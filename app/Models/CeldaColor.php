<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CeldaColor extends Model
{
    use HasFactory;

    protected $table = 'celda_colors';
    protected $fillable = [
        'fila',
        'columna',
        'color',
        'tabla',
        'id_infoUsuario',
    ];
}
