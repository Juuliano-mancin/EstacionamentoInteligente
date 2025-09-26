<?php

namespace App\Http\Controllers; /* define o namespace do controller */

use Illuminate\Http\Request; /* importa a classe Request para manipular dados de requisições HTTP */
use App\Models\Projeto; /* importa o model Projeto */

class ProjetoController extends Controller /* controller responsável por gerenciar projetos, herda funcionalidades da classe base Controller */
    {
        public function index() /* método que lista os projetos */
            {
                $projetos = Projeto::orderBy('idProjeto', 'desc')->paginate(10); /* busca os projetos em ordem decrescente por id e paginados de 10 em 10 */
                return view('listaprojeto', compact('projetos')); /* retorna a view 'listaprojeto' passando os dados dos projetos */
            }

        public function create() /* método que exibe o formulário para criar um novo projeto */
            {
                return view('novoprojeto'); /* retorna a view 'novoprojeto' */
            }

        public function store(Request $request) /* método que processa o envio do formulário de novo projeto */
            {
                $request->validate([ /* valida os dados enviados no formulário */
                    'nomeProjeto'        => 'required|string|max:100|unique:tb_projeto,nomeProjeto', /* obrigatório, string, máximo 100 caracteres, único */
                    'nomeFantasia'       => 'required|string|max:150', /* obrigatório, string, máximo 150 caracteres */
                    'razaoSocial'        => 'required|string|max:150', /* obrigatório, string, máximo 150 caracteres */
                    'contatoNome'        => 'required|string|max:100', /* obrigatório, string, máximo 100 caracteres */
                    'contatoCelular'     => 'nullable|string|max:20', /* opcional, string, máximo 20 caracteres */
                    'contatoEmail'       => 'nullable|email|max:150', /* opcional, deve ser e-mail válido, máximo 150 caracteres */
                    'enderecoLogradouro' => 'required|string|max:150', /* obrigatório, string, máximo 150 caracteres */
                    'enderecoNumero'     => 'required|string|max:10', /* obrigatório, string, máximo 10 caracteres */
                    'enderecoComplemento'=> 'nullable|string|max:50', /* opcional, string, máximo 50 caracteres */
                    'enderecoBairro'     => 'required|string|max:100', /* obrigatório, string, máximo 100 caracteres */
                    'enderecoCidade'     => 'required|string|max:100', /* obrigatório, string, máximo 100 caracteres */
                    'enderecoEstado'     => 'required|string|size:2', /* obrigatório, string, exatamente 2 caracteres */
                    'enderecoCEP'        => 'required|string|max:9', /* obrigatório, string, máximo 9 caracteres */
                ]);

                Projeto::create($request->all()); /* cria um novo registro no banco de dados com os dados validados */

                return redirect()->route('projetos.index')->with('success', 'Projeto cadastrado com sucesso!'); /* redireciona para a lista de projetos com mensagem de sucesso */
            }

        public function edit(Projeto $projeto) /* método que exibe o formulário de edição de um projeto */
            {
                return view('editarprojeto', compact('projeto')); /* retorna a view 'editarprojeto' passando os dados do projeto */
            }

        public function update(Request $request, Projeto $projeto) /* método que atualiza um projeto existente */
            {
                $request->validate([ /* valida os dados enviados no formulário */
                    'nomeProjeto'        => 'required|string|max:100|unique:tb_projeto,nomeProjeto,' . $projeto->idProjeto . ',idProjeto', /* obrigatório, string, máximo 100 caracteres, único ignorando o projeto atual */
                    'nomeFantasia'       => 'required|string|max:150', /* obrigatório, string, máximo 150 caracteres */
                    'razaoSocial'        => 'required|string|max:150', /* obrigatório, string, máximo 150 caracteres */
                    'contatoNome'        => 'required|string|max:100', /* obrigatório, string, máximo 100 caracteres */
                    'contatoCelular'     => 'nullable|string|max:20', /* opcional, string, máximo 20 caracteres */
                    'contatoEmail'       => 'nullable|email|max:150', /* opcional, e-mail válido, máximo 150 caracteres */
                    'enderecoLogradouro' => 'required|string|max:150', /* obrigatório, string, máximo 150 caracteres */
                    'enderecoNumero'     => 'required|string|max:10', /* obrigatório, string, máximo 10 caracteres */
                    'enderecoComplemento'=> 'nullable|string|max:50', /* opcional, string, máximo 50 caracteres */
                    'enderecoBairro'     => 'required|string|max:100', /* obrigatório, string, máximo 100 caracteres */
                    'enderecoCidade'     => 'required|string|max:100', /* obrigatório, string, máximo 100 caracteres */
                    'enderecoEstado'     => 'required|string|size:2', /* obrigatório, string, exatamente 2 caracteres */
                    'enderecoCEP'        => 'required|string|max:9', /* obrigatório, string, máximo 9 caracteres */
                ]);

                $projeto->update($request->all()); /* atualiza os dados do projeto no banco */
                return redirect()->route('projetos.index')->with('success', 'Projeto atualizado com sucesso!'); /* redireciona para a lista de projetos com mensagem de sucesso */
            }

        public function destroy(Projeto $projeto) /* método que exclui um projeto */
            {
                $projeto->delete(); /* remove o registro do banco de dados */
                return redirect()->route('projetos.index')->with('success', 'Projeto excluído com sucesso!'); /* redireciona para a lista de projetos com mensagem de sucesso */
            }
    }