<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'actor_user_id',
        'actor_name_snapshot',
        'actor_email_snapshot',
        'category',
        'action',
        'status',
        'subject_type',
        'subject_id',
        'subject_label',
        'message',
        'route_name',
        'http_method',
        'ip_address',
        'user_agent',
        'request_id',
        'before_data',
        'after_data',
        'meta',
    ];

    protected $casts = [
        'before_data' => 'array',
        'after_data' => 'array',
        'meta' => 'array',
    ];

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_user_id');
    }
}
