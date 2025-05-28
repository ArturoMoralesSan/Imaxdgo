<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\DoctorRequest;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;


class DoctorController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(Gate::allows('view.services') || Gate::allows('create.services'), 403);

        $search = $request->input('search');
        $sortKey = $request->input('sort_by', 'last_name'); // Nota: en URL se usa "sort_by"
        $sortAsc = $request->input('sort_dir', 'asc');      // Nota: en URL se usa "sort_dir"

        $query = Doctor::query();

        // Subconsulta para fecha último servicio
        $lastServiceDateSubquery = DB::table('services')
            ->select('created_at')
            ->whereColumn('services.doctor_id', 'doctors.id')
            ->orderBy('created_at', 'desc')
            ->limit(1);

        // Subconsulta para contar servicios
        $countServicesSubquery = DB::table('services')
            ->selectRaw('COUNT(*)')
            ->whereColumn('services.doctor_id', 'doctors.id');

        $query->select('doctors.*')
            ->addSelect([
                'last_service_date_raw' => $lastServiceDateSubquery,
                'count_services_raw' => $countServicesSubquery,
            ]);

        // Búsqueda
        if ($search) {
            $terms = explode(' ', $search);
            $query->where(function ($q) use ($terms) {
                foreach ($terms as $term) {
                    $q->where(function ($q2) use ($term) {
                        $q2->where('name', 'LIKE', '%'.$term.'%')
                        ->orWhere('last_name', 'LIKE', '%'.$term.'%');
                    });
                }
            });
        }

        // Ordenamiento
        if ($sortKey === 'last_service_date') {
            $query->orderBy('last_service_date_raw', $sortAsc);
        } elseif ($sortKey === 'count_services') {
            $query->orderBy('count_services_raw', $sortAsc);
        } else {
            $query->orderBy($sortKey, $sortAsc);
        }

        $doctors = $query->paginate(20);

        return view('admin.doctores.index', compact('doctors', 'sortKey', 'sortAsc', 'search'));
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
