<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profesion extends Model
{
    use HasFactory;
    public function permisos()
    {
        return $this->belongsToMany(Secciones::class, 'profesions_has_permisos', 'id_profesion', 'id_seccion');
    }

}
