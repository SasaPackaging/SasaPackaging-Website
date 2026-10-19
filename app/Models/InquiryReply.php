<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * An email reply an admin sent to a contact message or a quote request.
 */
class InquiryReply extends Model
{
    protected $table = 'inquiry_replies';

    protected $fillable = ['inquiry_type', 'inquiry_id', 'admin_id', 'to_email', 'subject', 'body'];

    public function inquiry()
    {
        return $this->morphTo();
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
