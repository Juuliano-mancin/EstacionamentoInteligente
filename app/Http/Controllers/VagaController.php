<?php

namespace App\Http\Controllers;

use App\Models\Vagas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VagaController extends Controller
{
    // Lista todas as vagas de um estacionamento
    public function index($idEstacionamento)
    {
        $vagas = Vagas::where('idEstacionamento', $idEstacionamento)->get();
        return response()->json($vagas);
    }

    // Salvar múltiplas vagas de uma vez
    public function store(Request $request)
    {
        $data = $request->validate([
            'idEstacionamento' => 'required|exists:tb_estacionamento,idEstacionamento',
            'vagas' => 'required|array',
            'vagas.*.posVagaX' => 'required|integer',
            'vagas.*.posVagaY' => 'required|integer',
            'vagas.*.tipo' => 'nullable|in:carro,moto,preferencial,especial,pcd',
            'vagas.*.status' => 'boolean',
            'vagas.*.idSetor' => 'nullable|exists:tb_setores,idSetor',
        ]);

        $vagasCriadas = [];

        try {
            DB::transaction(function() use ($data, &$vagasCriadas) {
                foreach ($data['vagas'] as $vaga) {
                    $vagasCriadas[] = Vagas::create([
                        'idEstacionamento' => $data['idEstacionamento'],
                        'idSetor' => $vaga['idSetor'] ?? null,
                        'posVagaX' => $vaga['posVagaX'],
                        'posVagaY' => $vaga['posVagaY'],
                        'tipo' => $vaga['tipo'] ?? null,
                        'status' => $vaga['status'] ?? false,
                    ]);
                }
            });

            return response()->json([
                'message' => 'Vagas salvas com sucesso!',
                'vagas' => $vagasCriadas
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao salvar vagas: ' . $e->getMessage()
            ], 500);
        }
    }

    // Atualizar status ou tipo de vaga
    public function update(Request $request, $idVaga)
    {
        $vaga = Vagas::findOrFail($idVaga);

        $data = $request->validate([
            'tipo' => 'nullable|in:carro,moto,preferencial,pcd',
            'status' => 'boolean',
            'idSetor' => 'nullable|exists:tb_setores,idSetor',
        ]);

        $vaga->update($data);

        return response()->json([
            'message' => 'Vaga atualizada!',
            'vaga' => $vaga
        ]);
    }

    // Deletar vaga
    public function destroy($idVaga)
    {
        $vaga = Vagas::findOrFail($idVaga);
        $vaga->delete();

        return response()->json(['message' => 'Vaga removida com sucesso!']);
    }
}
