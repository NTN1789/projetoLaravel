<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        return Permission::with('children')->paginate(10);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'parent_id' => 'nullable|exists:permissions,id'
        ]);

        return Permission::create($validated);
    }

    public function show($id)
    {
        return Permission::with('children')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string',
            'parent_id' => 'nullable|exists:permissions,id'
        ]);

        $permission = Permission::findOrFail($id);
        $permission->update($validated);

        return $permission;
    }

    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return response()->json(['message' => 'Permissão excluída com sucesso.']);
    }
}
