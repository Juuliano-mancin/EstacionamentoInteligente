<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Projeto;

class ProjetoController extends Controller
{
    public function index()
    {
        $projetos = Projeto::orderBy('id_projeto', 'desc')->paginate(10);
        return view('listaprojeto', compact('projetos'));
    }

    // Exibe o formulário de criação de projeto
    public function create()
    {
        return view('novoprojeto');
    }

    // Processa o envio do formulário de novo projeto
    public function store(Request $request)
    {
        // Validação completa
        $request->validate([
            'nomeprojeto'        => 'required|string|max:100|unique:tb_projeto,nomeprojeto',
            'nomeFantasia'       => 'required|string|max:150',
            'razaoSocial'        => 'required|string|max:150',
            'contatoNome'        => 'required|string|max:100',
            'contatoCelular'     => 'nullable|string|max:20',
            'contatoEmail'       => 'nullable|email|max:150',
            'enderecoLogradouro' => 'required|string|max:150',
            'enderecoNumero'     => 'required|string|max:10',
            'enderecoComplemento'=> 'nullable|string|max:50',
            'enderecoBairro'     => 'required|string|max:100',
            'enderecoCidade'     => 'required|string|max:100',
            'enderecoEstado'     => 'required|string|size:2',
            'enderecoCEP'        => 'required|string|max:9',
        ]);

        // Salva os dados usando o Model Projeto
        Projeto::create($request->all());

        // Redireciona para a dashboard ou outra rota desejada
        return redirect()->route('projetos.index')->with('success', 'Projeto cadastrado com sucesso!');
    }

    public function edit(Projeto $projeto)
    {
        return view('editarprojeto', compact('projeto'));
    }

    public function update(Request $request, Projeto $projeto)
    {
        $request->validate([
            'nomeprojeto'        => 'required|string|max:100|unique:tb_projeto,nomeprojeto,' . $projeto->id_projeto . ',id_projeto',
            'nomeFantasia'       => 'required|string|max:150',
            'razaoSocial'        => 'required|string|max:150',
            'contatoNome'        => 'required|string|max:100',
            'contatoCelular'     => 'nullable|string|max:20',
            'contatoEmail'       => 'nullable|email|max:150',
            'enderecoLogradouro' => 'required|string|max:150',
            'enderecoNumero'     => 'required|string|max:10',
            'enderecoComplemento'=> 'nullable|string|max:50',
            'enderecoBairro'     => 'required|string|max:100',
            'enderecoCidade'     => 'required|string|max:100',
            'enderecoEstado'     => 'required|string|size:2',
            'enderecoCEP'        => 'required|string|max:9',
        ]);

        $projeto->update($request->all());
        return redirect()->route('projetos.index')->with('success', 'Projeto atualizado com sucesso!');
    }

    public function destroy(Projeto $projeto)
    {
        $projeto->delete();
        return redirect()->route('projetos.index')->with('success', 'Projeto excluído com sucesso!');
    }
}

