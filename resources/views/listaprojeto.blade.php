@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Projetos Cadastrados</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('projetos.create') }}" class="btn btn-dark mb-3">Novo Projeto</a>
    <a href="{{ route('dashboard') }}" class="btn btn-dark mb-3 ms-2">Voltar para Dashboard</a>

    @if($projetos->isEmpty())
        <p>Nenhum projeto cadastrado.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome do Projeto</th>
                    <th>Nome Fantasia</th>
                    <th>Contato</th>
                    <th>Cidade</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projetos as $projeto)
                    <tr>
                        <td>{{ $projeto->idProjeto }}</td>
                        <td>{{ $projeto->nomeProjeto }}</td>
                        <td>{{ $projeto->nomeFantasia }}</td>
                        <td>{{ $projeto->contatoNome }} - {{ $projeto->contatoCelular }}</td>
                        <td>{{ $projeto->enderecoCidade }}</td>
                        <td>
                            <a href="{{ route('projetos.edit', $projeto) }}" class="btn btn-sm btn-dark">Editar</a>
                            <form action="{{ route('projetos.destroy', $projeto) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-dark" onclick="return confirm('Deseja realmente excluir este projeto?')">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{ $projetos->links() }} <!-- Paginação -->
</div>
@endsection