<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enfermedad extends Model
{
    use HasFactory;
    protected $fillable = [
        'valor',
        'fecha_diagnostico',
        'fecha_rehabilitacion',
        'id_analisis',
        'id_paciente'
    ];

    public function paciente(){
        return $this->belongsTo(Paciente::class, 'id_paciente');
    }
}
