<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_recurring',
        'amount',
        'is_active',
    ];

    protected $casts = [
        'is_recurring' => 'boolean',
        'is_active' => 'boolean',
        'amount' => 'decimal:2',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}