<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analisis extends Model
{
    use HasFactory;
    protected $fillable = [
        'motivo',
        'observacion',
        'tratamiento',
        'analisis',
        'tipoAnalisis',
        'id_historia',
        'id_medico',
        'servicio',
    ];
    public function historia(){
        return $this->belongsTo(Historia_Clinica::class, 'id_historia');
    }
    
    public function diagnosticos()
    {
        return $this->hasMany(Diagnostico::class, 'id_analisis');
    }

    public function enfermedad()
    {
        return $this->hasOne(Enfermedad::class, 'id_analisis');
    }

    public function examenFisico()
    {
        return $this->hasOne(Examen_fisico::class, 'id_analisis');
    }

    public function medicamentos()
    {
        return $this->hasMany(Plan_manejo_medicamento::class, 'id_analisis');
    }

    public function procedimientos()
    {
        return $this->hasMany(Plan_manejo_procedimiento::class, 'id_analisis');
    }

    public function insumos()
    {
        return $this->hasMany(Plan_manejo_insumo::class, 'id_analisis');
    }

    public function equipos()
    {
        return $this->hasMany(Plan_manejo_equipo::class, 'id_analisis');
    }

}
