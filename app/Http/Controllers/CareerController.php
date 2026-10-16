<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Model\JobOpening;
use App\Model\JobApplication;

class CareerController extends Controller
{
    public function apply(Request $request)
    {
        $thanks = ['status' => 'success', 'message' => 'Thank you! Your application has been received. We will contact you if your profile matches.'];

        // Honeypot: real people never see this field, bots fill it.
        if ($request->filled('website')) {
            return response()->json($thanks);
        }

        $opening = $request->filled('job_opening_id')
            ? JobOpening::where('status', 'Yes')->find($request->input('job_opening_id'))
            : null;
        $profile = $opening ? $opening->profile : 'general';
        $questions = config('careers.profiles.' . $profile, []);

        $rules = [
            'first_name' => 'required|string|max:80',
            'last_name' => 'required|string|max:80',
            'email' => 'required|email|max:150',
            'phone' => 'required|string|max:30',
            'city' => 'required|string|max:80',
            'linkedin_url' => 'nullable|url|max:255',
            'education_level' => 'required|in:' . implode(',', array_keys(config('careers.education_levels'))),
            'degree' => 'required|string|max:120',
            'institution' => 'required|string|max:150',
            'grad_year' => 'required|digits:4',
            'has_experience' => 'required|in:yes,no',
            'expected_salary' => 'nullable|numeric|min:0',
            'cv' => 'required|file|max:5120',
        ];

        if ($request->input('has_experience') === 'yes') {
            $rules['experience_years'] = 'required|string|max:20';
            $rules['last_employer'] = 'required|string|max:150';
            $rules['last_title'] = 'required|string|max:150';
            if (!$request->boolean('is_unpaid')) {
                $rules['previous_salary'] = 'required|numeric|min:0';
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
                    $rules[$field . '.*'] = 'in:' . implode(',', $q['options']);
                    break;
                case 'select':
                    $rules[$field] = $req . '|in:' . implode(',', $q['options']);
                    break;
                case 'yesno':
                    $rules[$field] = $req . '|in:yes,no';
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

        // CV: whitelist extension AND mime type. Stored privately (storage/app/cvs), never under /public.
        $file = $request->file('cv');
        $ext = strtolower($file->getClientOriginalExtension());
        $okMime = [
            'application/pdf', 'application/msword', 'application/zip',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];
        if (!in_array($ext, ['pdf', 'doc', 'docx'], true) || !in_array($file->getMimeType(), $okMime, true)) {
            return response()->json(['status' => 'error', 'message' => 'Please upload your CV as a PDF or Word file.'], 422);
        }
        $cvPath = $file->storeAs('cvs', Str::random(32) . '.' . $ext);

        $unpaid = $request->input('has_experience') === 'yes' && $request->boolean('is_unpaid');
        $extra = [];
        foreach ($questions as $q) {
            $extra[$q['label']] = $request->input('extra.' . $q['key']);
        }

        JobApplication::create([
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
            'previous_salary' => $unpaid ? 'N/A' : $request->input('previous_salary'),
            'is_unpaid' => $unpaid,
            'expected_salary' => $request->input('expected_salary'),
            'extra_answers' => json_encode($extra),
            'cv_path' => $cvPath,
            'ip' => $request->ip(),
        ]);

        return response()->json($thanks);
    }
}
