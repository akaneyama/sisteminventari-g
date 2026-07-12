<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $users = User::when($search, function ($query, $search) {
            $query->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%");
        })->latest('id_user')->get();

        return view('admin.users.index', compact('users', 'search'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nip'          => 'nullable|string|max:50',
            'username'     => 'required|string|max:255|unique:users,username',
            'email'        => 'required|email|max:255|unique:users,email',
            'password'     => 'required|string|min:6',
            'role'         => 'required|in:Admin,Kepsek',
        ]);

        $data['password'] = Hash::make($data['password']);
        
        User::create($data);

        return redirect()->route('users.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nip'          => 'nullable|string|max:50',
            'username'     => 'required|string|max:255|unique:users,username,' . $id . ',id_user',
            'email'        => 'required|email|max:255|unique:users,email,' . $id . ',id_user',
            'role'         => 'required|in:Admin,Kepsek',
            'password'     => 'nullable|string|min:6',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        if (auth()->id() == $id) {
            return redirect()->route('users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang login.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    public function trash()
    {
        $users = User::onlyTrashed()->latest('deleted_at')->get();
        return view('admin.users.trash', compact('users'));
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();
        
        return redirect()->route('users.trash')->with('success', 'Akun pengguna berhasil dipulihkan dan dapat login kembali.');
    }
}
