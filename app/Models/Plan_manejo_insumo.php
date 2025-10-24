<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan_manejo_insumo extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'cantidad',
        'id_analisis'
    ];

    public function analisis(){
        return $this->belongsTo(Analisis::class, 'id_analisis');
    }
}
