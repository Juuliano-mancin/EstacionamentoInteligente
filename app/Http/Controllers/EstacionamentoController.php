<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estacionamento;
use App\Models\Projeto;

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

        // Redireciona de volta para o formulário atual com mensagem de sucesso
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
}
