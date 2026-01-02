<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profile; // Use Profile model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // validar los datos del formulario
        $request->validate([
            'nombre_completo' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'string', 
                'email:rfc,dns', // Valida formato RFC y verifica que el dominio exista (DNS)
                'max:255', 
                'unique:users',
                function ($attribute, $value, $fail) {
                    // Bloquear dominios de email desechables/temporales comunes
                    $blockedDomains = [
                        'tempmail.com', 'guerrillamail.com', 'mailinator.com', 
                        'throwaway.email', 'yopmail.com', 'fakeinbox.com',
                        'trashmail.com', '10minutemail.com', 'temp-mail.org',
                        'disposablemail.com', 'getairmail.com', 'maildrop.cc'
                    ];
                    $domain = strtolower(substr(strrchr($value, "@"), 1));
                    if (in_array($domain, $blockedDomains)) {
                        $fail('No se permiten direcciones de correo temporales.');
                    }
                }
            ],
            'telefono' => ['nullable', 'string', 'max:20'],
            'direccion' => ['nullable', 'string', 'max:500'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        DB::transaction(function () use ($request) {
            // 1 crear el usuario
            $user = User::create([
                'name' => $request->nombre_completo, 
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // 2 crear el perfil
            Profile::create([
                'user_id' => $user->id,
                'role' => 'client',
                'phone' => $request->telefono,
                'address' => $request->direccion,
            ]);

            // iniciar sesion
            Auth::login($user);
        });

        return redirect(route('home'));
    }
}
