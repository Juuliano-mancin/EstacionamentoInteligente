<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vagas extends Model
{
    use HasFactory;

    protected $table = 'tb_vagas';
    protected $primaryKey = 'idVaga';

    protected $fillable = [
        'idEstacionamento',
        'idSetor',
        'posVagaX',
        'posVagaY',
        'tipo',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    // Relacionamentos
    public function estacionamento()
    {
        return $this->belongsTo(Estacionamento::class, 'idEstacionamento', 'idEstacionamento');
    }

    public function setor()
    {
        return $this->belongsTo(Setor::class, 'idSetor', 'idSetor');
    }
}
