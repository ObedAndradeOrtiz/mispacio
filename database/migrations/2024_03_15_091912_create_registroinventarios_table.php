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
        Schema::create('registroinventarios', function (Blueprint $table) {
            $table->id();
            $table->integer('idsucursal')->nullable();
            $table->string('sucursal')->nullable();
            $table->integer('idproducto')->nullable();
            $table->string('nombreproducto')->nullable();
            $table->integer('cantidad')->nullable();
            $table->string('precio')->nullable();
            $table->integer('iduser')->nullable();
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
        Schema::dropIfExists('registroinventarios');
    }
};
