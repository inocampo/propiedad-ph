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
            // Marcas de carros populares en Colombia
            'TOYOTA',
            'CHEVROLET',
            'RENAULT',
            'NISSAN',
            'HYUNDAI',
            'KIA',
            'MAZDA',
            'FORD',
            'VOLKSWAGEN',
            'HONDA',
            'SUZUKI',
            'MITSUBISHI',
            'PEUGEOT',
            'CITROEN',
            'FIAT',
            'JEEP',
            'SUBARU',
            'ISUZU',
            'GREAT WALL',
            'CHERY',
            
            // Marcas de motos populares en Colombia
            'YAMAHA',
            'HONDA',
            'SUZUKI',
            'KAWASAKI',
            'BAJAJ',
            'TVS',
            'AKT',
            'AUTECO',
            'HERO',
            'DUCATI',
            'BMW',
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