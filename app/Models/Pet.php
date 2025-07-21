<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'apartment_id',
        'name',
        'type',
        'breed_id',
        'color_id',
    ];

    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }

    public function breed()
    {
        return $this->belongsTo(Breed::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }
}
