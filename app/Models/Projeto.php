<?php

namespace App\Models; /* define o namespace do model */

use Illuminate\Database\Eloquent\Factories\HasFactory; /* habilita o uso de factories para testes */
use Illuminate\Database\Eloquent\Model; /* classe base de models do Eloquent */

class Projeto extends Model /* model que representa a tabela de projetos */
{
    use HasFactory; /* inclui a trait HasFactory para facilitar criação de registros de teste */

    protected $table = 'tb_projeto'; /* define explicitamente o nome da tabela no banco */

    protected $primaryKey = 'idProjeto'; /* define a chave primária da tabela */

    protected $fillable = [ /* campos que podem ser preenchidos via mass-assignment */
        'nomeProjeto', /* nome do projeto */
        'nomeFantasia', /* nome fantasia da empresa/projeto */
        'razaoSocial', /* razão social da empresa/projeto */
        'contatoNome', /* nome do contato principal */
        'contatoCelular', /* celular do contato */
        'contatoEmail', /* e-mail do contato */
        'enderecoLogradouro', /* endereço: logradouro */
        'enderecoNumero', /* endereço: número */
        'enderecoComplemento', /* endereço: complemento */
        'enderecoBairro', /* endereço: bairro */
        'enderecoCidade', /* endereço: cidade */
        'enderecoEstado', /* endereço: estado (UF) */
        'enderecoCEP', /* endereço: CEP */
    ];
}