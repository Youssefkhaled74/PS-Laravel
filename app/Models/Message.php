<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'conversation_id', 'sender_type', 'sender_id', 'body', 'message_type', 'attachment_path', 'read_at'
    ];

    protected $dates = ['read_at', 'created_at', 'updated_at'];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender()
    {
        if ($this->sender_type === 'vendor') {
            return $this->belongsTo(Vendor::class, 'sender_id');
        }

        return $this->belongsTo(User::class, 'sender_id');
    }
}
