<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Convenio extends Model
{
    use HasFactory;

    protected $table = 'convenios';

    protected $fillable = [
        'logo',
        'nombre',
        'estado'
    ];

    public function pacientes()
    {
        return $this->belongsToMany(Paciente::class, 'paciente_has_convenios', 'id_convenio', 'id_paciente');
    }
}
