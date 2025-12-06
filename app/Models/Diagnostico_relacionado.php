<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnostico_relacionado extends Model
{
    use HasFactory;
    protected $fillable = [
        'descripcion',
        'codigo',
        'id_analisis'
    ];
}
