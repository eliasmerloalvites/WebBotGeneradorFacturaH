<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Marcacion extends Model
{
    use HasFactory;

    // Especificamos el nombre de la tabla, ya que la convención de Laravel sería "marcacions"
    protected $table = 'marcaciones';

    // Campos asignables
    protected $fillable = [
        'ESP_Id',       // Espacio de parqueo
        'VEH_Id',       // Vehículo
        'USU_Id',       // Usuario que realizó el registro
        'fecha_entrada',
        'fecha_salida',
        'monto_total',
        'estado'
    ];

    /**
     * Relación con el espacio de parqueo.
     * Se asume que existe un modelo llamado EspacioParqueo.
     */
    public function espacio()
    {
        return $this->belongsTo(EspacioParqueo::class, 'ESP_Id');
    }

    /**
     * Relación con el vehículo.
     * Se asume que existe un modelo llamado Vehiculo.
     */
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'VEH_Id');
    }

    /**
     * Relación con el usuario que realizó la marcación.
     * Se asume el uso del modelo User que viene por defecto en Laravel.
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'USU_Id');
    }
}
