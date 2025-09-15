@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Painel Admin</a>
        <div class="d-flex">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-light">Sair</button>
            </form>
        </div>
    </div>
</nav>

<div class="container-fluid mt-4 text-center">
    <div class="card shadow-sm">
        <div class="card-body">
            <h1 class="card-title">Bem-vindo, {{ Auth::user()->name }}!</h1>
            <p class="card-text mt-2">Dashboard Administrativa</p>
        </div>
    </div>
</div>

@endsection
