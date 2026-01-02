<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    /**
     * Redirigir a Google para autenticación
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Manejar callback de Google
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Buscar usuario existente por google_id o email
            $user = User::where('google_id', $googleUser->getId())
                        ->orWhere('email', $googleUser->getEmail())
                        ->first();
            
            if ($user) {
                // Usuario existe - actualizar google_id si no lo tiene
                if (!$user->google_id) {
                    $user->google_id = $googleUser->getId();
                    $user->save();
                }
            } else {
                // Crear nuevo usuario
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'email_verified_at' => now(), // Google ya verificó el email
                    'password' => Hash::make(Str::random(24)), // Password aleatorio
                ]);
                
                // Crear perfil
                Profile::create([
                    'user_id' => $user->id,
                    'role' => 'client',
                ]);
            }
            
            // Iniciar sesión
            Auth::login($user);
            
            return redirect()->intended(route('home'))
                           ->with('success', '¡Bienvenido, ' . $user->name . '!');
                           
        } catch (\Exception $e) {
            return redirect()->route('login')
                           ->with('error', 'Error al iniciar sesión con Google: ' . $e->getMessage());
        }
    }
}
