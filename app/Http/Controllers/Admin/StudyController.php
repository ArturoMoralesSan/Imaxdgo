<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Study;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StudyRequest;
use Illuminate\Support\Str;

class StudyController extends Controller
{
    public function index()
    {
        abort_unless(Gate::allows('view.studies') || Gate::allows('create.studies'), 403);
        $studies = Study::all();
        return view('admin.estudios.index', compact('studies'));   
    }

    public function save(StudyRequest $request)
    {
        abort_unless(Gate::allows('view.studies') || Gate::allows('edit.studies'), 403);
        
        $study = new Study;
        $study->name = $request->name;
        $study->key_name = Str::slug($request->name);
        $study->save();

        alert('Se ha agregado un estudio.');

        return response('', 204, [
            'Redirect-To' => url('admin/estudios/')
        ]);
    }

    public function edit($id)
    {
        abort_unless(Gate::allows('view.studies') || Gate::allows('edit.studies'), 403);
        $study = Study::find($id);
        return view('admin.estudios.editar', compact('study'));
    }


    public function update(StudyRequest $request, $id)
    {
        abort_unless(Gate::allows('view.studies') || Gate::allows('edit.studies'), 403);

        $study = Study::find($id);
        $study->name = $request->name;
        $study->key_name = Str::slug($request->name);
        $study->save();

        alert('Se ha actualizado un estudio.');

        return response('', 204, [
            'Redirect-To' => url('admin/estudios/')
        ]);
    }

    public function delete($id)
    {
        abort_unless(Gate::allows('view.studies') || Gate::allows('create.studies'), 403);

        $study = Study::find($id);
        $study->delete();

        return response('', 204);

    }
}
