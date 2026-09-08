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
        if (!Schema::hasTable('egg_productions')) {
            Schema::create('egg_productions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('flock_id')->nullable()->constrained('flocks')->nullOnDelete();
                $table->foreignId('coop_id')->nullable()->constrained('coops')->nullOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->date('date');
                $table->time('time')->nullable();
                $table->integer('total_eggs')->default(0); // Butir telur masuk
                $table->integer('broken_eggs')->default(0); // Butir retak / pecah
                $table->integer('good_eggs')->default(0); // Butir baik (estimasi)
                $table->decimal('crates_count', 10, 2)->default(0); // Peti (misal 2.460 atau kalkulasi)
                $table->decimal('weight_kg', 10, 2)->nullable();
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
        Schema::dropIfExists('egg_productions');
    }
};
