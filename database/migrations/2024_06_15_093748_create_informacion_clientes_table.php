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
        Schema::create('informacion_clientes', function (Blueprint $table) {
            $table->id();
            $table->string('sangre')->nullable();
            $table->string('menstraucion')->nullable();
            $table->string('protector')->nullable();
            $table->integer('alergia')->nullable();
            $table->string('anterior')->nullable();
            $table->string('piel')->nullable();
            $table->string('gradoacne')->nullable();
            $table->string('fecha')->nullable();
            $table->string('estado')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('informacion_clientes');
    }
};