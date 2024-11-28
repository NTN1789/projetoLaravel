<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

use Illuminate\Support\Facades\Validator;
class UserController extends  Controller
{

    public function index()
    {
        $users = User::whereNull('deleted_at')->get();
        return response()->json($users);
    }


    public function store(Request $request)
    {


        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'organization' => 'required|string'
        
        ]);
    
                
        $user = User::create($validatedData);
        return response()->json($user, 201);
    }
    
    public function update(Request $request, $id)
    {
        // Validação dos dados do utilizador
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id, 
            'phone' => 'nullable|string|max:20',
            'organization' => 'required|string',
            'permissions' => 'array' 
        ]);
 
        $user = User::findOrFail($id);
    
      
        $user->update($validatedData);
    
      
        if ($request->has('permissions')) {
         
            $user->permissions()->sync($validatedData['permissions']);
        }
    
        return response()->json($user);
    }

    public function destroy(User $user)
       {
           $user->delete();
   
           return response()->json(['message' => 'Usuário excluído com sucesso']);
       }


       public function show($id)
       {
           // Obter o utilizador com as permissões atribuídas
           $user = User::with('permissions')->findOrFail($id);
       
           // Obter todas as permissões disponíveis
           $allPermissions = Permission::with('children')->whereNull('parent_id')->get();
       
             return response()->json([
               'user' => $user,
               'assigned_permissions' => $user->permissions->pluck('id'), // IDs das permissões já atribuídas
               'all_permissions' => $allPermissions, // Todas as permissões para exibir no frontend
           ]);
       }
       

}
