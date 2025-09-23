<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estacionamento extends Model
{
    use HasFactory;

    protected $table = 'tb_estacionamento';
    protected $primaryKey = 'idEstacionamento';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'gridsEixoX',
        'gridsEixoY',
        'vagasCarro',
        'vagasMoto',
        'vagasPreferencial',
        'vagasPcd',
        'idProjeto',
    ];

    public function projeto()
    {
        return $this->belongsTo(Projeto::class, 'idProjeto', 'idProjeto');
    }

     public function setores()
    {
        return $this->hasMany(Setor::class, 'idEstacionamento', 'idEstacionamento');
    }

    public function vagas()
    {
        return $this->hasMany(Vaga::class, 'idEstacionamento', 'idEstacionamento');
    }
}
