@extends('layouts.app')

@section('title', 'Edytuj użytkownika')

@section('content')
    <div class="card auth-wrapper" style="max-width: 600px; margin: 0 auto;">
        <h1>Edytuj użytkownika</h1>

        <p class="mt-2 text-muted">
            Zmień dane użytkownika. Jeśli nie chcesz zmieniać hasła, pozostaw pola hasła puste.
        </p>

        {{-- Błędy walidacji --}}
        @if ($errors->any())
            <div class="alert alert-error mt-3">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST"
              action="{{ route('admin.users.update', $user->id) }}"
              class="mt-3">
            @csrf
            @method('PUT')

            {{-- Imię i nazwisko --}}
            <div class="form-group">
                <label for="name">Imię i nazwisko:</label>
                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name', $user->name) }}"
                       required>
            </div>

            {{-- E-mail --}}
            <div class="form-group">
                <label for="email">Adres e-mail:</label>
                <input type="email"
                       id="email"
                       name="email"
                       value="{{ old('email', $user->email) }}"
                       required>
            </div>

            {{-- Rola --}}
            <div class="form-group">
                <label for="role">Rola użytkownika:</label>
                <select id="role" name="role" required>
                    <option value="client" {{ old('role', $user->role) === 'client' ? 'selected' : '' }}>Klient</option>
                    <option value="moderator" {{ old('role', $user->role) === 'moderator' ? 'selected' : '' }}>Moderator</option>
                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrator</option>
                </select>
            </div>

            {{-- Hasło (opcjonalnie) --}}
            <div class="form-group">
                <label for="password">Nowe hasło (opcjonalnie):</label>
                <input type="password"
                       id="password"
                       name="password">
            </div>

            <div class="form-group">
                <label for="password_confirmation">Powtórz nowe hasło:</label>
                <input type="password"
                       id="password_confirmation"
                       name="password_confirmation">
            </div>

            <button type="submit" class="btn btn-primary mt-2">
                Zapisz zmiany
            </button>

            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary mt-2" style="margin-left: 6px;">
                Anuluj
            </a>
        </form>
    </div>
@endsection
