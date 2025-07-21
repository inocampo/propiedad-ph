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
}
