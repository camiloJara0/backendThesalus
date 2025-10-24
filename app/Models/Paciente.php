<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;
    public function infoUsuario(){
        return $this->belongsTo(InformacionUser::class, 'id_infoUsuario');
    }
    public function eps(){
        return $this->belongsTo(Eps::class, 'id_eps');
    }
    public function antecedente(){
        return $this->hasMany(Antecedente::class, 'id_paciente');
    }
}
