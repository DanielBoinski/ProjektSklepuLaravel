@extends('layouts.app')

@section('title', 'Użytkownicy')

@section('content')
    <div class="card">
        <h1>Lista użytkowników</h1>

        {{-- Komunikaty --}}
        @if (session('success'))
            <div class="alert alert-success mt-2">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error mt-2">
                {{ session('error') }}
            </div>
        @endif

        <div class="mt-3">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">⮐ Powrót do panelu admina</a>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary" style="margin-left: 10px;">
                ➕ Dodaj nowego użytkownika
            </a>
        </div>
    </div>

    <div class="card">
        <h2>Wszyscy użytkownicy</h2>

        <table class="table mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Imię i nazwisko</th>
                    <th>E-mail</th>
                    <th>Rola</th>
                    <th style="width: 220px;">Akcje</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>

                        <td>
                            <span style="
                                padding: 4px 10px;
                                border-radius: 8px;
                                background: #E9F5F0;
                                color: #2D6A4F;
                                font-weight: 600;
                                font-size: 0.9rem;
                            ">
                                {{ $user->role }}
                            </span>
                        </td>

                        <td>
                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-secondary btn-sm">
                                Podgląd
                            </a>

                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary btn-sm">
                                Edytuj
                            </a>

                            <form action="{{ route('admin.users.destroy', $user->id) }}"
                                  method="POST"
                                  style="display:inline-block;"
                                  onsubmit="return confirm('Na pewno usunąć tego użytkownika?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    Usuń
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            Brak użytkowników w bazie.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
