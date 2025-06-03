<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Branch;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\DoctorRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class DoctorController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(Gate::allows('view.services') || Gate::allows('create.services'), 403);

        $search     = $request->input('search');
        $sortKey    = $request->input('sort_by', 'last_name');
        $sortAsc    = $request->input('sort_dir', 'asc');
        $start_date = $request->input('start_date');
        $end_date   = $request->input('end_date');

        $query = Doctor::query();

        // Subconsulta para la última fecha de servicio
        $lastServiceDateSubquery = DB::table('services')
            ->select('created_at')
            ->whereColumn('services.doctor_id', 'doctors.id')
            ->when($start_date && $end_date, function ($sub) use ($start_date, $end_date) {
                $sub->whereBetween('created_at', [$start_date, $end_date]);
            })
            ->orderBy('created_at', 'desc')
            ->limit(1);

        // Subconsulta para contar servicios
        $countServicesSubquery = DB::table('services')
            ->selectRaw('COUNT(*)')
            ->whereColumn('services.doctor_id', 'doctors.id')
            ->when($start_date && $end_date, function ($sub) use ($start_date, $end_date) {
                $sub->whereBetween('created_at', [$start_date, $end_date]);
            });

        $query->select('doctors.*')
            ->addSelect([
                'last_service_date_raw' => $lastServiceDateSubquery,
                'count_services_raw' => $countServicesSubquery,
            ]);

        $query->leftJoin('branches', 'doctors.branch_id', '=', 'branches.id')
      ->addSelect('branches.name as branch_name');


        // Filtro por fecha: mostrar solo doctores con servicios en el rango
        if ($start_date && $end_date) {
            $query->whereExists(function ($sub) use ($start_date, $end_date) {
                $sub->select(DB::raw(1))
                    ->from('services')
                    ->whereColumn('services.doctor_id', 'doctors.id')
                    ->whereBetween('created_at', [$start_date, $end_date]);
            });
        }

        // Filtro de búsqueda
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

        // Paginación y query strings
        $doctors = $query->paginate(100)->appends([
            'sort_by' => $sortKey,
            'sort_dir' => $sortAsc ? 'asc' : 'desc',
            'search' => $search,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);

        return view('admin.doctores.index', compact('doctors', 'sortKey', 'sortAsc', 'search', 'start_date', 'end_date'));
    }




    public function save(DoctorRequest $request)
    {
        abort_unless(Gate::allows('view.services') || Gate::allows('edit.services'), 403);

        $branchId = Auth::user()->branch_id;  

        $doctor = new Doctor;
        $doctor->name = $request->name;
        $doctor->last_name = $request->last_name;
        $doctor->address = $request->address;
        $doctor->cp = $request->cp;
        $doctor->email = $request->email;
        $doctor->tel = $request->tel;
        $doctor->branch_id = $branchId;
        $doctor->save();

        alert('Se ha agregado un doctor.');

        return response('', 204, [
            'Redirect-To' => url('admin/doctores/')
        ]);
    }

    public function edit($id)
    {
        abort_unless(Gate::allows('view.services') || Gate::allows('edit.services'), 403);
        $branches = Branch::pluck('name','id');
        $doctor = Doctor::find($id);
        return view('admin.doctores.editar', compact('doctor', 'branches'));
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

        if($doctor->branch_id == null)
        {
            $doctor->branch_id = $request->branch_id;
        }

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
