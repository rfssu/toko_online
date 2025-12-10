<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        // Mengarah ke file view login Anda
        // Karena Anda bilang punya folder auth di views, pakai itu:
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate(['email' => 'required', 'password' => 'required']);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }
        return back()->withErrors(['email' => 'Salah email atau password']);
    }

    // ... method login yang sudah ada ...

    // 1. Tampilkan Form Register
    public function showRegisterForm()
    {
        // Pastikan file ini ada di resources/views/auth/register.blade.php
        return view('auth.register');
    }

    // 2. Proses Simpan User Baru
    public function register(Request $request)
    {
        // Validasi Input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5|confirmed', // Pastikan di view ada input name="password_confirmation"
        ]);

        // Buat User Baru
        // Pastikan import model User di atas: use App\Models\User;
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            // Tambahkan field lain jika perlu (misal: 'role' => 'customer')
        ]);

        // Langsung Login otomatis setelah daftar
        Auth::login($user);

        // Lempar ke dashboard
        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Setelah logout, lempar user kembali ke halaman login
        return redirect('/');
    }
}