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
        Schema::create('calls', function (Blueprint $table) {
            $table->id();
            $table->string('area')->nullable();;
            $table->string('empresa')->nullable();;
            $table->string('fecha')->nullable();;
            $table->string('ci')->nullable();;
            $table->string('fechacita')->nullable();
            $table->string('hora')->nullable();
            $table->string('cantidad')->nullable();;
            $table->string('telefono');
            $table->string('comentario')->nullable();;
            $table->string('encargado')->nullable();;
            $table->string('responsable')->nullable();;
            $table->string('estado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calls');
    }
};
