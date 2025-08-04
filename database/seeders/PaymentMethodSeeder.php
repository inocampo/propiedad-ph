<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentMethods = [
            [
                'name' => 'EFECTIVO',
                'description' => 'Pago en efectivo directamente en administración',
                'requires_reference' => false,
                'is_active' => true,
            ],
            [
                'name' => 'TRANSFERENCIA BANCARIA',
                'description' => 'Transferencia electrónica a cuenta bancaria del conjunto',
                'requires_reference' => true,
                'is_active' => true,
            ],
            [
                'name' => 'CONSIGNACIÓN BANCARIA',
                'description' => 'Consignación en efectivo o cheque en sucursal bancaria',
                'requires_reference' => true,
                'is_active' => true,
            ],
            [
                'name' => 'NEQUI',
                'description' => 'Pago a través de la billetera digital Nequi',
                'requires_reference' => true,
                'is_active' => true,
            ],
            [
                'name' => 'DAVIPLATA',
                'description' => 'Pago a través de la billetera digital DaviPlata',
                'requires_reference' => true,
                'is_active' => true,
            ],
            [
                'name' => 'BANCOLOMBIA A LA MANO',
                'description' => 'Pago a través de Bancolombia a la Mano',
                'requires_reference' => true,
                'is_active' => true,
            ],
            [
                'name' => 'PSE',
                'description' => 'Pago por PSE (Pagos Seguros en Línea)',
                'requires_reference' => true,
                'is_active' => false, // Activar cuando se configure
            ],
            [
                'name' => 'TARJETA DE CRÉDITO',
                'description' => 'Pago con tarjeta de crédito (Visa, MasterCard)',
                'requires_reference' => true,
                'is_active' => false, // Activar cuando se configure datáfono
            ],
            [
                'name' => 'TARJETA DE DÉBITO',
                'description' => 'Pago con tarjeta débito',
                'requires_reference' => true,
                'is_active' => false, // Activar cuando se configure datáfono
            ],
            [
                'name' => 'CHEQUE',
                'description' => 'Pago con cheque personal o de gerencia',
                'requires_reference' => true,
                'is_active' => true,
            ],
            [
                'name' => 'GIRO POSTAL',
                'description' => 'Pago a través de giro postal',
                'requires_reference' => true,
                'is_active' => true,
            ],
            [
                'name' => 'COMPENSACIÓN',
                'description' => 'Compensación por servicios prestados al conjunto',
                'requires_reference' => false,
                'is_active' => true,
            ],
            [
                'name' => 'DESCUENTO ESPECIAL',
                'description' => 'Descuento autorizado por administración',
                'requires_reference' => false,
                'is_active' => true,
            ],
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::updateOrCreate(
                ['name' => $method['name']],
                $method
            );
        }

        $this->command->info('✅ Métodos de pago creados: ' . count($paymentMethods));
        $this->command->line('');
        $this->command->info('💳 Métodos de pago configurados:');
        
        $activeCount = 0;
        $inactiveCount = 0;
        
        foreach ($paymentMethods as $method) {
            $status = $method['is_active'] ? '✅ Activo' : '⏸️  Inactivo';
            $reference = $method['requires_reference'] ? '(Requiere referencia)' : '(Sin referencia)';
            
            if ($method['is_active']) {
                $activeCount++;
                $this->command->line("   • {$method['name']} {$status} {$reference}");
            } else {
                $inactiveCount++;
            }
        }
        
        if ($inactiveCount > 0) {
            $this->command->line('');
            $this->command->info("⏸️  Métodos inactivos ({$inactiveCount}):");
            foreach ($paymentMethods as $method) {
                if (!$method['is_active']) {
                    $this->command->line("   • {$method['name']} (Para activar más adelante)");
                }
            }
        }
        
        $this->command->line('');
        $this->command->info("📊 Resumen: {$activeCount} métodos activos, {$inactiveCount} inactivos");
    }
}