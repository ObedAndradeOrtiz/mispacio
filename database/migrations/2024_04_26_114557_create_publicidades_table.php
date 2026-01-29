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
        Schema::create('publicidades', function (Blueprint $table) {
            $table->id();
            $table->integer('idsucursal')->nullable();
            $table->string('sucursal')->nullable();
            $table->integer('idcuenta')->nullable();
            $table->string('nombrecuenta')->nullable();
            $table->integer('iduser')->nullable();
            $table->string('responsable')->nullable();
            $table->string('fechainicio')->nullable();
            $table->string('fechafin')->nullable();
            $table->string('motivo')->nullable();
            $table->string('estado')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publicidades');
    }
};