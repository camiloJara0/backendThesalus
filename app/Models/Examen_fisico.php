<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examen_fisico extends Model
{
    use HasFactory;
    protected $fillable = [
        'Peso',
        'altura',
        'otros',
        'id_analisis',
        'signosVitales'
    ];
    protected $casts = [
        'signosVitales' => 'array', // Esto convierte automáticamente el array PHP a JSON al guardar, y viceversa al recuperar.
    ];
    
    public function analisis(){
        return $this->belongsTo(Analisis::class, 'id_analisis');
    }
}
