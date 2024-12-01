<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

use Illuminate\Support\Facades\Validator;
class UserController extends  Controller
{
    public function index()
    {
        return User::paginate(10);
    }







    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string',
            'organization' => 'required|string'  
        ]);

        return User::create($validated);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }




    public function update(Request $request, $id)
{
    $validated = $request->validate([
        'name' => 'sometimes|string',
        'email' => 'sometimes|email|unique:users,email,' . $id,
        'phone' => 'nullable|string',
        'organization' => 'sometimes|string'
    ]);

    $user = User::findOrFail($id);
    $user->update($validated);

    return $user;
}
    

public function destroy($id)
{
    $user = User::findOrFail($id);
    $user->delete();

    return response()->json(['message' => 'Usuário excluído com sucesso.']);
}

       


public function assignPermissions(Request $request, $id)
{
    $user = User::findOrFail($id);


    $validated = $request->validate([
        'permissions' => 'required|array',
        'permissions.*' => 'exists:permissions,id' 
    ]);

   
    $user->permissions()->sync($validated['permissions']);

    return response()->json([
        'message' => 'Permissões sincronizadas com sucesso!',
        'user_permissions' => $user->permissions()->get() 
    ]);
}

}
