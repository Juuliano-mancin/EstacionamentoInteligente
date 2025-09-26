<?php

namespace App\Models; /* define o namespace do model */

use Illuminate\Database\Eloquent\Factories\HasFactory; /* habilita o uso de factories para testes */
use Illuminate\Database\Eloquent\Model; /* classe base de models do Eloquent */

class Setor extends Model /* model que representa a tabela de setores */
{
    use HasFactory; /* inclui a trait HasFactory para facilitar criação de registros de teste */

    protected $table = 'tb_setores'; /* define explicitamente o nome da tabela no banco */
    protected $primaryKey = 'idSetor'; /* define a chave primária da tabela */
    public $incrementing = true; /* indica que a chave primária é auto-incrementável */
    protected $keyType = 'int'; /* define o tipo da chave primária como inteiro */

    protected $fillable = [ /* campos que podem ser preenchidos via mass-assignment */
        'idEstacionamento', /* ID do estacionamento ao qual o setor pertence */
        'nomeSetor', /* nome do setor */
        'corSetor', /* cor do setor (opcional) */
    ];

    public function grids() /* relacionamento com os grids do setor */
    {
        return $this->hasMany(SetorGrid::class, 'idSetor', 'idSetor'); /* um setor possui vários grids */
    }

    public function estacionamento() /* relacionamento com o estacionamento ao qual pertence */
    {
        return $this->belongsTo(Estacionamento::class, 'idEstacionamento', 'idEstacionamento'); /* cada setor pertence a um estacionamento */
    }
}
