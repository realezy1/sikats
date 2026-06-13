<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->foreignId('table_id')->nullable()->constrained('tables')->nullOnDelete();
            $table->string('customer_name', 100)->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('customer_session_id')->nullable()->constrained('customer_sessions')->nullOnDelete();
            $table->enum('source', ['staff', 'customer'])->default('staff');
            $table->enum('status', ['unpaid', 'paid'])->default('unpaid');
            $table->enum('payment_method', ['cash', 'midtrans'])->nullable();
            $table->timestamp('payment_time')->nullable();
            $table->string('midtrans_order_id', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
