<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Descripcion_nota extends Model
{
    use HasFactory;
    protected $table = 'descripcion_nota';

    protected $fillable = [
        'id_nota',
        'descripcion',
        'hora',
        'tipo',
    ];

    public function nota(){
        return $this->belongsTo(Nota::class, 'id_nota');
    }
}
