<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory, HasApiTokens;
    public function empresa(){
        return $this->belongsTo(Empresa::class, 'id_empresa');
    }
    public function infoUsuario(){
        return $this->belongsTo(informacionUser::class, 'id_infoUsuario');
    }
}
