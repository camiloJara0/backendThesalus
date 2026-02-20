<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan_manejo_medicamento extends Model
{
    use HasFactory;
    protected $fillable = [
        'medicamento',
        'cantidad',
        'dosis',
        'id_analisis',
        'id_paciente',
        'id_medico'
    ];

    public function analisis(){
        return $this->belongsTo(Analisis::class, 'id_analisis');
    }
}
