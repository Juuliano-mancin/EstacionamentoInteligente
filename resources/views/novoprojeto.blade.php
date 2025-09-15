@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Novo Projeto</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('projetos.store') }}" method="POST">
        @include('projetos._form')
        <button type="submit" class="btn btn-dark">Salvar Projeto</button>
    </form>
</div>
<br>
@endsection
