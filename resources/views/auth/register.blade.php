@extends('layouts.app')

@section('title', 'Rejestracja')

@section('content')
    <div class="auth-wrapper">
        <div class="card">
            <h1>Rejestracja</h1>

            @if ($errors->any())
                <div class="alert alert-error mt-2">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}" class="mt-3">
                @csrf

                <div class="form-group">
                    <label for="name">Imię i nazwisko:</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label for="email">E-mail:</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required>
                </div>

                <div class="form-group">
                    <label for="password">Hasło:</label>
                    <input type="password" name="password" id="password" required>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Powtórz hasło:</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required>
                </div>

                <button type="submit" class="btn btn-primary mt-2">Zarejestruj się</button>
            </form>

            <p class="mt-3 text-muted">
                Masz już konto?
                <a href="{{ route('login') }}">Przejdź do logowania</a>
            </p>
        </div>
    </div>
@endsection
