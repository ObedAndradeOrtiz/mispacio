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
        Schema::create('planilla_sueldos', function (Blueprint $table) {
            $table->id();
            $table->integer('idplanilla')->nullable();
            $table->integer('idusuario')->nullable();
            $table->string('nombre')->nullable();
            $table->string('fecha')->nullable();
            $table->string('descripcion')->nullable();
            $table->string('idcargo')->nullable();
            $table->integer('idsucursal')->nullable();
            $table->string('haberbasico')->nullable();
            $table->string('sueldohora')->nullable();
            $table->string('horasdias')->nullable();
            $table->string('diastrabajados')->nullable();
            $table->string('horasextras')->nullable();
            $table->string('bonos')->nullable();
            $table->string('anticipo')->nullable();
            $table->string('pagado')->nullable();
            $table->string('responsable')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planilla_sueldos');
    }
};
