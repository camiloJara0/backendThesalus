<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historial_cambio_sonda extends Model
{
    use HasFactory;
    protected $table = 'historial_cambio_sonda';
    protected $fillable = [
        'id_kardex',
        'fecha_cambio',
        'tipo_sonda',
        'observacion'
    ];
}