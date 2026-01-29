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
        Schema::create('planillas', function (Blueprint $table) {
            $table->id();
            $table->string('fecha')->nullable();
            $table->string('semana')->nullable();
            $table->string('trabajadores')->nullable();
            $table->string('haberbasico')->nullable();
            $table->string('horasdias')->nullable();
            $table->string('horasextras')->nullable();
            $table->string('bonos')->nullable();
            $table->string('anticipo')->nullable();
            $table->string('pagado')->nullable();
            $table->string('responsable')->nullable();
            $table->string('estado')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planillas');
    }
};
