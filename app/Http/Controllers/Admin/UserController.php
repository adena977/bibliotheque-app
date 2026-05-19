<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
        
        $users = $query->orderBy('name')->paginate(20);
        
        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:member,librarian,admin',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'is_active' => 'boolean'
        ]);
        
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6|confirmed']);
            $validated['password'] = Hash::make($request->password);
        }
        
        $user->update($validated);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur modifié avec succès.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }
        
        if ($user->borrowings()->where('status', 'ongoing')->exists()) {
            return back()->with('error', 'Cet utilisateur a des emprunts en cours.');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }
    
    public function toggleStatus(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();
        
        $status = $user->is_active ? 'activé' : 'désactivé';
        return back()->with('success', "Utilisateur {$status} avec succès.");
    }
    public function create()
{
    return view('admin.users.create');
}

public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string',
        'role' => 'required|in:member,librarian,admin',
        'password' => 'required|min:6|confirmed',
        'is_active' => 'boolean'
    ]);
    
    $validated['password'] = Hash::make($validated['password']);
    $validated['membership_date'] = now();
    $validated['total_fine'] = 0;
    $validated['total_borrowed'] = 0;
    
    if (!isset($validated['is_active'])) {
        $validated['is_active'] = false;
    }
    
    User::create($validated);
    
    $roleName = $validated['role'] == 'admin' ? 'Administrateur' : ($validated['role'] == 'librarian' ? 'Bibliothécaire' : 'Membre');
    
    return redirect()->route('admin.users.index')
        ->with('success', $roleName . ' créé avec succès. Mot de passe: ' . $request->password);
}
}