<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            // Marcas compartidas (carros y motos)
            'HONDA',
            'SUZUKI',
            'BMW',
            
            // Marcas principalmente de carros
            'TOYOTA',
            'CHEVROLET',
            'RENAULT',
            'NISSAN',
            'HYUNDAI',
            'KIA',
            'MAZDA',
            'FORD',
            'VOLKSWAGEN',
            'MITSUBISHI',
            'PEUGEOT',
            'CITROEN',
            'FIAT',
            'JEEP',
            'SUBARU',
            'ISUZU',
            'GREAT WALL',
            'CHERY',
            
            // Marcas principalmente de motos
            'YAMAHA',
            'KAWASAKI',
            'BAJAJ',
            'TVS',
            'AKT',
            'AUTECO',
            'HERO',
            'DUCATI',
            'KTM',
            'HUSQVARNA',
            'BENELLI',
            'CF MOTO',
            'KEEWAY',
            'UM',
            'ROYAL ENFIELD',
            'VICTORY',
            'INDIAN',
        ];

        foreach ($brands as $brand) {
            Brand::create([
                'name' => $brand
            ]);
        }
    }
}