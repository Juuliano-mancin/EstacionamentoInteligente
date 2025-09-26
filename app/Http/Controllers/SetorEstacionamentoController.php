<?php

namespace App\Http\Controllers; /* define o namespace do controller */

use Illuminate\Http\Request; /* importa a classe Request */
use App\Models\Estacionamento; /* importa o model Estacionamento */
use App\Models\Setor; /* importa o model Setor */
use App\Models\SetorGrid; /* importa o model SetorGrid */

class SetorEstacionamentoController extends Controller /* controller para gerenciar setores de estacionamentos, herda da classe base Controller */
    {
        public function index() /* método que lista todos os estacionamentos com setores */
            {
                $estacionamentos = Estacionamento::all(); /* busca todos os estacionamentos */
                return view('setoresestacionamento', compact('estacionamentos')); /* retorna a view 'setoresestacionamento' passando os estacionamentos */
            }

        public function show($id) /* método que retorna os detalhes de um estacionamento específico */
            {
                $estacionamento = Estacionamento::findOrFail($id); /* busca o estacionamento pelo ID ou retorna erro 404 se não encontrado */
                return response()->json($estacionamento); /* retorna os dados do estacionamento em formato JSON */
            }

        public function vagas() /* método que define o relacionamento com as vagas (parece um relacionamento de model) */
            {
                return $this->hasMany(Vaga::class, 'idSetor', 'idSetor'); /* retorna todas as vagas relacionadas ao setor */
            }
    }