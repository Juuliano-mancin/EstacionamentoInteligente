<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setor extends Model
{
    use HasFactory;

    protected $table = 'tb_setores';
    protected $primaryKey = 'idSetor';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'idEstacionamento',
        'nomeSetor',
        'corSetor',
    ];

    // Relacionamento com grids
    public function grids()
    {
        return $this->hasMany(SetorGrid::class, 'idSetor', 'idSetor');
    }

    // Relacionamento com estacionamento
    public function estacionamento()
    {
        return $this->belongsTo(Estacionamento::class, 'idEstacionamento', 'idEstacionamento');
    }
    
}