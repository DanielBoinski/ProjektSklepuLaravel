<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
   
    public function index()
    {
        $users = User::all();

        return view('admin.users.index', compact('users'));
    }

 
    public function create()
    {
        return view('admin.users.create');
    }

   
    public function store(Request $request)
    {
        
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required|in:admin,moderator,client',
        ]);

        
        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Użytkownik został dodany.');
    }

   
    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.show', compact('user'));
    }

   
    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.edit', compact('user'));
    }

    
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|in:admin,moderator,client',
            'password' => 'nullable|min:6',
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->role  = $request->role;

        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'Dane użytkownika zostały zaktualizowane.');
    }

   
    public function destroy($id)
    {
        $user = User::findOrFail($id);

      
        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Nie możesz usunąć aktualnie zalogowanego użytkownika.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Użytkownik został usunięty.');
    }
}
