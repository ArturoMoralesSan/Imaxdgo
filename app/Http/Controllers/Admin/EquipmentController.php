<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EquipmentRequest;
use App\Models\Equipment;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class EquipmentController extends Controller
{
    
    public function index()
    {
        abort_unless(Gate::allows('view.equipment') || Gate::allows('create.equipment'), 403);
        $equipment = Equipment::all();
        return view('admin.inventario.index', compact('equipment'));   
    }

    public function save(EquipmentRequest $request)
    {
        abort_unless(Gate::allows('view.equipment') || Gate::allows('edit.equipment'), 403);
        
        $equipment = new Equipment;
        
        $equipment->num_serie = $request->num_serie;
        $equipment->control_tag = $request->control_tag;
        $equipment->product = $request->product;
        $equipment->brand = $request->brand;
        $equipment->model = $request->model;
        $equipment->pu = $request->pu;
        $equipment->status = $request->status;
        $equipment->quantity = $request->quantity;
        $equipment->image = $request->image;

        $equipment->save();

        alert('Se ha agregado un equipo/material.');

        return response('', 204, [
            'Redirect-To' => url('admin/material/')
        ]);
    }

    public function edit($id)
    {
        abort_unless(Gate::allows('view.equipment') || Gate::allows('edit.equipment'), 403);
        $equipment = Equipment::find($id);

        return view('admin.inventario.editar', compact('equipment'));
    }


    public function update(EquipmentRequest $request, $id)
    {
        abort_unless(Gate::allows('view.equipment') || Gate::allows('edit.equipment'), 403);

        $equipment = Equipment::find($id);

        $equipment->num_serie = $request->num_serie;
        $equipment->control_tag = $request->control_tag;
        $equipment->product = $request->product;
        $equipment->brand = $request->brand;
        $equipment->model = $request->model;
        $equipment->pu = $request->pu;
        $equipment->status = $request->status;
        $equipment->quantity = $request->quantity;
        $equipment->image = $request->image;

        $equipment->save();

        alert('Se ha actualizado un equipo / material.');

        return response('', 204, [
            'Redirect-To' => url('admin/material/')
        ]);
    }

    public function delete($id)
    {
        abort_unless(Gate::allows('view.equipment') || Gate::allows('create.equipment'), 403);

        Equipment::find($id)->delete();
        
        return response('', 204);

    }
}
