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
        Schema::create('mensajes', function (Blueprint $table) {
            $table->id();
            $table->integer('idarea')->nullable();
            $table->integer('idempresa')->nullable();
            $table->string('mensaje')->nullable();
            $table->integer('receptor')->nullable();
            $table->integer('emisor')->nullable();
            $table->string('hora')->nullable();
            $table->string('fecha')->nullable();
            $table->string('estado')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.php artisan migrate:refresh --path=/database/migrations/2023_05_22_022129_create_mensajes_table.php
     */
    public function down(): void
    {
        Schema::dropIfExists('mensajes');
    }
};
