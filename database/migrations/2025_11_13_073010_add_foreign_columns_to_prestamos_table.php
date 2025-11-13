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
        Schema::table('prestamos', function (Blueprint $table) {
            if (! Schema::hasColumn('prestamos', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            }

            if (! Schema::hasColumn('prestamos', 'libro_id')) {
                $table->foreignId('libro_id')->nullable()->after('estado')->constrained('libros')->cascadeOnDelete();
            }

            if (Schema::hasColumn('prestamos', 'usuario')) {
                $table->dropColumn('usuario');
            }

            if (Schema::hasColumn('prestamos', 'libro')) {
                $table->dropColumn('libro');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prestamos', function (Blueprint $table) {
            if (Schema::hasColumn('prestamos', 'libro_id')) {
                $table->dropForeign(['libro_id']);
                $table->dropColumn('libro_id');
            }

            if (Schema::hasColumn('prestamos', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }

            if (! Schema::hasColumn('prestamos', 'usuario')) {
                $table->string('usuario')->nullable();
            }

            if (! Schema::hasColumn('prestamos', 'libro')) {
                $table->string('libro')->nullable();
            }
        });
    }
};
