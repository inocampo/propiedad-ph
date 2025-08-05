<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Relationship;

class RelationshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $relationships = [
            'PROPIETARIO',
            'CÓNYUGE',
            'ESPOSO',
            'ESPOSA',
            'COMPAÑERO',
            'COMPAÑERA',
            'PAREJA',
            'HIJO',
            'HIJA',
            'PADRE',
            'MADRE',
            'HERMANO',
            'HERMANA',
            'ABUELO',
            'ABUELA',
            'NIETO',
            'NIETA',
            'TÍO',
            'TÍA',
            'SOBRINO',
            'SOBRINA',
            'PRIMO',
            'PRIMA',
            'CUÑADO',
            'CUÑADA',
            'SUEGRO',
            'SUEGRA',
            'YERNO',
            'NUERA',
            'PADRINO',
            'MADRINA',
            'AHIJADO',
            'AHIJADA',
            'HIJASTRO',
            'HIJASTRA',
            'PADRASTRO',
            'MADRASTRA',
            'ARRENDATARIO',
            'OTRO',
        ];

        foreach ($relationships as $relationship) {
            Relationship::create([
                'name' => $relationship
            ]);
        }
    }
}