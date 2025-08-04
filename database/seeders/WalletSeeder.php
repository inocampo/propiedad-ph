<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InvoiceType;
use App\Models\PaymentMethod;

class WalletSeeder extends Seeder
{
    public function run(): void
    {
        // Crear tipos de factura
        $this->createInvoiceTypes();
        
        // Crear métodos de pago
        $this->createPaymentMethods();
        
        $this->command->info('✅ Sistema de cartera configurado correctamente');
    }
    
    private function createInvoiceTypes(): void
    {
        $invoiceTypes = [
            [
                'name' => 'ADMINISTRACIÓN',
                'description' => 'Cuota mensual de administración del conjunto',
                'is_recurring' => true,
                'amount' => 150000, // Ajustar según tu conjunto
                'is_active' => true,
            ],
            [
                'name' => 'MULTA POR RUIDO',
                'description' => 'Multa por ruido excesivo después de las 10 PM',
                'is_recurring' => false,
                'amount' => 50000,
                'is_active' => true,
            ],
            [
                'name' => 'MULTA POR MASCOTAS',
                'description' => 'Multa por no recoger excrementos de mascotas',
                'is_recurring' => false,
                'amount' => 30000,
                'is_active' => true,
            ],
            [
                'name' => 'MULTA POR PARQUEADERO',
                'description' => 'Multa por parquear en lugar no autorizado',
                'is_recurring' => false,
                'amount' => 40000,
                'is_active' => true,
            ],
            [
                'name' => 'EVENTO ESPECIAL',
                'description' => 'Cobros por eventos especiales (mejoras, reparaciones)',
                'is_recurring' => false,
                'amount' => null, // Variable según el evento
                'is_active' => true,
            ],
            [
                'name' => 'DAÑOS COMUNES',
                'description' => 'Cobro por daños a zonas comunes',
                'is_recurring' => false,
                'amount' => null, // Variable según el daño
                'is_active' => true,
            ],
            [
                'name' => 'INTERESES DE MORA',
                'description' => 'Intereses por pagos tardíos',
                'is_recurring' => false,
                'amount' => null, // Calculado según porcentaje
                'is_active' => true,
            ],
        ];
        
        foreach ($invoiceTypes as $type) {
            InvoiceType::updateOrCreate(
                ['name' => $type['name']],
                $type
            );
        }
        
        $this->command->info('📄 Tipos de factura creados: ' . count($invoiceTypes));
    }
    
    private function createPaymentMethods(): void
    {
        $paymentMethods = [
            [
                'name' => 'EFECTIVO',
                'description' => 'Pago en efectivo en administración',
                'requires_reference' => false,
                'is_active' => true,
            ],
            [
                'name' => 'TRANSFERENCIA BANCARIA',
                'description' => 'Transferencia electrónica a cuenta del conjunto',
                'requires_reference' => true,
                'is_active' => true,
            ],
            [
                'name' => 'CONSIGNACIÓN BANCARIA',
                'description' => 'Consignación en sucursal bancaria',
                'requires_reference' => true,
                'is_active' => true,
            ],
            [
                'name' => 'PAGO MÓVIL (NEQUI/DAVIPLATA)',
                'description' => 'Pago a través de billeteras digitales',
                'requires_reference' => true,
                'is_active' => true,
            ],
            [
                'name' => 'TARJETA DE CRÉDITO/DÉBITO',
                'description' => 'Pago con tarjeta (si aplica)',
                'requires_reference' => true,
                'is_active' => false, // Deshabilitado hasta configurar datáfono
            ],
            [
                'name' => 'CHEQUE',
                'description' => 'Pago con cheque',
                'requires_reference' => true,
                'is_active' => true,
            ],
        ];
        
        foreach ($paymentMethods as $method) {
            PaymentMethod::updateOrCreate(
                ['name' => $method['name']],
                $method
            );
        }
        
        $this->command->info('💳 Métodos de pago creados: ' . count($paymentMethods));
    }
}