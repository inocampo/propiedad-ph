<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'invoice_id',
        'apartment_id',
        'payment_method_id',
        'amount',
        'payment_date',
        'reference_number',
        'notes',
        'received_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    // Relaciones
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function receivedByUser()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    // Scopes
    public function scopeToday($query)
    {
        return $query->whereDate('payment_date', today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('payment_date', now()->month)
                    ->whereYear('payment_date', now()->year);
    }

    public function scopeByApartment($query, $apartmentId)
    {
        return $query->where('apartment_id', $apartmentId);
    }

    public function scopeByPaymentMethod($query, $methodId)
    {
        return $query->where('payment_method_id', $methodId);
    }
}