<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistrationPayment extends Model
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_VOID = 'void';

    protected $table = 'registration_payments';

    protected $fillable = [
        'cca_registration_id',
        'payment_no',
        'payment_date',
        'amount',
        'payment_method',
        'receipt_reference',
        'note',
        'status',
        'void_reason',
        'voided_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
        'voided_at' => 'datetime',
    ];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(CCARegistration::class, 'cca_registration_id');
    }
}
