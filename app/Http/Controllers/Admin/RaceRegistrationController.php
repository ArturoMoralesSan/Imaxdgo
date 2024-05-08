<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RaceRegistrationRequest;
use Illuminate\Http\Request;
use App\Models\RaceRegistration;
use App\Models\Race;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class RaceRegistrationController extends Controller
{
    public function index()
    {
        abort_unless(Gate::allows('view.races') || Gate::allows('create.races'), 403);
        $registers = RaceRegistration::paginate(20);
        $registersItems = Collect($registers->items());
        return view('admin.registro.index', compact('registers', 'registersItems'));   
    }

    public function create()
    {
        abort_unless(Gate::allows('view.races') || Gate::allows('create.races'), 403);
        $races = Race::pluck('name','id');
        return view('admin.registro.crear', compact('races'));   
    }

    public function save(RaceRegistrationRequest $request)
    {
        abort_unless(Gate::allows('view.races') || Gate::allows('edit.races'), 403);
        
        $registration = new RaceRegistration;
        $registration->race_id = $request->race_id;
        $registration->name = $request->name;
        $registration->age = $request->age;
        $registration->sex = $request->sex;
        $registration->size = $request->size;
        $registration->cellphone = $request->cellphone;
        $registration->type = $request->type;
        $registration->cost = $request->cost;
        $registration->save();

        alert('Se ha agregado un participante.');

        return response('', 204, [
            'Redirect-To' => url('admin/carrera-participantes/')
        ]);
    }

    public function edit($id)
    {
        abort_unless(Gate::allows('view.races') || Gate::allows('edit.races'), 403);
        $register = RaceRegistration::find($id);
        $races = Race::pluck('name','id');
        return view('admin.registro.editar', compact('register', 'races'));
    }


    public function update(RaceRegistrationRequest $request, $id)
    {
        abort_unless(Gate::allows('view.races') || Gate::allows('edit.races'), 403);

        $registration = RaceRegistration::find($id);
        $registration->race_id = $request->race_id;
        $registration->name = $request->name;
        $registration->age = $request->age;
        $registration->sex = $request->sex;
        $registration->size = $request->size;
        $registration->cellphone = $request->cellphone;
        $registration->type = $request->type;
        $registration->cost = $request->cost;
        $registration->save();
        

        alert('Se ha actualizado un participante.');

        return response('', 204, [
            'Redirect-To' => url('admin/carrera-participantes/')
        ]);
    }

    public function delete($id)
    {
        abort_unless(Gate::allows('view.races') || Gate::allows('create.races'), 403);

        $race = RaceRegistration::find($id);
        $race->delete();
        
        return response('', 204);

    }
}
