<?php

namespace App\Models;

use App\Models\Concerns\InboxItem;
use Illuminate\Database\Eloquent\Model;

/**
 * A message sent from the website's Contact us form (table: contact_messages).
 */
class ContactMessage extends Model
{
    use InboxItem;

    protected $table = 'contact_messages';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'ip_address',
        'user_agent',
        'mail_sent_at',
        'read_at',
        'replied_at',
    ];

    protected $casts = [
        'mail_sent_at' => 'datetime',
        'read_at'      => 'datetime',
        'replied_at'   => 'datetime',
    ];

    public static function searchableColumns()
    {
        return ['name', 'email', 'phone', 'subject', 'message'];
    }
}
