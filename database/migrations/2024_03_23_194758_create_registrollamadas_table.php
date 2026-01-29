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
        Schema::create('registrollamadas', function (Blueprint $table) {
            $table->id();
            $table->integer('idllamada')->nullable();
            $table->integer('telefono')->nullable();
            $table->integer('iduser')->nullable();
            $table->string('responsable')->nullable();
            $table->string('fecha')->nullable();
            $table->string('estado')->nullable();
            $table->string('sucursal')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrollamadas');
    }
};
