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
        Schema::create('transacciones', function (Blueprint $table) {
            $table->id();

            $table->integer('idtarjetaprincipal')->nullable();
            $table->string('trajetaprincipal')->nullable();

            $table->integer('idtarjeta')->nullable();
            $table->string('trajeta')->nullable();

            $table->integer('idcuenta')->nullable();
            $table->string('nombrecuenta')->nullable();

            $table->string('monto')->nullable();

            $table->integer('iduser')->nullable();
            $table->string('responsable')->nullable();


            $table->string('fecha')->nullable();
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
        Schema::dropIfExists('transacciones');
    }
};
