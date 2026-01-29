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
        Schema::create('mensajes_wsses', function (Blueprint $table) {
            $table->id();
            $table->integer('idauser')->nullable();
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
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mensajes_wsses');
    }
};