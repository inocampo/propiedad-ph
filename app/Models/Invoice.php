<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'apartment_id',
        'invoice_type_id',
        'number',
        'period', // 2025-01 para enero 2025
        'issue_date',
        'due_date',
        'amount',
        'description',
        'status', // pending, paid, overdue, cancelled
        'payment_date',
        'notes',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    // Relaciones
    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }

    public function invoiceType()
    {
        return $this->belongsTo(InvoiceType::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue')
                    ->orWhere(function($q) {
                        $q->where('status', 'pending')
                          ->where('due_date', '<', now());
                    });
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    // Accessors
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'Pendiente',
            'paid' => 'Pagado',
            'overdue' => 'Vencido',
            'cancelled' => 'Cancelado',
            default => 'Desconocido'
        };
    }

    public function getIsOverdueAttribute()
    {
        return $this->status === 'pending' && $this->due_date < now();
    }

    public function getDaysOverdueAttribute()
    {
        if (!$this->is_overdue) return 0;
        return $this->due_date->diffInDays(now());
    }

    public function getPaidAmountAttribute()
    {
        return $this->payments()->sum('amount');
    }

    public function getBalanceAttribute()
    {
        return $this->amount - $this->paid_amount;
    }

    // Métodos
    public function markAsPaid($paymentDate = null)
    {
        $this->update([
            'status' => 'paid',
            'payment_date' => $paymentDate ?? now(),
        ]);
    }

    public function updateStatus()
    {
        if ($this->paid_amount >= $this->amount) {
            $this->status = 'paid';
            $this->payment_date = $this->payments()->latest()->first()?->payment_date;
        } elseif ($this->due_date < now() && $this->status === 'pending') {
            $this->status = 'overdue';
        }
        $this->save();
    }
}