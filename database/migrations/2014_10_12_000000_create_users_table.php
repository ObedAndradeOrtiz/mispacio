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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('rol')->nullable();;
            $table->string('telefono')->nullable();
            $table->string('estado')->nullable();
            $table->string('empresa')->nullable();
            $table->string('sueldo')->nullable();
            $table->string('bono')->nullable();
            $table->string('cuentaahorro')->nullable();
            $table->string('tesoreia')->nullable();
            $table->string('cargo')->nullable();
            $table->string('firma')->nullable();
            $table->double('porcentaje')->nullable();
            $table->string('password');
            $table->string('ci')->nullable()->unique();
            $table->string('sucursal')->nullable();
            $table->string('banco')->nullable();
            $table->string('edad')->nullable();
            $table->string('sesionsucursal')->nullable();
            $table->string('sexo')->nullable();
            $table->rememberToken()->nullable();;
            $table->timestamp('email_verified_at')->nullable();
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
