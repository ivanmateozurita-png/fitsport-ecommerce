<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Profile;

class ProfileController extends Controller
{

    /**
     * Mostrar perfil del usuario
     */
    public function show()
    {
        $user = Auth::user();
        $profile = $user->profile;
        
        // Crear perfil si no existe
        if (!$profile) {
            $profile = Profile::create([
                'user_id' => $user->id,
                'role' => 'client'
            ]);
        }
        
        return view('profile.show', compact('user', 'profile'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->profile;
        
        if (!$profile) {
            $profile = Profile::create([
                'user_id' => $user->id,
                'role' => 'client'
            ]);
        }
        
        return view('profile.edit', compact('user', 'profile'));
    }

    /**
     * Actualizar perfil
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->save();

        $profile = $user->profile;
        
        if (!$profile) {
            $profile = new Profile();
            $profile->user_id = $user->id;
            $profile->role = 'client';
        }

        $profile->phone = $request->phone;
        $profile->address = $request->address;
        $profile->city = $request->city;

        // Manejar imagen
        if ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe
            if ($profile->image_path) {
                $oldImagePath = $profile->image_path;
                
                // Intentar eliminar en diferentes ubicaciones posibles
                $possiblePaths = [
                    public_path('storage/' . $oldImagePath),
                    base_path('../public_html/storage/' . $oldImagePath)
                ];
                
                foreach ($possiblePaths as $oldPath) {
                    if (file_exists($oldPath)) {
                        @unlink($oldPath);
                        break;
                    }
                }
            }
            
            $file = $request->file('image');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Determinar ruta según entorno
            if (app()->environment('production') && file_exists(base_path('../public_html'))) {
                // Producción (Hostinger)
                $storageBase = base_path('../public_html/storage');
                $destinationPath = $storageBase . '/profiles';
            } else {
                // Local (Laragon)
                $storageBase = public_path('storage');
                $destinationPath = $storageBase . '/profiles';
            }
            
            // Crear directorios recursivamente si no existen
            if (!is_dir($destinationPath)) {
                if (!mkdir($destinationPath, 0755, true) && !is_dir($destinationPath)) {
                    throw new \RuntimeException(sprintf('Directory "%s" was not created', $destinationPath));
                }
            }
            
            $file->move($destinationPath, $filename);
            $profile->image_path = 'profiles/' . $filename;
        }

        $profile->save();

        return redirect()->route('profile.show')->with('success', '¡Perfil actualizado correctamente!');
    }
}
