<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'direccion',
    ];

    // Relación: Cliente tiene muchos Pedidos
    public function pedidos()
    {
        return $this->hasMany(Pedido::class); // 1 cliente => N pedidos
    }

    // Relación: Cliente tiene una autenticación
    public function auth()
    {
        return $this->hasOne(ClienteAuth::class);
    }

}
