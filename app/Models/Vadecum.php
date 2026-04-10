<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vadecum extends Model
{
    use HasFactory;

    protected $table = 'vadecums';
    protected $fillable = [
                'expediente',
                'producto',
                'titular',
                'registrosanitario',
                'fechaexpedicion',
                'fechavencimiento',
                'estadoregistro',
                'expedientecum',
                'consecutivocum',
                'cantidadcum',
                'descripcioncomercial',
                'estadocum',
                'fechaactivo',
                'fechainactivo',
                'muestramedica',
                'unidad',
                'atc',
                'descripcionatc',
                'viaadministracion',
                'concentracion',
                'principioactivo',
                'unidadmedida',
                'cantidad',
                'unidadreferencia',
                'formafarmaceutica',
                'nombrerol',
                'tiporol',
                'modalidad',
                'IUM',
    ];
}
