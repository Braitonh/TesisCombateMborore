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
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn('stock'); // Elimina el campo stock
            $table->string('categoria'); // Agrega el campo categoria (tipo string)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn('categoria'); // Elimina categoria
            $table->integer('stock'); // Vuelve a agregar stock (supongo que era un entero)
        });
    }
};
