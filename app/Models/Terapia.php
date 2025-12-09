<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Terapia extends Model
{
    use HasFactory;
    protected $table = 'terapia';

    protected $fillable = [
        'id_paciente',
        'id_procedimiento',
        'id_profesional',
        'objetivos',
        'fecha',
        'hora',
        'sesion',
        'evolucion',
        'id_analisis'
    ];

    public function paciente(){
        return $this->belongsTo(Paciente::class, 'id_paciente');
    }
    public function profesional(){
        return $this->belongsTo(Profesional::class, 'id_profesional');
    }
    public function procedimiento(){
        return $this->belongsTo(Plan_manejo_procedimiento::class, 'id_procedimiento');
    }
}
