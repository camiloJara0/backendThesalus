<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historia_Clinica extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_paciente',
        'fecha_historia',
    ];
  
    public function paciente(){
        return $this->belongsTo(Paciente::class, 'id_paciente');
    }

    public function analisis()
    {
        return $this->hasMany(Analisis::class, 'id_historia');
    }

}
