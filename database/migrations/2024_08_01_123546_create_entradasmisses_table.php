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
        Schema::create('entradasmisses', function (Blueprint $table) {
            $table->id();
            $table->integer('idcliente')->nullable();
            $table->string('nombre')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('idcompra')->nullable();
            $table->string('numeroqr')->nullable();
            $table->integer('cantidad')->nullable();
            $table->string('monto')->nullable();
            $table->string('fecha')->nullable();
            $table->string('modo')->nullable();
            $table->string('estado')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entradasmisses');
    }
};
