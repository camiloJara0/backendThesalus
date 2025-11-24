<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan_manejo_procedimiento extends Model
{
    use HasFactory;
    protected $fillable = [
        'procedimiento',
        'codigo',
        'id_paciente',
        'id_medico',
        'dias_asignados',
    ];


}
