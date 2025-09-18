<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estacionamento;
use App\Models\Projeto;
use App\Models\Setor;
use App\Models\SetorGrid;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EstacionamentoController extends Controller
{
    /**
     * Lista todos os estacionamentos
     */
    public function index()
    {
        $estacionamentos = Estacionamento::with('projeto')->get();
        return view('listarestacionamentos', compact('estacionamentos'));
    }

    /**
     * Exibe o formulário para criar um novo estacionamento
     */
    public function create()
    {
        $projetos = Projeto::all();
        return view('novoestacionamento', compact('projetos'));
    }

    /**
     * Salva um novo estacionamento no banco
     */
    public function store(Request $request)
    {
        $request->validate([
            'idProjeto' => 'required|exists:tb_projeto,idProjeto',
            'gridsEixoX' => 'required|integer|min:1',
            'gridsEixoY' => 'required|integer|min:1',
            'vagasCarro' => 'required|integer|min:0',
            'vagasMoto' => 'required|integer|min:0',
            'vagasPreferencial' => 'required|integer|min:0',
            'vagasPcd' => 'required|integer|min:0',
        ]);

        Estacionamento::create($request->all());

        return redirect()->back()->with('success', 'Estacionamento cadastrado com sucesso!');
    }

    /**
     * Exibe detalhes de um estacionamento
     */
    public function show(Estacionamento $estacionamento)
    {
        $estacionamento->load('projeto');
        return view('mostrarestacionamento', compact('estacionamento'));
    }

    /**
     * Exibe o formulário para editar um estacionamento existente
     */
    public function edit(Estacionamento $estacionamento)
    {
        $projetos = Projeto::all();
        return view('editarestacionamento', compact('estacionamento', 'projetos'));
    }

    /**
     * Atualiza os dados de um estacionamento no banco
     */
    public function update(Request $request, Estacionamento $estacionamento)
    {
        $request->validate([
            'idProjeto' => 'required|exists:tb_projeto,idProjeto',
            'gridsEixoX' => 'required|integer|min:1',
            'gridsEixoY' => 'required|integer|min:1',
            'vagasCarro' => 'required|integer|min:0',
            'vagasMoto' => 'required|integer|min:0',
            'vagasPreferencial' => 'required|integer|min:0',
            'vagasPcd' => 'required|integer|min:0',
        ]);

        $estacionamento->update($request->all());

        return redirect()->route('estacionamentos.index')
                         ->with('success', 'Estacionamento atualizado com sucesso!');
    }

    /**
     * Remove um estacionamento do banco
     */
    public function destroy(Estacionamento $estacionamento)
    {
        $estacionamento->delete();
        return redirect()->route('estacionamentos.index')
                         ->with('success', 'Estacionamento excluído com sucesso!');
    }

    /**
     * Salva os setores do estacionamento
     */
    public function salvarSetores(Request $request)
    {
        try {
            Log::info('salvarSetores chamado', $request->all());

            $data = $request->validate([
                'idEstacionamento' => 'required|integer|exists:tb_estacionamento,idEstacionamento',
                'setores' => 'required|array',
                'setores.*.nomeSetor' => 'required|string',
                'setores.*.corSetor' => 'nullable|string',
                'setores.*.grids' => 'required|array',
                'setores.*.grids.*.x' => 'required|integer',
                'setores.*.grids.*.y' => 'required|integer',
            ]);

            DB::beginTransaction();

            $estacionamento = Estacionamento::find($data['idEstacionamento']);
            if (!$estacionamento) {
                return response()->json(['success'=>false,'message'=>'Estacionamento não encontrado'], 404);
            }

            // Apaga setores antigos
            $estacionamento->setores()->delete();

            // Salva novos setores
            foreach ($data['setores'] as $setorData) {
                $setor = Setor::create([
                    'idEstacionamento' => $data['idEstacionamento'],
                    'nomeSetor' => $setorData['nomeSetor'],
                    'corSetor' => $setorData['corSetor'] ?? null,
                ]);

                $grids = [];
                foreach ($setorData['grids'] as $grid) {
                    $grids[] = [
                        'idSetor' => $setor->idSetor,
                        'posX' => $grid['x'],
                        'posY' => $grid['y'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                if (count($grids) > 0) {
                    SetorGrid::insert($grids);
                }
            }

            DB::commit();
            return response()->json(['success'=>true,'message'=>'Setores salvos com sucesso']);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success'=>false,'errors'=>$e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao salvar setores: '.$e->getMessage());
            return response()->json(['success'=>false,'message'=>'Erro ao salvar setores: '.$e->getMessage()], 500);
        }
    }

    /**
     * Retorna os dados de um estacionamento
     */
    public function getEstacionamento($idProjeto, $idEstacionamento)
    {
        $estacionamento = Estacionamento::with('projeto')
            ->where('idProjeto', $idProjeto)
            ->where('idEstacionamento', $idEstacionamento)
            ->first();

        if(!$estacionamento) {
            return response()->json(['error' => 'Estacionamento não encontrado'], 404);
        }

        return response()->json([
            'idProjeto' => $estacionamento->idProjeto,
            'idEstacionamento' => $estacionamento->idEstacionamento,
            'gridsEixoX' => $estacionamento->gridsEixoX,
            'gridsEixoY' => $estacionamento->gridsEixoY,
            'vagasCarro' => $estacionamento->vagasCarro,
            'vagasMoto' => $estacionamento->vagasMoto,
            'vagasPreferencial' => $estacionamento->vagasPreferencial,
            'vagasPcd' => $estacionamento->vagasPcd,
            'nomeProjeto' => $estacionamento->projeto->nomeProjeto ?? null
        ]);
    }

    public function vagasEstacionamento($idEstacionamento)
    {
        $estacionamento = Estacionamento::with(['projeto', 'setores.grids'])->findOrFail($idEstacionamento);

        return view('vagasestacionamento', compact('estacionamento'));
    }
}
