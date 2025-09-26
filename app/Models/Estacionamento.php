<?php

namespace App\Models; /* define o namespace do model */

use Illuminate\Database\Eloquent\Factories\HasFactory; /* habilita o uso de factories para testes */
use Illuminate\Database\Eloquent\Model; /* classe base de models do Eloquent */

class Estacionamento extends Model /* model que representa a tabela de estacionamentos */
    {
        use HasFactory; /* inclui a trait HasFactory para facilitar criação de registros de teste */
        protected $table = 'tb_estacionamento'; /* define explicitamente o nome da tabela no banco */
        protected $primaryKey = 'idEstacionamento'; /* define a chave primária da tabela */
        public $incrementing = true; /* indica que a chave primária é auto-incrementável */
        protected $keyType = 'int'; /* define o tipo da chave primária como inteiro */

        protected $fillable = [ /* campos que podem ser preenchidos via mass-assignment */
            'gridsEixoX', /* número de grids no eixo X */
            'gridsEixoY', /* número de grids no eixo Y */
            'vagasCarro', /* quantidade de vagas para carro */
            'vagasMoto', /* quantidade de vagas para moto */
            'vagasPreferencial', /* quantidade de vagas preferenciais */
            'vagasPcd', /* quantidade de vagas para PCD */
            'idProjeto', /* id do projeto associado */
        ];

        public function projeto() /* relacionamento com o model Projeto */
            {
                return $this->belongsTo(Projeto::class, 'idProjeto', 'idProjeto'); /* cada estacionamento pertence a um projeto */
            }

        public function setores() /* relacionamento com os setores do estacionamento */
            {
                return $this->hasMany(Setor::class, 'idEstacionamento', 'idEstacionamento'); /* um estacionamento possui vários setores */
            }

        public function vagas() /* relacionamento com as vagas do estacionamento */
            {
                return $this->hasMany(Vaga::class, 'idEstacionamento', 'idEstacionamento'); /* um estacionamento possui várias vagas */
            }
    }