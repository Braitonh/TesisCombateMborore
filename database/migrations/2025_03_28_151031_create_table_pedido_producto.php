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
        Schema::create('table_pedido_producto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained()->onDelete('cascade');
            $table->foreignId('producto_id')->constrained()->onDelete('cascade');
            $table->integer('cantidad')->default(1);                       // Cantidad de unidades de ese producto en ese pedido
            $table->decimal('precio_unitario', 10, 2);                     // Precio unitario en ese momento (por si cambia el precio después)
            $table->decimal('subtotal', 10, 2);                            // Cantidad * precio_unitario (se guarda directamente)
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_pedido_producto');
    }
};
