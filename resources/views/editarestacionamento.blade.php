@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Estacionamento</h1>

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

    <form action="{{ route('estacionamentos.update', $estacionamento) }}" method="POST">
        @csrf
        @method('PUT')
        @include('partials._formEstacionamento', ['submitButtonText' => 'Atualizar Estacionamento'])
    </form>
    
</div>
@endsection
