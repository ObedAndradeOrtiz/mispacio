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
        Schema::create('traspasostexts', function (Blueprint $table) {
            $table->id(); // Llave primaria
            $table->unsignedBigInteger('user_id'); // ID del usuario que realiza el traspaso
            $table->string('sucursal_origen', 255); // Sucursal de origen
            $table->string('sucursal_destino', 255); // Sucursal de destino
            $table->text('texto'); // Texto del traspaso
            $table->timestamp('fecha')->nullable; // Fecha y hora del traspaso
            $table->timestamps(); // Marcas de tiempo (created_at, updated_at)

            // Definir la relación con la tabla de usuarios
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trasapasostexts');
    }
};
