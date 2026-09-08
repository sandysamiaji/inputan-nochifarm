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
        if (!Schema::hasTable('health_treatments')) {
            Schema::create('health_treatments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('flock_id')->nullable()->constrained('flocks')->nullOnDelete();
                $table->foreignId('coop_id')->nullable()->constrained('coops')->nullOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->date('date');
                $table->time('time')->nullable();
                $table->string('type')->default('vaksin'); // vaksin / obat / vitamin / disinfektan
                $table->string('medicine_name'); // e.g. ND IB Vaccine, Vitamin B Complex
                $table->string('dosage')->nullable(); // e.g. 1 Botol, 500 ml
                $table->string('application_method')->nullable(); // air minum, tetes mata, suntik, semprot
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
        Schema::dropIfExists('health_treatments');
    }
};
