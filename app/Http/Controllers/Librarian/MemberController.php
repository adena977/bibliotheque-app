<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Borrowing;
use App\Models\Fine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'member');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        $members = $query->orderBy('name')->paginate(20);
        
        return view('librarian.members.index', compact('members'));
    }

    public function show(User $member)
    {
        if ($member->role !== 'member') {
            abort(404);
        }
        
        $activeBorrowings = Borrowing::where('user_id', $member->id)
            ->where('status', 'ongoing')
            ->with('book')
            ->get();
        
        $borrowingHistory = Borrowing::where('user_id', $member->id)
            ->where('status', 'returned')
            ->with('book')
            ->orderBy('returned_at', 'desc')
            ->paginate(10);
        
        $pendingFines = Fine::where('user_id', $member->id)
            ->whereIn('status', ['pending', 'partially_paid'])
            ->with('borrowing.book')
            ->get();
        
        $totalFines = $pendingFines->sum('amount') - $pendingFines->sum('paid_amount');
        
        return view('librarian.members.show', compact('member', 'activeBorrowings', 'borrowingHistory', 'pendingFines', 'totalFines'));
    }

    public function create()
    {
        return view('librarian.members.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'password' => 'required|string|min:6|confirmed'
        ]);
        
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'role' => 'member',
            'is_active' => true,
            'membership_date' => now(),
            'total_fine' => 0,
            'total_borrowed' => 0
        ]);
        
        return redirect()->route('librarian.members.index')
            ->with('success', 'Membre ajouté avec succès. Mot de passe: ' . $validated['password']);
    }

    public function edit(User $member)
    {
        if ($member->role !== 'member') {
            abort(404);
        }
        return view('librarian.members.edit', compact('member'));
    }

    public function update(Request $request, User $member)
    {
        if ($member->role !== 'member') {
            abort(404);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $member->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);
        
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6|confirmed']);
            $validated['password'] = Hash::make($request->password);
        }
        
        $member->update($validated);
        
        return redirect()->route('librarian.members.show', $member)
            ->with('success', 'Membre modifié avec succès.');
    }

    public function toggleStatus(User $member)
    {
        if ($member->role !== 'member') {
            abort(404);
        }
        
        $member->is_active = !$member->is_active;
        $member->save();
        
        $status = $member->is_active ? 'activé' : 'désactivé';
        return back()->with('success', "Membre {$status} avec succès.");
    }

    public function destroy(User $member)
    {
        if ($member->role !== 'member') {
            abort(404);
        }
        
        if ($member->borrowings()->where('status', 'ongoing')->exists()) {
            return back()->with('error', 'Ce membre a des emprunts en cours.');
        }
        
        $member->delete();
        
        return redirect()->route('librarian.members.index')
            ->with('success', 'Membre supprimé avec succès.');
    }
}