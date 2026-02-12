<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Terapia extends Model
{
    use HasFactory;
    protected $table = 'terapia';

    protected $fillable = [
        'id_procedimiento',
        'objetivos',
        'fecha',
        'hora',
        'sesion',
        'evolucion',
        'id_analisis'
    ];

    public function procedimiento(){
        return $this->belongsTo(Plan_manejo_procedimiento::class, 'id_procedimiento');
    }
}
