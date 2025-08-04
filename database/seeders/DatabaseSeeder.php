<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🚀 Iniciando configuración de la base de datos...');
        $this->command->line('');

        // Crear usuario administrador principal
        $this->createAdminUsers();

        // Ejecutar seeders del sistema de cartera
        $this->call([
            InvoiceTypeSeeder::class,
            PaymentMethodSeeder::class,
            // WalletSeeder::class, ← COMENTADO para evitar duplicación
            // UserSeeder::class, // Opcional: usuarios adicionales
        ]);

        $this->command->line('');
        $this->command->info('🎉 ¡Base de datos configurada exitosamente!');
        $this->command->line('');
        $this->command->info('📧 Credenciales de acceso:');
        $this->command->info('   Email: admin@gualanday.com');
        $this->command->info('   Password: admin123');
        $this->command->line('');
        $this->command->warn('⚠️  Recuerda cambiar las credenciales en producción');
    }

    /**
     * Crear usuarios administradores
     */
    private function createAdminUsers(): void
    {
        $this->command->info('👤 Creando usuarios administradores...');

        // Usuario administrador principal
        User::updateOrCreate(
            ['email' => 'admin@gualanday.com'],
            [
                'name' => 'Administrador Gualanday',
                'email' => 'admin@gualanday.com',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        // Mantener el usuario de prueba original si lo deseas
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Usuario de Prueba',
                'email' => 'test@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('✅ Usuarios creados correctamente');
    }
}