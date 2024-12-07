<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periodo extends Model
{
    // Especificar la tabla de la base de datos
    protected $table = 'periodos';

    // Definir los atributos que se pueden llenar masivamente
    protected $fillable = [
        'gestion_id',
        'nombre_periodo',
        'estado'
    ];

    // Relaciones
    public function gestion()
    {
        return $this->belongsTo(Gestion::class);
    }
}
