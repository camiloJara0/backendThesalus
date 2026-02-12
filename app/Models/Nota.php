<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    use HasFactory;
    public function procedimiento(){
        return $this->belongsTo(Plan_manejo_procedimiento::class, 'id_procedimiento');
    }
}
