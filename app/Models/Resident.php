<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Resident extends Model
{
    use HasFactory;

    protected $fillable = [
        'apartment_id',
        'name',
        'document_number',
        'phone_number',
        'phone_for_intercom',
        'relationship_id',
    ];

    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }

    public function relationship()
    {
        return $this->belongsTo(Relationship::class);
    }
}
