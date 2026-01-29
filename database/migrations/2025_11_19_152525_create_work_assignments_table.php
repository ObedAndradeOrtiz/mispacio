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
        Schema::create('work_assignments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('week_id')
                ->constrained('weeks')
                ->onDelete('cascade');

            $table->foreignId('area_id')
                ->constrained('areas')    // tu tabla de sucursales
                ->onDelete('cascade');

            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->date('work_date');      // día exacto (lunes -> domingo)

            $table->timestamps();

            // Un usuario solo puede trabajar 1 día por semana en un día específico
            $table->unique(['week_id', 'user_id', 'work_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_assignments');
    }
};
