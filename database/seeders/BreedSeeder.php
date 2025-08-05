<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Breed;

class BreedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $breeds = [
            // Razas de perros más comunes en Colombia
            'LABRADOR RETRIEVER',
            'GOLDEN RETRIEVER',
            'PASTOR ALEMÁN',
            'BULLDOG FRANCÉS',
            'POODLE',
            'CHIHUAHUA',
            'YORKSHIRE TERRIER',
            'BEAGLE',
            'ROTTWEILER',
            'BOXER',
            'SHIH TZU',
            'DACHSHUND',
            'MALTÉS',
            'COCKER SPANIEL',
            'BORDER COLLIE',
            'HUSKY SIBERIANO',
            'PINSCHER',
            'SCHNAUZER',
            'PITBULL',
            'CRIOLLO',
            
            // Razas de gatos más comunes en Colombia
            'SIAMÉS',
            'PERSA',
            'MAINE COON',
            'BRITÁNICO DE PELO CORTO',
            'RAGDOLL',
            'BENGALÍ',
            'ABISINIO',
            'SCOTTISH FOLD',
            'MUNCHKIN',
            'SPHYNX',
            'ANGORA TURCO',
            'AZUL RUSO',
            'BOMBAY',
            'BIRMANO',
            'EXÓTICO DE PELO CORTO',
            'AMERICAN SHORTHAIR',
            'ORIENTAL',
            'NORUEGO DEL BOSQUE',
            'COMÚN EUROPEO',
            'CRIOLLO',
            
            // Opción genérica
            'OTRO',
        ];

        foreach ($breeds as $breed) {
            Breed::create([
                'name' => $breed
            ]);
        }
    }
}