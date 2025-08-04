<?php

// En app/Console/Kernel.php
namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // Generar facturas mensuales el día 1 de cada mes
        $schedule->command('invoices:generate-monthly')
            ->monthlyOn(1, '08:00')
            ->emailOutputOnFailure('admin@gualanday.com');

        // Actualizar facturas vencidas diariamente
        $schedule->command('invoices:update-overdue')
            ->dailyAt('06:00')
            ->withoutOverlapping();

        // Enviar recordatorios cada lunes a las 9 AM
        $schedule->command('invoices:send-reminders --type=both')
            ->weeklyOn(1, '09:00')
            ->emailOutputOnFailure('admin@gualanday.com');

        // Enviar recordatorio de facturas que vencen mañana
        $schedule->command('invoices:send-upcoming-reminders')
            ->dailyAt('18:00')
            ->withoutOverlapping();

        // Backup diario de la base de datos
        $schedule->command('backup:run --only-db')
            ->dailyAt('02:00')
            ->emailOutputOnFailure('admin@gualanday.com');

        // Limpiar logs antiguos
        $schedule->command('log:clear')
            ->weekly()
            ->sundays()
            ->at('03:00');
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

// =====================================================

// Comando adicional para recordatorios de vencimiento
namespace App\Console\Commands;

use App\Models\Invoice;
use Illuminate\Console\Command;

class SendUpcomingReminders extends Command
{
    protected $signature = 'invoices:send-upcoming-reminders';
    protected $description = 'Enviar recordatorios de facturas que vencen mañana';

    public function handle()
    {
        $this->info('Enviando recordatorios de vencimiento...');
        
        $upcomingInvoices = Invoice::pending()
            ->whereDate('due_date', now()->addDay())
            ->with(['apartment'])
            ->get();
        
        $sent = 0;
        
        foreach ($upcomingInvoices as $invoice) {
            // Enviar WhatsApp
            $this->sendWhatsAppReminder($invoice);
            
            // Enviar Email
            $this->sendEmailReminder($invoice);
            
            $sent++;
        }
        
        $this->info("✅ {$sent} recordatorios de vencimiento enviados");
    }
    
    private function sendWhatsAppReminder($invoice)
    {
        $message = "Hola {$invoice->apartment->resident_name}, tu factura {$invoice->number} por valor de $" . 
                  number_format($invoice->amount, 0) . " vence mañana. Por favor realizar el pago.";
        
        // Implementar con tu servicio de WhatsApp preferido
        // Ejemplo con Twilio:
        /*
        $twilio = new \Twilio\Rest\Client(config('twilio.sid'), config('twilio.token'));
        $twilio->messages->create(
            'whatsapp:+57' . $invoice->apartment->resident_phone,
            [
                'from' => 'whatsapp:' . config('twilio.whatsapp_number'),
                'body' => $message
            ]
        );
        */
    }
    
    private function sendEmailReminder($invoice)
    {
        // Implementar con Laravel Mail
        /*
        Mail::to($invoice->apartment->resident_email)
            ->send(new InvoiceReminderMail($invoice, 'upcoming'));
        */
    }
}

// =====================================================

// Observers para automatizar procesos
namespace App\Observers;

use App\Models\Payment;

class PaymentObserver
{
    public function created(Payment $payment): void
    {
        // Actualizar estado de la factura cuando se registra un pago
        $payment->invoice->updateStatus();
        
        // Log del pago
        \Log::info("Pago registrado: {$payment->amount} para factura {$payment->invoice->number}");
        
        // Enviar notificación de confirmación (opcional)
        $this->sendPaymentConfirmation($payment);
    }
    
    private function sendPaymentConfirmation(Payment $payment)
    {
        $message = "Pago recibido correctamente. Factura: {$payment->invoice->number}, Valor: $" . 
                  number_format($payment->amount, 0) . ", Fecha: " . $payment->payment_date->format('d/m/Y');
        
        // Enviar confirmación por WhatsApp o Email
    }
}

// =====================================================

// Service para WhatsApp
namespace App\Services;

class WhatsAppService
{
    protected $twilio;
    
