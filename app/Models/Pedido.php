<?php

namespace App\Models;

use Carbon\Carbon;
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

    public function getTiempoEstadoAttribute()
    {
        // $diffInMinutes = Carbon::parse($this->fecha)->diffInMinutes(now());
        $diffInSeconds = Carbon::parse($this->fecha)->diffInSeconds(now());
        if ($diffInSeconds < 10) {
            return 'bg-green-50 border-green-400';
        } elseif ($diffInSeconds < 20) {
            return 'bg-yellow-50 border-yellow-400';
        } else {
            return 'bg-red-50 border-red-400';
        }

        // if ($diffInMinutes < 1) {
        //     return 'bg-green-50 border-green-400';
        // } elseif ($diffInMinutes < 2) {
        //     return 'bg-yellow-50 border-yellow-400';
        // } else {
        //     return 'bg-red-50 border-red-400';
        // }
    }

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
