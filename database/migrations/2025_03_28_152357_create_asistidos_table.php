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
        Schema::create('asistidos', function (Blueprint $table) {
            $table->id();
            $table->string('idcliente')->nullable();
            $table->string('idoperativo')->nullable();
            $table->string('hora')->nullable();
            $table->string('extra')->nullable();
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
        Schema::dropIfExists('asistidos');
    }
};