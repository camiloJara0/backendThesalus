<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Antecedente extends Model
{
    use HasFactory;
    protected $fillable = [
        'tipo',
        'descripcion',
        'id_paciente'
    ];

    public function paciente(){
        return $this->belongsTo(Paciente::class, 'id_paciente');
    }
}
