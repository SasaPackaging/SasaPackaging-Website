<?php

namespace App\Services\Quote;

use App\Models\QuoteRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Turns a validated quote form into a saved QuoteRequest (and stores the attached file).
 */
class QuoteSubmissionService
{
    protected $options;

    public function __construct(QuoteOptionsService $options)
    {
        $this->options = $options;
    }

    /**
     * Saves the request. If anything fails, NOTHING stays behind: the database rows are rolled back
     * and the uploaded file is deleted, then the error is thrown again for the controller to log.
     */
    public function store(array $data, UploadedFile $file = null, array $meta = [])
    {
        $stored = null;

        try {
            if ($file) {
                $stored = $this->storeFile($file);
            }

            return DB::transaction(function () use ($data, $file, $meta, $stored) {
                $record = new QuoteRequest($data + $meta);

                if (!empty($data['product_id'])) {
                    $record->product_label = $this->options->label('products', $data['product_id']);
                }
                if (!empty($data['service_id'])) {
                    $record->service_label = $this->options->label('services', $data['service_id']);
                }

                if ($file && $stored) {
                    $record->file_path          = $stored;
                    $record->file_original_name = Str::limit($this->safeName($file->getClientOriginalName()), 240, '');
                    $record->file_size          = $file->getSize();
                }

                $record->save();

                // Reference such as Q-00042, built from the row id so it is always unique.
                $record->reference = config('quote.reference_prefix')
                    . str_pad($record->id, (int) config('quote.reference_pad'), '0', STR_PAD_LEFT);
                $record->save();

                return $record;
            });
        } catch (\Throwable $e) {
            if ($stored) {
                Storage::disk(config('quote.file.disk'))->delete($stored); // no record, so no orphan file
            }

            throw $e;
        }
    }

    /** The customer's original file name is only shown to admins: keep it a plain, harmless name. */
    protected function safeName($name)
    {
        $name = preg_replace('/[\x00-\x1F\x7F\/\\\\]+/', '', (string) $name);

        return $name !== '' ? $name : 'file';
    }

    protected function storeFile(UploadedFile $file)
    {
        $cfg = config('quote.file');

        // A random name + a whitelisted extension: the customer's file name is never used on disk.
        $name = Str::uuid() . '.' . strtolower($file->getClientOriginalExtension());
        $dir  = trim($cfg['directory'], '/') . '/' . date('Y/m');

        return $file->storeAs($dir, $name, $cfg['disk']);
    }

    public static function deleteFile(QuoteRequest $record)
    {
        if ($record->hasFile()) {
            Storage::disk(config('quote.file.disk'))->delete($record->file_path);
        }
    }
}
