@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="w-100" style="max-width: 400px; margin: 0 auto;">
    <h3 class="text-center mb-4">Login</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <div class="mb-3">
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="form-control" placeholder="E-mail" required autofocus>
        </div>
        <div class="mb-3">
            <input id="password" type="password" name="password"
                   class="form-control" placeholder="Senha" required>
        </div>
        <button type="submit" class="btn btn-dark w-100">Entrar</button>
    </form>
</div>
@endsection
