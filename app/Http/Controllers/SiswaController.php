<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    // Daftar semua siswa
    public function index()
    {
        $siswas = User::where('role', 'siswa')->paginate(10);
        return view('admin.siswas.index', compact('siswas'));
    }

    // Form buat siswa baru
    public function create()
    {
        $classes = ClassModel::all();
        return view('admin.siswas.form', compact('classes'));
    }

    // Simpan siswa baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nohp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
            'classes' => 'required|array',
            'classes.*' => 'exists:classes,id',
        ], [
            'name.required' => 'Nama siswa harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'nohp.required' => 'No HP harus diisi',
            'alamat.required' => 'Alamat harus diisi',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'classes.required' => 'Pilih minimal satu kelas',
        ]);

        $siswa = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'nohp' => $validated['nohp'],
            'alamat' => $validated['alamat'],
            'password' => Hash::make($validated['password']),
            'role' => 'siswa',
        ]);

        // Attach kelas
        $siswa->classesAsStudent()->attach($validated['classes']);

        return redirect()->route('admin.siswas.index')
            ->with('success', 'Siswa berhasil ditambahkan');
    }

    // Form edit siswa
    public function edit(User $siswa)
    {
        if ($siswa->role !== 'siswa') {
            return redirect()->back()->with('error', 'Data tidak valid');
        }
        $classes = ClassModel::all();
        $selectedClasses = $siswa->classesAsStudent->pluck('id')->toArray();
        return view('admin.siswas.form', compact('siswa', 'classes', 'selectedClasses'));
    }

    // Update siswa
    public function update(Request $request, User $siswa)
    {
        if ($siswa->role !== 'siswa') {
            return redirect()->back()->with('error', 'Data tidak valid');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $siswa->id,
            'nohp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'password' => 'nullable|string|min:6|confirmed',
            'classes' => 'required|array',
            'classes.*' => 'exists:classes,id',
        ], [
            'name.required' => 'Nama siswa harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'nohp.required' => 'No HP harus diisi',
            'alamat.required' => 'Alamat harus diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'classes.required' => 'Pilih minimal satu kelas',
        ]);

        $siswa->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'nohp' => $validated['nohp'],
            'alamat' => $validated['alamat'],
        ]);

        if ($validated['password']) {
            $siswa->update(['password' => Hash::make($validated['password'])]);
        }

        // Sync kelas
        $siswa->classesAsStudent()->sync($validated['classes']);

        return redirect()->route('admin.siswas.index')
            ->with('success', 'Siswa berhasil diupdate');
    }

    // Hapus siswa
    public function destroy(User $siswa)
    {
        if ($siswa->role !== 'siswa') {
            return redirect()->back()->with('error', 'Data tidak valid');
        }

        $siswa->delete();

        return redirect()->route('admin.siswas.index')
            ->with('success', 'Siswa berhasil dihapus');
    }

    // Show detail siswa
    public function show(User $siswa)
    {
        if ($siswa->role !== 'siswa') {
            return redirect()->back()->with('error', 'Data tidak valid');
        }
        $classes = $siswa->classesAsStudent;
        $grades = $siswa->grades;
        return view('admin.siswas.show', compact('siswa', 'classes', 'grades'));
    }
}
