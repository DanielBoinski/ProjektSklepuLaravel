@extends('layouts.app')

@section('title', 'Logowanie')

@section('content')
    <div class="auth-wrapper">
        <div class="card">
            <h1>Logowanie</h1>

            @if ($errors->any())
                <div class="alert alert-error mt-2">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="mt-3">
                @csrf

                <div class="form-group">
                    <label for="email">E-mail:</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required>
                </div>

                <div class="form-group">
                    <label for="password">Hasło:</label>
                    <input type="password" name="password" id="password" required>
                </div>

                <button type="submit" class="btn btn-primary mt-2">Zaloguj</button>
            </form>

            <p class="mt-3 text-muted">
                Nie masz konta?
                <a href="{{ route('register') }}">Zarejestruj się</a>
            </p>

            @if(config('app.debug'))
                <hr class="mt-3">
                <p class="text-muted" style="font-size: 0.85rem;">
                    <em>Tryb deweloperski - dane testowe dostępne w seederze.</em>
                </p>
            @endif
        </div>
    </div>
@endsection