    public function __construct()
    {
        $this->twilio = new \Twilio\Rest\Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );
    }
    
    public function sendMessage(string $to, string $message): bool
    {
        try {
            $this->twilio->messages->create(
                'whatsapp:+57' . $to,
                [
                    'from' => 'whatsapp:' . config('services.twilio.whatsapp_number'),
                    'body' => $message
                ]
            );
            
            return true;
        } catch (\Exception $e) {
            \Log::error("Error enviando WhatsApp: " . $e->getMessage());
            return false;
        }
    }
    
    public function sendInvoiceReminder($invoice, $type = 'overdue'): bool
    {
        $messages = [
            'overdue' => "🏠 *Conjunto Residencial Gualanday*\n\n" .
                        "Hola {$invoice->apartment->resident_name},\n\n" .
                        "Tienes una factura *VENCIDA*:\n" .
                        "📄 Factura: {$invoice->number}\n" .
                        "💰 Valor: $" . number_format($invoice->amount, 0) . "\n" .
                        "📅 Venció: " . $invoice->due_date->format('d/m/Y') . "\n" .
                        "⏰ Días vencida: {$invoice->days_overdue}\n\n" .
                        "Por favor realizar el pago lo antes posible.",
            
            'upcoming' => "🏠 *Conjunto Residencial Gualanday*\n\n" .
                         "Hola {$invoice->apartment->resident_name},\n\n" .
                         "Recordatorio: Tu factura vence mañana:\n" .
                         "📄 Factura: {$invoice->number}\n" .
                         "💰 Valor: $" . number_format($invoice->amount, 0) . "\n" .
                         "📅 Vence: " . $invoice->due_date->format('d/m/Y') . "\n\n" .
                         "No olvides realizar tu pago a tiempo.",
        ];
        
        return $this->sendMessage(
            $invoice->apartment->resident_phone,
            $messages[$type] ?? $messages['overdue']
        );
    }
}

// =====================================================

// Mail para notificaciones
namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;
    public $type;

    public function __construct(Invoice $invoice, string $type = 'overdue')
    {
        $this->invoice = $invoice;
        $this->type = $type;
    }

    public function build()
    {
        $subject = $this->type === 'overdue' 
            ? 'Factura Vencida - Conjunto Residencial Gualanday'
            : 'Recordatorio de Vencimiento - Conjunto Residencial Gualanday';
            
        return $this->subject($subject)
                    ->view('emails.invoice-reminder')
                    ->with([
                        'invoice' => $this->invoice,
                        'type' => $this->type,
                        'apartment' => $this->invoice->apartment,
                    ]);
    }
}

// =====================================================

// Vista del email: resources/views/emails/invoice-reminder.blade.php
/*
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $type === 'overdue' ? 'Factura Vencida' : 'Recordatorio de Pago' }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; }
        .header { text-align: center; margin-bottom: 30px; }
        .logo { max-width: 200px; }
        .alert { padding: 15px; border-radius: 5px; margin: 20px 0; }
        .alert-danger { background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
        .alert-warning { background-color: #fff3cd; border: 1px solid #ffeaa7; color: #856404; }
        .invoice-details { background: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="Gualanday" class="logo">
            <h1>Conjunto Residencial Gualanday</h1>
        </div>

        <h2>Hola {{ $apartment->resident_name }},</h2>

        @if($type === 'overdue')
            <div class="alert alert-danger">
                <strong>⚠️ FACTURA VENCIDA</strong><br>
                Tienes una factura vencida que requiere pago inmediato.
            </div>
        @else
            <div class="alert alert-warning">
                <strong>📅 RECORDATORIO DE VENCIMIENTO</strong><br>
                Tu factura vence mañana. No olvides realizar el pago.
            </div>
        @endif

        <div class="invoice-details">
            <h3>Detalles de la Factura:</h3>
            <p><strong>Apartamento:</strong> {{ $apartment->number }}</p>
            <p><strong>Número de Factura:</strong> {{ $invoice->number }}</p>
            <p><strong>Tipo:</strong> {{ $invoice->invoiceType->name }}</p>
            <p><strong>Período:</strong> {{ $invoice->period }}</p>
            <p><strong>Valor:</strong> ${{ number_format($invoice->amount, 0) }}</p>
            <p><strong>Fecha de Vencimiento:</strong> {{ $invoice->due_date->format('d/m/Y') }}</p>
            
            @if($type === 'overdue')
                <p><strong style="color: red;">Días Vencida:</strong> {{ $invoice->days_overdue }} días</p>
            @endif
        </div>

        <p>Para realizar el pago, puedes:</p>
        <ul>
            <li>Transferir a la cuenta de administración</li>
            <li>Pagar en efectivo en la administración</li>
            <li>Contactar al administrador para coordinar el pago</li>
        </ul>

        <p>Si ya realizaste el pago, por favor ignora este mensaje.</p>

        <div class="footer">
            <p>Este es un mensaje automático. Por favor no responder este correo.</p>
            <p>Conjunto Residencial Gualanday - Administración</p>
        </div>
    </div>
</body>
</html>
*/