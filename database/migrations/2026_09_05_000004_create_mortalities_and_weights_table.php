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
        if (!Schema::hasTable('mortalities')) {
            Schema::create('mortalities', function (Blueprint $table) {
                $table->id();
                $table->foreignId('flock_id')->nullable()->constrained('flocks')->nullOnDelete();
                $table->foreignId('coop_id')->nullable()->constrained('coops')->nullOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->date('date');
                $table->time('time')->nullable();
                $table->integer('count')->default(1); // Jumlah ekor mati/afkir
                $table->string('type')->default('mati'); // mati / afkir / sakit
                $table->string('cause')->nullable(); // Penyebab: stres panas, prolapse, kanibalisme, wajar, dll
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('weight_samples')) {
            Schema::create('weight_samples', function (Blueprint $table) {
                $table->id();
                $table->foreignId('flock_id')->nullable()->constrained('flocks')->nullOnDelete();
                $table->foreignId('coop_id')->nullable()->constrained('coops')->nullOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->date('date');
                $table->integer('sample_count')->default(50); // Jumlah ekor sampel
                $table->decimal('average_weight_kg', 6, 3)->default(1.620); // Rata-rata kg
                $table->decimal('uniformity_percentage', 5, 2)->nullable(); // Keseragaman %
                $table->integer('age_weeks')->nullable(); // Umur minggu
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
        Schema::dropIfExists('weight_samples');
        Schema::dropIfExists('mortalities');
    }
};
