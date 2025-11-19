<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioCochera extends Model
{
    use HasFactory;

    protected $table = 'usuario_cocheras';

    protected $fillable = [
        'COC_Id',
        'USU_Id',
    ];

    public function cochera()
    {
        return $this->belongsTo(Cochera::class, 'COC_Id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'USU_Id');
    }
}
