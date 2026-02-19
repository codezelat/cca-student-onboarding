<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'year_label',
        'duration_label',
        'base_price',
        'currency',
        'is_active',
        'display_order',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    public function intakeWindows(): HasMany
    {
        return $this->hasMany(ProgramIntakeWindow::class)
            ->orderByDesc('opens_at');
    }
}
