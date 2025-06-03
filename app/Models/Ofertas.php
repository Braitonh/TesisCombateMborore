<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ofertas extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 'descripcion', 'precio', 'activo', 'descuento'
    ];

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'oferta_producto', 'oferta_id', 'producto_id')
            ->withPivot('cantidad')
            ->withTimestamps();
    }

    public function pedidos()
    {
        return $this->belongsToMany(\App\Models\Pedido::class, 'oferta_pedido', 'oferta_id', 'pedido_id')
            ->withPivot('cantidad', 'precio_oferta')
            ->withTimestamps();
    }
}
