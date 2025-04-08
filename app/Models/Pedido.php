<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pedido extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'cliente_id',
        'fecha',
        'estado',
        'total',
    ];

    // Relación: Pedido pertenece a un Cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class); // N pedidos => 1 cliente
    }

    // Relación: Pedido tiene muchos Productos (relación muchos a muchos)
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'table_pedido_producto')
                    ->withPivot('cantidad', 'precio_unitario', 'subtotal')
                    ->withTimestamps();
    }
}
