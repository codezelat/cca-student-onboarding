<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramIntakeWindow extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id',
        'window_name',
        'opens_at',
        'closes_at',
        'price_override',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'opens_at' => 'datetime',
        'closes_at' => 'datetime',
        'price_override' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }
}
