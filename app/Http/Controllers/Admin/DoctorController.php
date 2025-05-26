<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\DoctorRequest;
use Illuminate\Support\Str;

class DoctorController extends Controller
{
    public function index()
    {
        abort_unless(Gate::allows('view.services') || Gate::allows('create.services'), 403);
        $search = \Request('search');

        $doctors = Doctor::orderBy('created_at','DESC');;

        $terms = explode(' ', $search);
        $doctors = $doctors->where(function ($query) use ($terms) {
            foreach ($terms as $term) {
                $query->where(function ($q) use ($term) {
                    $q->where('name', 'LIKE', '%'.$term.'%')
                    ->orWhere('last_name', 'LIKE', '%'.$term.'%');
                });
            }
        });

        $doctors = $doctors->paginate(20);

        $doctorsItems = Collect($doctors->items());

        return view('admin.doctores.index', compact('doctors', 'doctorsItems'));   
    }

    public function save(DoctorRequest $request)
    {
        abort_unless(Gate::allows('view.services') || Gate::allows('edit.services'), 403);
        
        $doctor = new Doctor;
        $doctor->name = $request->name;
        $doctor->last_name = $request->last_name;
        $doctor->address = $request->address;
        $doctor->cp = $request->cp;
        $doctor->email = $request->email;
        $doctor->tel = $request->tel;
        $doctor->save();

        alert('Se ha agregado un doctor.');

        return response('', 204, [
            'Redirect-To' => url('admin/doctores/')
        ]);
    }

    public function edit($id)
    {
        abort_unless(Gate::allows('view.services') || Gate::allows('edit.services'), 403);
        $doctor = Doctor::find($id);
        return view('admin.doctores.editar', compact('doctor'));
    }


    public function update(DoctorRequest $request, $id)
    {
        abort_unless(Gate::allows('view.services') || Gate::allows('edit.services'), 403);

        $doctor = Doctor::find($id);
        $doctor->name = $request->name;
        $doctor->last_name = $request->last_name;
        $doctor->address = $request->address;
        $doctor->cp = $request->cp;
        $doctor->email = $request->email;
        $doctor->tel = $request->tel;
        $doctor->save();

        alert('Se ha actualizado un doctor.');

        return response('', 204, [
            'Redirect-To' => url('admin/doctores/')
        ]);
    }

    public function delete($id)
    {
        abort_unless(Gate::allows('view.services') || Gate::allows('create.services'), 403);

        $doctor = Doctor::find($id);
        $doctor->delete();

        return response('', 204);

    }
}
