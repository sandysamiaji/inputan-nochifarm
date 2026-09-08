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
        if (!Schema::hasTable('feed_consumptions')) {
            Schema::create('feed_consumptions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('flock_id')->nullable()->constrained('flocks')->nullOnDelete();
                $table->foreignId('coop_id')->nullable()->constrained('coops')->nullOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->date('date');
                $table->time('time')->nullable();
                $table->string('feeding_time')->nullable(); // Pagi / Sore
                $table->string('feed_name')->default('Pakan Layer'); // Nama pakan
                $table->decimal('quantity_kg', 10, 2)->default(0); // Kg pemakaian
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
        Schema::dropIfExists('feed_consumptions');
    }
};
