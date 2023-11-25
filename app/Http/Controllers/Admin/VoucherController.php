<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VoucherRequest;
use App\Models\Voucher;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        abort_unless(Gate::allows('view.voucher') || Gate::allows('create.voucher'), 403);
        $voucher = Voucher::all();
        return view('admin.vales.index', compact('voucher'));   
    }

    public function save(VoucherRequest $request)
    {
        abort_unless(Gate::allows('view.voucher') || Gate::allows('edit.voucher'), 403);
        
        $voucher = new Voucher;

        $voucher->subject = $request->subject;
        $voucher->laboratory = $request->laboratory;
        $voucher->return_date = $request->return_date;
        $voucher->status = $request->status;

        $voucher->save();

        alert('Se ha agregado un vale de material.');

        return response('', 204, [
            'Redirect-To' => url('admin/vale/')
        ]);
    }

    public function edit($id)
    {
        abort_unless(Gate::allows('view.voucher') || Gate::allows('edit.voucher'), 403);
        $voucher = Voucher::find($id);

        return view('admin.vales.editar', compact('voucher'));
    }


    public function update(VoucherRequest $request, $id)
    {
        abort_unless(Gate::allows('view.voucher') || Gate::allows('edit.voucher'), 403);

        $voucher = Voucher::find($id);

        $voucher->subject = $request->subject;
        $voucher->laboratory = $request->laboratory;
        $voucher->return_date = $request->return_date;
        $voucher->status = $request->status;
        
        $voucher->save();

        alert('Se ha actualizado un vale de material.');

        return response('', 204, [
            'Redirect-To' => url('admin/vale/')
        ]);
    }

    public function delete($id)
    {
        abort_unless(Gate::allows('view.voucher') || Gate::allows('create.voucher'), 403);

        Voucher::find($id)->delete();
        
        return response('', 204);

    }
}
