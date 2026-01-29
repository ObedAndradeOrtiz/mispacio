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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->string('area')->nullable();
            $table->integer('numero')->nullable();
            $table->string('empresa')->nullable();
            $table->integer('iduser')->nullable();
            $table->string('nameuser')->nullable();
            $table->string('namebeneficiario')->nullable();
            $table->string('fecha')->nullable();
            $table->string('fechainicio')->nullable();
            $table->string('fechapagado')->nullable();
            $table->string('modo')->nullable();
            $table->string('ci')->nullable();
            $table->double('cantidad',8,2)->nullable();
            $table->double('pagado')->nullable();
            $table->string('pertence')->nullable();
            $table->string('estado')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
