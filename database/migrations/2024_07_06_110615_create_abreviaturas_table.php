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
        Schema::create('abreviaturas', function (Blueprint $table) {
            $table->id();
            $table->integer('idsucursal')->nullable();
            $table->string('sucursal')->nullable();
            $table->string('codigo')->nullable();
            $table->integer('iduser')->nullable();
            $table->string('user')->nullable();
            $table->string('abreviatura')->nullable();
            $table->string('estado')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abreviaturas');
    }
};
