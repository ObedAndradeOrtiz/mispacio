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
        Schema::create('mensajes_facebooks', function (Blueprint $table) {
            $table->id();
            $table->string('abreviatura')->nullable();
            $table->string('tratamiento')->nullable();
            $table->string('colaboracion')->nullable();
            $table->string('estado')->nullable();
            $table->string('nivel')->nullable();
            $table->integer('mensajes')->nullable();
            $table->string('resultado')->nullable();
            $table->string('costo')->nullable();
            $table->string('importe')->nullable();
            $table->string('fecha')->nullable();
            $table->string('idcuenta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mensajes_facebooks');
    }
};
