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
        Schema::table('work_assignments', function (Blueprint $table) {
            // Eliminar unique anterior
            $table->dropUnique('work_assignments_week_id_user_id_work_date_unique');

            // Agregar shift a las columnas únicas
            $table->unique(
                ['week_id', 'user_id', 'work_date', 'shift'],
                'assignments_unique_per_shift'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_assignments', function (Blueprint $table) {
            $table->dropUnique('assignments_unique_per_shift');

            // Volver a la restricción original
            $table->unique(
                ['week_id', 'user_id', 'work_date'],
                'work_assignments_week_id_user_id_work_date_unique'
            );
        });
    }
};
