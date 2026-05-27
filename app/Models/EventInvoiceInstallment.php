<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventInvoiceInstallment extends Model
{
    protected $fillable = [
        'invoice_id',
        'label',
        'amount',
        'due_date',
        'paid_amount',
        'payment_method',
        'transaction_reference',
        'paid_at',
        'status',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_at' => 'datetime',
    ];

    public function invoice()
    {
        return $this->belongsTo(EventInvoice::class, 'invoice_id');
    }
}
