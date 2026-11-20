<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    // Halaman pengaturan
    public function index()
    {
        return view('setting.index', ['user' => auth()->user()]);
    }

    // Simpan perubahan profil
    public function updateProfil(Request $request)
    {
        $user = auth()->user();

        $data = $request->validateWithBag('profil', [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'no_telp' => ['nullable', 'string', 'max:20'],
        ]);

        $user->forceFill([
            'name' => $data['name'],
            'email' => $data['email'],
            'no_telp' => $data['no_telp'] ?? null,
        ])->save();

        return redirect('/pengaturan')->with('success_profil', 'Profil berhasil diperbarui.');
    }

    // Ubah password (wajib mengisi password lama)
    public function updatePassword(Request $request)
    {
        $data = $request->validateWithBag('password', [
            'password_lama' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = auth()->user();

        if (! Hash::check($data['password_lama'], $user->password)) {
            return back()->withErrors(['password_lama' => 'Password lama tidak cocok.'], 'password');
        }

        $user->forceFill(['password' => Hash::make($data['password'])])->save();

        return redirect('/pengaturan')->with('success_password', 'Password berhasil diubah.');
    }
}