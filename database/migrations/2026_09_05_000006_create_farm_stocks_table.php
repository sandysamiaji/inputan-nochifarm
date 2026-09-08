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
        if (!Schema::hasTable('farm_stocks')) {
            Schema::create('farm_stocks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->date('date');
                $table->string('category'); // pakan / obat / vitamin
                $table->string('item_name'); // e.g. Pakan Layer, ND IB Vaccine
                $table->string('type'); // masuk / keluar
                $table->decimal('quantity', 12, 2)->default(0);
                $table->string('unit')->default('Kg'); // Kg / Botol / Peti / Item
                $table->string('source')->nullable(); // Pembelian, Penyesuaian, dll
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farm_stocks');
    }
};
