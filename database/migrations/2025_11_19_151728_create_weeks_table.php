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
        Schema::create('weeks', function (Blueprint $table) {
            $table->id();

            $table->date('start_date');   // lunes
            $table->date('end_date');     // domingo

            $table->string('name')->nullable(); // Ej: “Semana 47 - 2025”

            $table->timestamps();

            $table->unique(['start_date', 'end_date']); // evita duplicados
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weeks');
    }
};
