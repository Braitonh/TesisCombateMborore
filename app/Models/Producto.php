<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'activo',
        'imagen',
        'categoria',
        'posicion'
    ];

    // Relación: Un producto puede estar en muchos pedidos
    public function pedidos()
    {
        return $this->belongsToMany(Pedido::class, 'pedido_producto')
                    ->withPivot('cantidad', 'precio_unitario', 'subtotal') // Campos extras de la tabla pivote
                    ->withTimestamps(); // Maneja automáticamente created_at y updated_at de la pivote
    }

    public function ofertas()
    {
        return $this->belongsToMany(Ofertas::class, 'oferta_producto', 'producto_id', 'oferta_id')
            ->withPivot('cantidad')
            ->withTimestamps();
    }
}
