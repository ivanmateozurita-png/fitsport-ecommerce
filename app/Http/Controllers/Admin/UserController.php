<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with(['profile', 'roles'])->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function edit(string $id)
    {
        $user = User::with(['profile', 'roles'])->findOrFail($id);
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'role' => 'required|in:admin,bodeguero,client',
        ]);

        $user = User::findOrFail($id);
        
        // Usar Spatie Permission - syncRoles reemplaza todos los roles
        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users.index')->with('success', 'Rol de usuario actualizado correctamente.');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
