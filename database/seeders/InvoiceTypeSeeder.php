<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InvoiceType;

class InvoiceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $invoiceTypes = [
            [
                'name' => 'ADMINISTRACIÓN',
                'description' => 'Cuota mensual de administración del conjunto residencial',
                'is_recurring' => true,
                'amount' => 150000, // Ajustar según tu conjunto
                'is_active' => true,
            ],
            [
                'name' => 'MULTA POR RUIDO',
                'description' => 'Multa por ruido excesivo después de las 10:00 PM según reglamento interno',
                'is_recurring' => false,
                'amount' => 50000,
                'is_active' => true,
            ],
            [
                'name' => 'MULTA POR MASCOTAS',
                'description' => 'Multa por no recoger excrementos de mascotas en zonas comunes',
                'is_recurring' => false,
                'amount' => 30000,
                'is_active' => true,
            ],
            [
                'name' => 'MULTA POR PARQUEADERO',
                'description' => 'Multa por parquear en lugar no autorizado o bloquear vías de acceso',
                'is_recurring' => false,
                'amount' => 40000,
                'is_active' => true,
            ],
            [
                'name' => 'MULTA POR BASURAS',
                'description' => 'Multa por sacar basuras en horarios no permitidos',
                'is_recurring' => false,
                'amount' => 25000,
                'is_active' => true,
            ],
            [
                'name' => 'MULTA POR VISITANTES',
                'description' => 'Multa por no reportar visitantes en portería',
                'is_recurring' => false,
                'amount' => 20000,
                'is_active' => true,
            ],
            [
                'name' => 'EVENTO ESPECIAL',
                'description' => 'Cobros extraordinarios por mejoras, reparaciones o eventos especiales',
                'is_recurring' => false,
                'amount' => null, // Variable según el evento
                'is_active' => true,
            ],
            [
                'name' => 'DAÑOS A ZONAS COMUNES',
                'description' => 'Cobro por reparación de daños causados a las zonas comunes',
                'is_recurring' => false,
                'amount' => null, // Variable según el daño
                'is_active' => true,
            ],
            [
                'name' => 'REPARACIÓN DE PARQUEADERO',
                'description' => 'Cobro por reparación de daños en parqueadero asignado',
                'is_recurring' => false,
                'amount' => null,
                'is_active' => true,
            ],
            [
                'name' => 'INTERESES DE MORA',
                'description' => 'Intereses generados por pagos tardíos (calculado automáticamente)',
                'is_recurring' => false,
                'amount' => null, // Calculado según porcentaje
                'is_active' => true,
            ],
            [
                'name' => 'CUOTA EXTRAORDINARIA',
                'description' => 'Cuota extraordinaria aprobada en asamblea de copropietarios',
                'is_recurring' => false,
                'amount' => null, // Variable según decisión de asamblea
                'is_active' => true,
            ],
            [
                'name' => 'RECONEXIÓN DE SERVICIOS',
                'description' => 'Cobro por reconexión de servicios comunes (agua, gas)',
                'is_recurring' => false,
                'amount' => 75000,
                'is_active' => true,
            ],
        ];

        foreach ($invoiceTypes as $type) {
            InvoiceType::updateOrCreate(
                ['name' => $type['name']],
                $type
            );
        }

        $this->command->info('✅ Tipos de factura creados: ' . count($invoiceTypes));
        $this->command->line('');
        $this->command->info('📄 Tipos de factura configurados:');
        
        foreach ($invoiceTypes as $type) {
            $amount = $type['amount'] ? '$' . number_format($type['amount'], 0) : 'Variable';
            $recurring = $type['is_recurring'] ? '(Mensual)' : '(Ocasional)';
            $this->command->line("   • {$type['name']} - {$amount} {$recurring}");
        }
    }
}