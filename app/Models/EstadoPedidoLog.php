<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoPedidoLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'pedido_id',
        'estado_anterior',
        'estado_nuevo',
        'cambiado_en',
    ];
}
