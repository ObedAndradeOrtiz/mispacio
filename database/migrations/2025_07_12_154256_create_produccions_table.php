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
        Schema::create('produccions', function (Blueprint $table) {
            $table->id();
            $table->string('nombreproducto')->nullable();
            $table->integer('idproducto')->nullable();
            $table->string('nombrelote')->nullable();
            $table->integer('idlote')->nullable();
            $table->string('descripcion')->nullable();
            $table->string('fechacreacion')->nullable();
            $table->string('fechavencimiento')->nullable();
            $table->integer('cantidad')->nullable();
            $table->string('estado')->nullable();
            $table->string('responsable')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produccions');
    }
};
