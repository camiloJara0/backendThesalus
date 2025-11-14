<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profesional extends Model
{
    use HasFactory;
    public function infoUsuario(){
        return $this->belongsTo(InformacionUser::class, 'id_infoUsuario');
    }
    public function profesion(){
        return $this->belongsTo(Profesion::class, 'id_profesion');
    }
}
