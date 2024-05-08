<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RaceRequest;
use Illuminate\Http\Request;
use App\Models\Race;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class RaceController extends Controller
{
    public function index()
    {
        abort_unless(Gate::allows('view.races') || Gate::allows('create.races'), 403);
        $races = Race::all();
        return view('admin.carreras.index', compact('races'));   
    }

    public function save(RaceRequest $request)
    {
        abort_unless(Gate::allows('view.races') || Gate::allows('edit.races'), 403);
        
        $race = new Race;
        $race->name = $request->name;
        $race->slug = Str::slug($request->name);
        $race->save();

        alert('Se ha agregado una carrera.');

        return response('', 204, [
            'Redirect-To' => url('admin/carreras/')
        ]);
    }

    public function edit($id)
    {
        abort_unless(Gate::allows('view.races') || Gate::allows('edit.races'), 403);
        $race = Race::find($id);

        return view('admin.carreras.editar', compact('race'));
    }


    public function update(RaceRequest $request, $id)
    {
        abort_unless(Gate::allows('view.races') || Gate::allows('edit.races'), 403);

        $race = Race::find($id);
        $race->name = $request->name;
        $race->slug = Str::slug($request->name);
        $race->save();

        alert('Se ha actualizado una carrera.');

        return response('', 204, [
            'Redirect-To' => url('admin/carreras/')
        ]);
    }

    public function delete($id)
    {
        abort_unless(Gate::allows('view.races') || Gate::allows('create.races'), 403);

        $race = Race::find($id);
        $race->delete();
        
        return response('', 204);

    }
}
