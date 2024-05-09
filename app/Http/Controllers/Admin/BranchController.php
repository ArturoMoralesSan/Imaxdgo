<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\BranchRequest;
use Illuminate\Support\Str;

class BranchController extends Controller
{
    public function index()
    {
        abort_unless(Gate::allows('view.branches') || Gate::allows('create.branches'), 403);
        $branches = Branch::all()->each->setAppends([]);
        return view('admin.sucursales.index', compact('branches'));   
    }

    public function save(BranchRequest $request)
    {
        abort_unless(Gate::allows('view.branches') || Gate::allows('edit.branches'), 403);
        
        $branches = new Branch;
        $branches->name = $request->name;
        $branches->key_name = Str::slug($request->name);
        $branches->save();

        alert('Se ha agregado una sucursal.');

        return response('', 204, [
            'Redirect-To' => url('admin/sucursales/')
        ]);
    }

    public function edit($id)
    {
        abort_unless(Gate::allows('view.branches') || Gate::allows('edit.branches'), 403);
        $branch = Branch::find($id);
        return view('admin.sucursales.editar', compact('branch'));
    }


    public function update(BranchRequest $request, $id)
    {
        abort_unless(Gate::allows('view.branches') || Gate::allows('edit.branches'), 403);

        $branch = Branch::find($id);
        $branch->name = $request->name;
        $branch->key_name = Str::slug($request->name);
        $branch->save();

        alert('Se ha actualizado una sucursal.');

        return response('', 204, [
            'Redirect-To' => url('admin/sucursales/')
        ]);
    }

    public function delete($id)
    {
        abort_unless(Gate::allows('view.branches') || Gate::allows('create.branches'), 403);

        $branch = Branch::find($id);
        $branch->delete();

        return response('', 204);

    }
}
