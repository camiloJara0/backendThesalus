<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodigoVerificacion extends Model
{
    use HasFactory;
    protected $table = 'codigo_verificaciones';
    protected $fillable = ['correo', 'codigo', 'expira_en', 'usado'];

}
