@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Estacionamentos</h1>

    {{-- Botão para cadastrar novo --}}
    <a href="{{ route('estacionamentos.create') }}" class="btn btn-dark mb-3">Cadastrar novo estacionamento</a>
    <a href="{{ route('dashboard') }}" class="btn btn-dark mb-3">Voltar à Dashboard</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($estacionamentos->isEmpty())
        <p>Nenhum estacionamento cadastrado.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Projeto</th>
                    <th>Nome Fantasia</th> {{-- Novo campo --}}
                    <th>Grids X</th>
                    <th>Grids Y</th>
                    <th>Vagas Carro</th>
                    <th>Vagas Moto</th>
                    <th>Vagas Preferenciais</th>
                    <th>Vagas PCD</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($estacionamentos as $est)
                    <tr>
                        <td>{{ $est->idEstacionamento }}</td>
                        <td>{{ $est->projeto->nomeProjeto ?? '-' }}</td>
                        <td>{{ $est->projeto->nomeFantasia ?? '-' }}</td> {{-- Mostrando nomeFantasia --}}
                        <td>{{ $est->gridsEixoX }}</td>
                        <td>{{ $est->gridsEixoY }}</td>
                        <td>{{ $est->vagasCarro }}</td>
                        <td>{{ $est->vagasMoto }}</td>
                        <td>{{ $est->vagasPreferencial }}</td>
                        <td>{{ $est->vagasPcd }}</td>
                        <td>
                            <a href="{{ route('estacionamentos.edit', $est) }}" class="btn btn-sm btn-dark">Editar</a>
                            <form action="{{ route('estacionamentos.destroy', $est) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-dark" onclick="return confirm('Tem certeza?')">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
