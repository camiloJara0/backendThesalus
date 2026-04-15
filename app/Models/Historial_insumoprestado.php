<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historial_insumoprestado extends Model
{
    use HasFactory;
    protected $table = 'historial_insumoprestados';
    protected $fillable = [
        'id_insumo',
        'id_movimiento',
        'fecha_desde',
        'fecha_hasta',
        'observacion',
        'estado',
    ];
}
