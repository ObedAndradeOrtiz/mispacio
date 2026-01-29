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
        Schema::create('registropagos', function (Blueprint $table) {
            $table->id();
            $table->integer('idsucursal')->nullable();
            $table->string('sucursal')->nullable();
            $table->integer('idoperativo')->nullable();
            $table->string('nombrecliente')->nullable();
            $table->integer('cantidad')->nullable();
            $table->string('monto')->nullable();
            $table->integer('iduser')->nullable();
            $table->string('responsable')->nullable();
            $table->integer('idcliente')->nullable();
            $table->string('fecha')->nullable();
            $table->string('modo')->nullable();
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
        Schema::dropIfExists('registropagos');
    }
};
