<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Color;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = [
            'BLANCO',
            'NEGRO',
            'GRIS',
            'PLATA',
            'AZUL',
            'ROJO',
            'VERDE',
            'AMARILLO',
            'NARANJA',
            'MORADO',
            'ROSA',
            'CAFÉ',
            'MARRÓN',
            'BEIGE',
            'DORADO',
            'BRONCE',
            'TURQUESA',
            'VINO TINTO',
            'AZUL MARINO',
            'GRIS OSCURO',
            'GRIS CLARO',
            'CREMA',
            'PERLA',
            'CHAMPAGNE',
            'METALIZADO',
            'MULTICOLOR',
            'OTRO',
        ];

        foreach ($colors as $color) {
            Color::create([
                'name' => $color
            ]);
        }
    }
}