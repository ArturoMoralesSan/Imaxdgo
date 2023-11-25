<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function index()
    {
        abort_unless(Gate::allows('view.roles') || Gate::allows('create.roles'), 403);
        $roles = Role::all();
        return view('admin.roles.index', compact('roles'));   
    }

    public function save(RoleRequest $request)
    {
        abort_unless(Gate::allows('view.roles') || Gate::allows('edit.roles'), 403);
        
        $role = new Role;
        $role->name = $request->name;
        $role->key_name = Str::slug($request->name);
        $role->save();

        alert('Se ha agregado un permiso.');

        return response('', 204, [
            'Redirect-To' => url('admin/roles/')
        ]);
    }

    public function edit($id)
    {
        abort_unless(Gate::allows('view.roles') || Gate::allows('edit.roles'), 403);
        $role = Role::find($id);

        return view('admin.roles.editar', compact('role'));
    }


    public function update(RoleRequest $request, $id)
    {
        abort_unless(Gate::allows('view.roles') || Gate::allows('edit.Role'), 403);

        $role = Role::find($id);
        $role->name = $request->name;
        $role->save();

        alert('Se ha actualizado un permiso.');

        return response('', 204, [
            'Redirect-To' => url('admin/roles/')
        ]);
    }

    public function delete($id)
    {
        abort_unless(Gate::allows('view.roles') || Gate::allows('create.roles'), 403);

        $role = Role::find($id);
        $role->delete();
        
        return response('', 204);

    }
}
