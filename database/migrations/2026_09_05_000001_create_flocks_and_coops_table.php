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
        if (!Schema::hasTable('flocks')) {
            Schema::create('flocks', function (Blueprint $table) {
                $table->id();
                $table->string('name'); // e.g. Klotter 1
                $table->string('code')->nullable(); // e.g. K1
                $table->date('start_date')->nullable();
                $table->integer('initial_population')->default(0);
                $table->integer('current_population')->default(0);
                $table->string('breed')->nullable(); // Lohmann Brown, Hy-Line, etc.
                $table->text('notes')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('coops')) {
            Schema::create('coops', function (Blueprint $table) {
                $table->id();
                $table->foreignId('flock_id')->nullable()->constrained('flocks')->nullOnDelete();
                $table->string('name'); // e.g. Blok A
                $table->string('code')->nullable(); // e.g. A
                $table->integer('capacity')->default(0); // Kapasitas aktif e.g. 800
                $table->integer('active_chickens')->default(0); // e.g. 762
                $table->integer('chicken_age_weeks')->default(0); // e.g. 21 Minggu
                $table->text('notes')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coops');
        Schema::dropIfExists('flocks');
    }
};
