@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Projeto</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('projetos.update', $projeto) }}" method="POST">
        @include('projetos._form')
        <button type="submit" class="btn btn-dark">Atualizar Projeto</button>
        <a href="{{ route('projetos.index') }}" class="btn btn-dark">Cancelar</a>
    </form>
</div>
<br>
@endsection
