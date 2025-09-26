<?php

namespace App\Http\Controllers; /* define o namespace do controller */

use App\Models\Vagas; /* importa o model Vagas */
use Illuminate\Http\Request; /* importa a classe Request */
use Illuminate\Support\Facades\DB; /* importa a facade DB para transações */

class VagaController extends Controller /* controller responsável por gerenciar vagas de estacionamentos */
{
    public function index($idEstacionamento) /* lista todas as vagas de um estacionamento específico */
        {
            $vagas = Vagas::where('idEstacionamento', $idEstacionamento)->get(); /* busca todas as vagas do estacionamento pelo ID */
            return response()->json($vagas); /* retorna os dados das vagas em JSON */
        }

    public function store(Request $request) /* salva múltiplas vagas de uma vez */
        {
            $data = $request->validate([ /* valida os dados enviados pelo formulário */
                'idEstacionamento' => 'required|exists:tb_estacionamento,idEstacionamento', /* obrigatório, deve existir na tabela de estacionamentos */
                'vagas' => 'required|array', /* obrigatório, array de vagas */
                'vagas.*.posVagaX' => 'required|integer', /* obrigatório, coordenada X da vaga */
                'vagas.*.posVagaY' => 'required|integer', /* obrigatório, coordenada Y da vaga */
                'vagas.*.tipo' => 'nullable|in:carro,moto,preferencial,especial,pcd', /* opcional, tipo da vaga */
                'vagas.*.status' => 'boolean', /* opcional, status da vaga (true/false) */
                'vagas.*.idSetor' => 'nullable|exists:tb_setores,idSetor', /* opcional, referência ao setor */
            ]);

            $vagasCriadas = []; /* array para armazenar as vagas criadas */

            try {
                DB::transaction(function() use ($data, &$vagasCriadas) { /* inicia uma transação para garantir atomicidade */
                    foreach ($data['vagas'] as $vaga) { /* percorre cada vaga enviada */
                        $vagasCriadas[] = Vagas::create([ /* cria cada vaga no banco */
                            'idEstacionamento' => $data['idEstacionamento'], /* ID do estacionamento */
                            'idSetor' => $vaga['idSetor'] ?? null, /* ID do setor ou null */
                            'posVagaX' => $vaga['posVagaX'], /* coordenada X */
                            'posVagaY' => $vaga['posVagaY'], /* coordenada Y */
                            'tipo' => $vaga['tipo'] ?? null, /* tipo da vaga */
                            'status' => $vaga['status'] ?? false, /* status da vaga */
                        ]);
                    }
                });

                return response()->json([ /* retorna sucesso em JSON */
                    'message' => 'Vagas salvas com sucesso!',
                    'vagas' => $vagasCriadas
                ], 201);

            } catch (\Exception $e) { /* captura qualquer exceção */
                return response()->json([ /* retorna erro em JSON */
                    'message' => 'Erro ao salvar vagas: ' . $e->getMessage()
                ], 500);
            }
        }

    public function update(Request $request, $idVaga) /* atualiza status ou tipo de uma vaga específica */
        {
            $vaga = Vagas::findOrFail($idVaga); /* busca a vaga pelo ID ou retorna erro 404 */

            $data = $request->validate([ /* valida os dados enviados */
                'tipo' => 'nullable|in:carro,moto,preferencial,pcd', /* tipo da vaga */
                'status' => 'boolean', /* status da vaga */
                'idSetor' => 'nullable|exists:tb_setores,idSetor', /* ID do setor, opcional */
            ]);

            $vaga->update($data); /* atualiza os dados da vaga no banco */

            return response()->json([ /* retorna mensagem de sucesso e os dados da vaga atualizada */
                'message' => 'Vaga atualizada!',
                'vaga' => $vaga
            ]);
        }

    public function destroy($idVaga) /* exclui uma vaga específica */
        {
            $vaga = Vagas::findOrFail($idVaga); /* busca a vaga pelo ID ou retorna erro 404 */
            $vaga->delete(); /* remove a vaga do banco */

            return response()->json(['message' => 'Vaga removida com sucesso!']); /* retorna mensagem de sucesso em JSON */
        }
}