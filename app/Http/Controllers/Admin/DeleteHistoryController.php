<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\SolicitudEliminarRegistroMail;
use Illuminate\Support\Facades\Mail;
use App\Models\DeleteHistory;
use App\Models\Expense;
use App\Models\Service;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Auth;


class DeleteHistoryController extends Controller
{
     public function index()
    {
        abort_unless(Gate::allows('view.roles') || Gate::allows('create.roles'), 403);
        $requests = DeleteHistory::all();

        return view('admin.peticiones.index', compact('requests'));   
    }

    public function approve($id)
    {
        $delete = DeleteHistory::findOrFail($id);

        if ($delete->type == 'gasto') {
            $data = Expense::find($delete->record_id);
        } elseif($delete->type == 'servicio') {
            $data = Service::find($delete->record_id);
        }

        $data->delete_approved = true;
        $data->save();

        $delete->status = 'aprobado';
        $delete->save();

        return response()->json(['success' => true], 200);
    }

    public function approveEmail($id)
    {
        $delete = DeleteHistory::find($id);
        if($delete) {

            if ($delete->type == 'gasto') {
                $data = Expense::find($delete->record_id);
            } elseif($delete->type == 'servicio') {
                $data = Service::find($delete->record_id);
            }

            $data->delete_approved = true;
            $data->save();

            $delete->status = 'aprobado';
            $delete->save();

            return view('admin.peticiones.aprobar', compact('delete'));
        } else {
            return view('admin.peticiones.error');
        }
    }

    public function requestDelete($id, $tipo)
    {
        abort_unless(Gate::allows('view.expenses') || Gate::allows('create.expenses'), 403);
        
        if ($tipo == 'gasto') {
            $data = Expense::with('branch')->where('id',$id)->first();
            $data->display_name = $data->person_name;
        } elseif($tipo == 'servicio') {
            $data = Service::with('branch')->where('id',$id)->first();
            $data->display_name = $data->patient;

        }

        return view('admin.peticiones.crear', compact('data','id', 'tipo'));   
    }

    public function requestDeleteSend(Request $request, $id, $tipo)
    {
        abort_unless(Gate::allows('view.expenses') || Gate::allows('create.expenses'), 403);

        if ($tipo == 'gasto') {
            $data = Expense::find($id);
            $amount =  $data->amount;
        } elseif($tipo == 'servicio') {
            $data = Service::find($id);
            $amount = $data->cost;
        }

        $data->delete_requested = true;
        $data->save();

        $delete = new DeleteHistory;
        $delete->record_id = $id;
        $delete->type = $tipo;
        $delete->name = $request->name;
        $delete->branch = $request->branch;
        $delete->reason = $request->reason;
        $delete->value = $amount;
        $delete->deleted_by = Auth::user()->name . ' ' . Auth::user()->last_name;
        $delete->save();

        $destinatario = 'adrianterronesg@gmail.com';

        Mail::to($destinatario)->send(new SolicitudEliminarRegistroMail($delete));

        if ($tipo == 'gasto') {
            return response('', 204, [
                'Redirect-To' => url('admin/gastos/')
            ]);
        } elseif($tipo == 'servicio') {
            return response('', 204, [
                'Redirect-To' => url('admin/servicios/')
            ]);
        }
    }
}
