<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Model\JobOpening;
use App\Model\JobApplication;
use App\Support\Sanitize;
use App\Support\UploadGuard;

class CareerController extends Controller
{
    public function apply(Request $request)
    {
        $thanks = ['status' => 'success', 'message' => 'Thank you! Your application has been received. We will contact you if your profile matches.'];

        // Honeypot: real people never see this field, bots fill it.
        if ($request->filled('website')) {
            return response()->json($thanks);
        }

        // Clean every text field first: no control characters, no line breaks in one-line fields.
        // (The CV file input is not touched.)
        $request->merge(Sanitize::deep($request->except('cv')));

        // The opening id must be a plain whole number, otherwise it is ignored (general application).
        $openingId = $request->input('job_opening_id');
        $opening = (is_scalar($openingId) && ctype_digit((string) $openingId))
            ? JobOpening::where('status', 'Yes')->find((int) $openingId)
            : null;
        $profile = $opening ? $opening->profile : 'general';
        $questions = config('careers.profiles.' . $profile, []);

        $name = ['required', 'string', 'min:2', 'max:80', "regex:/^[\\pL\\pM][\\pL\\pM\\s.'\\-]*$/u"];

        $rules = [
            'first_name' => $name,
            'last_name' => $name,
            'email' => ['required', 'email:rfc,filter', 'max:150'],
            // digits with + - ( ) . and spaces, 7 to 15 digits in total
            'phone' => ['required', 'string', 'max:40', 'regex:/^\+?[0-9\s().\-]+$/', function ($attribute, $value, $fail) {
                $digits = strlen(preg_replace('/\D/', '', is_scalar($value) ? (string) $value : ''));
                if ($digits < 7 || $digits > 15) {
                    $fail(__('inbox.error.phone'));
                }
            }],
            'city' => 'required|string|min:2|max:80',
            // only web addresses that start with http(s):// (a javascript: link is not a valid url here)
            'linkedin_url' => ['nullable', 'url', 'regex:#^https?://#i', 'max:255'],
            'education_level' => ['required', Rule::in(array_keys(config('careers.education_levels')))],
            'degree' => 'required|string|max:120',
            'institution' => 'required|string|max:150',
            'grad_year' => ['required', 'digits:4', 'integer', 'between:1950,' . (date('Y') + 8)],
            'has_experience' => ['required', Rule::in(['yes', 'no'])],
            'expected_salary' => 'nullable|numeric|min:0|max:100000000',
            'cv' => ['bail', 'required', 'file', 'max:5120'],
            'website' => 'nullable|string|max:255', // honeypot (handled above)
        ];

        if ($request->input('has_experience') === 'yes') {
            $rules['experience_years'] = 'required|string|max:20';
            $rules['last_employer'] = 'required|string|max:150';
            $rules['last_title'] = 'required|string|max:150';
            if (!$request->boolean('is_unpaid')) {
                $rules['previous_salary'] = 'required|numeric|min:0|max:100000000';
            }
        }

        // Role-specific questions: only keys defined in config/careers.php are accepted.
        foreach ($questions as $q) {
            $field = 'extra.' . $q['key'];
            $req = !empty($q['required']) ? 'required' : 'nullable';
            switch ($q['type']) {
                case 'url':
                    $rules[$field] = $req . '|url|max:255';
                    break;
                case 'checkboxes':
                    $rules[$field] = $req . '|array';
                    $rules[$field . '.*'] = [Rule::in($q['options'])];
                    break;
                case 'select':
                    $rules[$field] = [$req, Rule::in($q['options'])];
                    break;
                case 'yesno':
                    $rules[$field] = [$req, Rule::in(['yes', 'no'])];
                    break;
                default:
                    $rules[$field] = $req . '|string|max:600';
            }
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        // CV: whitelist extension AND real content type. Stored privately (storage/app/cvs), never under /public.
        $file = $request->file('cv');
        if (!UploadGuard::isUsable($file)) {
            return response()->json(['status' => 'error', 'message' => __('inbox.error.file_upload')], 422);
        }

        $ext = strtolower($file->getClientOriginalExtension());
        if (!in_array($ext, ['pdf', 'doc', 'docx'], true) || !UploadGuard::mimeMatchesExtension($file, $ext) || UploadGuard::looksLikeScript($file)) {
            return response()->json(['status' => 'error', 'message' => 'Please upload your CV as a PDF or Word file.'], 422);
        }

        try {
            $cvPath = $file->storeAs('cvs', Str::random(32) . '.' . $ext);
        } catch (\Throwable $e) {
            Log::error('Career form: could not store the CV: ' . $e->getMessage());

            return response()->json(['status' => 'error', 'message' => __('inbox.error.generic')], 500);
        }

        $unpaid = $request->input('has_experience') === 'yes' && $request->boolean('is_unpaid');
        $extra = [];
        foreach ($questions as $q) {
            $extra[$q['label']] = $request->input('extra.' . $q['key']);
        }

        try {
            $row = [
                'job_opening_id' => $opening ? $opening->id : null,
                'position_title' => $opening ? $opening->title : 'General application',
                'first_name' => trim($request->input('first_name')),
                'last_name' => trim($request->input('last_name')),
                'email' => trim($request->input('email')),
                'phone' => trim($request->input('phone')),
                'city' => trim($request->input('city')),
                'linkedin_url' => $request->input('linkedin_url'),
                'education_level' => $request->input('education_level'),
                'degree' => trim($request->input('degree')),
                'institution' => trim($request->input('institution')),
                'grad_year' => $request->input('grad_year'),
                'has_experience' => $request->input('has_experience') === 'yes',
                'experience_years' => $request->input('experience_years'),
                'last_employer' => $request->input('last_employer'),
                'last_title' => $request->input('last_title'),
                // the column is a number: NULL means "not given / unpaid" (see the migration comment)
                'previous_salary' => $unpaid ? null : $request->input('previous_salary'),
                'is_unpaid' => $unpaid,
                'expected_salary' => $request->input('expected_salary'),
                'extra_answers' => json_encode($extra, JSON_UNESCAPED_UNICODE),
                'cv_path' => $cvPath,
                'ip' => $request->ip(),
            ];

            // older databases may not have the is_unpaid column yet (run: php artisan migrate); never crash because of it
            if (!Schema::hasColumn('tbl_job_applications', 'is_unpaid')) {
                unset($row['is_unpaid']);
            }

            JobApplication::create($row);
        } catch (\Throwable $e) {
            Storage::delete($cvPath); // no application row, so do not keep an orphan CV
            Log::error('Career form: could not save the application: ' . $e->getMessage());

            return response()->json(['status' => 'error', 'message' => __('inbox.error.generic')], 500);
        }

        return response()->json($thanks);
    }
}
