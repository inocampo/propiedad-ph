<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Apartment extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'resident_name',
        'resident_document',
        'resident_phone',
        'resident_email',
        'has_bicycles',
        'bicycles_count',
        'received_manual',
        'observations',
    ];

    protected $casts = [
        'has_bicycles' => 'boolean',
        'received_manual' => 'boolean',
        'bicycles_count' => 'integer',
    ];

    // Relaciones existentes
    public function residents()
    {
        return $this->hasMany(Resident::class);
    }

    public function owners()
    {
        return $this->hasMany(Owner::class);
    }

    public function minors()
    {
        return $this->hasMany(Minor::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function pets()
    {
        return $this->hasMany(Pet::class);
    }

    // 🔥 NUEVAS RELACIONES PARA EL SISTEMA DE CARTERA
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Scopes útiles para cartera
    public function scopeWithBalance($query)
    {
        return $query->whereHas('invoices', function ($q) {
            $q->where('status', '!=', 'paid');
        });
    }

    public function scopeWithOverdueInvoices($query)
    {
        return $query->whereHas('invoices', function ($q) {
            $q->where('status', 'overdue');
        });
    }

    // Accessors para cálculos de cartera
    public function getTotalInvoicedAttribute()
    {
        return $this->invoices()->sum('amount');
    }

    public function getTotalPaidAttribute()
    {
        return $this->payments()->sum('amount');
    }

    public function getBalanceAttribute()
    {
        return $this->total_invoiced - $this->total_paid;
    }

    public function getPendingInvoicesCountAttribute()
    {
        return $this->invoices()->where('status', 'pending')->count();
    }

    public function getOverdueInvoicesCountAttribute()
    {
        return $this->invoices()->where('status', 'overdue')->count();
    }

    public function getPaymentScoreAttribute()
    {
        if ($this->total_invoiced == 0) return 100;
        return round(($this->total_paid / $this->total_invoiced) * 100, 1);
    }
}