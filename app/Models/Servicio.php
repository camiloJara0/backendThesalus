<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;
    protected $table = 'servicio';

    public function analises()
    {
        return $this->hasMany(Analisis::class, 'id_servicio');
    }

}
