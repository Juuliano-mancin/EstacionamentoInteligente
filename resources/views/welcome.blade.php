@extends('layouts.app')

@section('title', 'Homepage')

@section('content')
<div class="text-center">
    <h1 class="display-4">Projeto Integrador 4º Semestre</h1>
    <h2 class="text-secondary">Em Desenvolvimento</h2>
    <br><br>
    <a href="{{ route('login') }}" class="btn btn-dark btn-lg">Entrar no sistema</a>
</div>
@endsection