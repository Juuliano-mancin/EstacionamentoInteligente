@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Novo Estacionamento</h1>

    {{-- Mensagem de sucesso --}}
    @if(session('success'))
        <div class="alert alert-success mt-2">
            {{ session('success') }}
        </div>
    @endif

    {{-- Exibe erros de validação --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('estacionamentos.store') }}" method="POST">
        @csrf
        @include('projetos._formEstacionamento', ['submitButtonText' => 'Salvar Estacionamento'])
    </form>

</div>
@endsection
