@extends('layouts.app')

@section('title', 'Szczegóły użytkownika')

@section('content')
    <div class="card">
        <h1>Szczegóły użytkownika</h1>

        <p class="mt-2">
            Poniżej znajdują się informacje o wybranym użytkowniku.
        </p>

        <div class="mt-3">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                ⮐ Powrót do listy użytkowników
            </a>
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary" style="margin-left: 8px;">
                ✏ Edytuj użytkownika
            </a>
        </div>
    </div>

    <div class="card">
        <h2>Dane użytkownika</h2>

        <table class="table mt-3">
            <tbody>
                <tr>
                    <th style="width: 200px;">ID</th>
                    <td>{{ $user->id }}</td>
                </tr>
                <tr>
                    <th>Imię i nazwisko</th>
                    <td>{{ $user->name }}</td>
                </tr>
                <tr>
                    <th>E-mail</th>
                    <td>{{ $user->email }}</td>
                </tr>
                <tr>
                    <th>Rola</th>
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
                </tr>
                <tr>
                    <th>Utworzono</th>
                    <td>{{ $user->created_at }}</td>
                </tr>
                <tr>
                    <th>Ostatnia aktualizacja</th>
                    <td>{{ $user->updated_at }}</td>
                </tr>
            </tbody>
        </table>

        <form action="{{ route('admin.users.destroy', $user->id) }}"
              method="POST"
              class="mt-3"
              onsubmit="return confirm('Na pewno usunąć tego użytkownika?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                🗑 Usuń użytkownika
            </button>
        </form>
    </div>
@endsection
