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
        Schema::create('operativos', function (Blueprint $table) {
            $table->id();
            $table->string('area')->nullable();;
            $table->string('idempresa')->nullable();;
            $table->string('empresa')->nullable();;
            $table->string('fecha')->nullable();;
            $table->string('hora')->nullable();;
            $table->string('telefono')->nullable();;
            $table->string('responsable')->nullable();;
            $table->string('cantidadtotal')->nullable();;
            $table->string('ingreso')->nullable();;
            $table->string('cantidadaregistrar')->nullable();;
            $table->string('encargado')->nullable();;
            $table->string('comentario')->nullable();;
            $table->string('estado')->nullable();;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operativos');
    }
};
