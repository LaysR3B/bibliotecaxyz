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
        Schema::table('users', function (Blueprint $table) {
            $table->string('dni', 20)->nullable()->after('name');
            $table->enum('rol', ['Administrador', 'Trabajador', 'Cliente'])->default('Cliente')->after('email');
            $table->enum('estado', ['Activo', 'Inactivo'])->default('Activo')->after('rol');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['dni', 'rol', 'estado']);
        });
    }
};
