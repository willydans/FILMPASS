<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua user, diurutkan berdasarkan nama
        $users = User::orderBy('name')->paginate(10); // Paginate untuk data yang banyak
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,user'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'User created successfully!');
    }


    /**
     * Update the user's role.
     */
    public function updateRole(Request $request, User $user)
    {
        // Validasi input
        $request->validate([
            'role' => 'required|in:admin,user', // Sesuaikan dengan role yang ada
        ]);

        // Update role user
        $user->update(['role' => $request->role]);

        // Redirect kembali dengan pesan sukses
        return redirect()->route('admin.dashboard')->with('success', 'User role has been updated successfully.');
    }
}
