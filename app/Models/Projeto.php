<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projeto extends Model
{
    use HasFactory;

    // Tabela personalizada
    protected $table = 'tb_projeto';

    // Chave primária personalizada
    protected $primaryKey = 'id_projeto';

    // Campos preenchíveis (mass assignment)
    protected $fillable = [
        'nomeprojeto',
        'nomeFantasia',
        'razaoSocial',
        'contatoNome',
        'contatoCelular',
        'contatoEmail',
        'enderecoLogradouro',
        'enderecoNumero',
        'enderecoComplemento',
        'enderecoBairro',
        'enderecoCidade',
        'enderecoEstado',
        'enderecoCEP',
    ];
}