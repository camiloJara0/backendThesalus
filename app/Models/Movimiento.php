<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    use HasFactory;

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'cantidadMovimiento',
        'tipoMovimiento',
        'fechaMovimiento',
        'id_medico',
        'id_insumo',
    ];

}
