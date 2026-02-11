<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;
    public function paciente(){
        return $this->belongsTo(Paciente::class, 'id_paciente');
    }
    public function profesional(){
        return $this->belongsTo(Profesional::class, 'id_profesional');
    }
    public function analisis(){
        return $this->belongsTo(Analisis::class, 'id_analisis');
    }
}
