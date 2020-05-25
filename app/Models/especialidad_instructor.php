<?php

namespace App\Models;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class especialidad_instructor extends Model
{
    //
    protected $table = 'especialidad_instructores';

    protected $fillable = [
        'id','especialidad_id','perfilprof_id','pago_id','zona','validado_impartir','unidad_solicita','memorandum_valdidacion',
        'fecha_validacion','memorandum_modificacion','observacion',
    ];

    protected $hidden = ['created_at', 'updated_at'];

}