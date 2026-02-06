<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insumo extends Model
{
    use HasFactory;

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'nombre',
        'categoria',
        'activo',
        'receta',
        'unidad',
        'stock',
        'lote',
        'vencimiento',
        'ubicacion',
        'estado',
    ];

}
