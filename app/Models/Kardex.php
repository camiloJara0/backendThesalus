<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kardex extends Model
{
    use HasFactory;
    protected $table = 'kardex';
    protected $fillable = [
        'id_paciente',
        'responsable',
        'kit_cateterismo',
        'rango',
        'kit_cambioSonda',
        'kit_gastro',
        'traqueo',
        'equipos_biomedicos',
        'oxigeno',
        'estado',
        'vm',
        'ultimoCambio'
    ];
}