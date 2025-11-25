@extends('layouts.app')

@section('title', 'Dodaj użytkownika')

@section('content')
    <div class="card auth-wrapper" style="max-width: 600px; margin: 0 auto;">
        <h1>Dodaj nowego użytkownika</h1>

        <p class="mt-2 text-muted">
            Wypełnij poniższy formularz, aby dodać nowego użytkownika do systemu.
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

        <form method="POST" action="{{ route('admin.users.store') }}" class="mt-3">
            @csrf

            {{-- Imię i nazwisko --}}
            <div class="form-group">
                <label for="name">Imię i nazwisko:</label>
                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name') }}"
                       required>
            </div>

            {{-- E-mail --}}
            <div class="form-group">
                <label for="email">Adres e-mail:</label>
                <input type="email"
                       id="email"
                       name="email"
                       value="{{ old('email') }}"
                       required>
            </div>

            {{-- Hasło --}}
            <div class="form-group">
                <label for="password">Hasło:</label>
                <input type="password"
                       id="password"
                       name="password"
                       required>
            </div>

            {{-- Powtórzenie hasła --}}
            <div class="form-group">
                <label for="password_confirmation">Powtórz hasło:</label>
                <input type="password"
                       id="password_confirmation"
                       name="password_confirmation"
                       required>
            </div>

            {{-- Rola --}}
            <div class="form-group">
                <label for="role">Rola użytkownika:</label>
                <select id="role" name="role" required>
                    <option value="client" {{ old('role') === 'client' ? 'selected' : '' }}>Klient</option>
                    <option value="moderator" {{ old('role') === 'moderator' ? 'selected' : '' }}>Moderator</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary mt-2">
                Zapisz użytkownika
            </button>

            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary mt-2" style="margin-left: 6px;">
                Anuluj
            </a>
        </form>
    </div>
@endsection
