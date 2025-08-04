<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apartment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('invoice_type_id')->constrained();
            $table->string('number')->unique(); // INV-2025-001
            $table->string('period'); // 2025-01
            $table->date('issue_date');
            $table->date('due_date');
            $table->decimal('amount', 10, 2);
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'paid', 'overdue', 'cancelled'])->default('pending');
            $table->date('payment_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['apartment_id', 'status']);
            $table->index(['period', 'status']);
            $table->index('due_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
};