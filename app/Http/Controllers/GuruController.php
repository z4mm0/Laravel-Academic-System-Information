<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GuruController extends Controller
{
    public function index()
    {
        $gurus = User::whereIn('role', ['guru_mapel', 'wali_kelas'])->paginate(10);
        return view('admin.gurus.index', compact('gurus'));
    }

    public function create()
    {
        return view('admin.gurus.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nohp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'Nama guru harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'nohp.required' => 'No HP harus diisi',
            'alamat.required' => 'Alamat harus diisi',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'nohp' => $validated['nohp'],
            'alamat' => $validated['alamat'],
            'password' => Hash::make($validated['password']),
            'role' => 'guru_mapel',
        ]);

        return redirect()->route('admin.gurus.index')
            ->with('success', 'Guru berhasil ditambahkan');
    }

    public function edit(User $guru)
    {
        if (!in_array($guru->role, ['guru_mapel', 'wali_kelas'])) {
            return redirect()->back()->with('error', 'Data tidak valid');
        }
        return view('admin.gurus.form', compact('guru'));
    }

    public function update(Request $request, User $guru)
    {
        if (!in_array($guru->role, ['guru_mapel', 'wali_kelas'])) {
            return redirect()->back()->with('error', 'Data tidak valid');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $guru->id,
            'nohp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'name.required' => 'Nama guru harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'nohp.required' => 'No HP harus diisi',
            'alamat.required' => 'Alamat harus diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'nohp' => $validated['nohp'],
            'alamat' => $validated['alamat'],
        ];

        if (isset($validated['password']) && $validated['password']) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $guru->update($updateData);

        return redirect()->route('admin.gurus.index')
            ->with('success', 'Guru berhasil diupdate');
    }

    public function destroy(User $guru)
    {
        if (!in_array($guru->role, ['guru_mapel', 'wali_kelas'])) {
            return redirect()->back()->with('error', 'Data tidak valid');
        }

        $guru->delete();

        return redirect()->route('admin.gurus.index')
            ->with('success', 'Guru berhasil dihapus');
    }

    public function show(User $guru)
    {
        if (!in_array($guru->role, ['guru_mapel', 'wali_kelas'])) {
            return redirect()->back()->with('error', 'Data tidak valid');
        }
        $classes = $guru->classes;
        $subjects = $guru->subjects;
        return view('admin.gurus.show', compact('guru', 'classes', 'subjects'));
    }
}

