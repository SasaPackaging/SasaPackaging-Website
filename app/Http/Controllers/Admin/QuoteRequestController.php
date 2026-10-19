<?php

namespace App\Http\Controllers\Admin;

use App\Models\QuoteRequest;
use App\Services\Quote\QuoteSubmissionService;
use Illuminate\Support\Facades\Storage;

/**
 * Admin inbox for the Get a Quote form (+ download of the customer's file).
 */
class QuoteRequestController extends InboxController
{
    protected function model()
    {
        return QuoteRequest::class;
    }

    protected function routeBase()
    {
        return 'admin.quote-requests';
    }

    protected function viewBase()
    {
        return 'admin.quote-requests';
    }

    protected function title()
    {
        return 'Quote requests';
    }

    /** Only logged-in admins reach this route, so the private file is never public. */
    public function file($id)
    {
        $item = $this->find($id);
        $disk = Storage::disk(config('quote.file.disk'));

        abort_unless($item->hasFile() && $disk->exists($item->file_path), 404);

        return $disk->download($item->file_path, $item->file_original_name);
    }

    protected function beforeDelete($item)
    {
        QuoteSubmissionService::deleteFile($item);
    }
}
