<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente_has_convenio extends Model
{
    use HasFactory;
    protected $table = 'paciente_has_convenios';

    protected $fillable = [
        'id_paciente',
        'id_convenio',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'id_paciente');
    }
    public function convenio()
    {
        return $this->belongsTo(Convenio::class, 'id_convenio');
    }
}
