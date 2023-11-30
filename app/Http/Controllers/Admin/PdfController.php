<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
use App\Models\Service;
use App\Models\Expense;
use Carbon\Carbon;
use Auth;
use Luecano\NumeroALetras\NumeroALetras;

class PdfController extends Controller
{
    
    public function pdf($id)
    {
        $dateNow    = Carbon::now();
        $dateFormat = $dateNow->format('Y-m-d');

        $start_date = \Request('start_date') != null ? \Request('start_date') : $dateNow->subDays(5)->format('Y-m-d');
        $end_date   = \Request('end_date') != null ? \Request('end_date') : $dateFormat ;
        
        if (!Auth::user()->isSuperAdmin()) {
            $start_date = \Request('start_date') != null ? \Request('start_date') : $dateFormat;
            $end_date   = \Request('end_date') != null ? \Request('end_date') : $dateFormat ;

            $id = Auth::user()->branch_id;
        }
        $services = Service::whereBetween('date', [$start_date, $end_date])
        ->orderBy('date')
        ->where('branch_id', $id)
        ->get();

        $expenses = Expense::whereBetween('date', [$start_date, $end_date])
        ->orderBy('date')
        ->where('branch_id', $id)
        ->with('type_expense')
        ->get();
        
        
        

        $start_date = Carbon::createFromFormat('Y-m-d', $start_date)->format('d/m/Y');
        $end_date   = Carbon::createFromFormat('Y-m-d', $end_date)->format('d/m/Y');

        
        
        $pdf = PDF::loadView('admin.pdf.index', compact('services', 'expenses', 'start_date', 'end_date'));

        return $pdf->download('report.pdf');   
    }

    public function pdfnote($id)
    {
        $dateNow    = Carbon::now();
        $dateFormat = $dateNow->format('Y-m-d');
        $service    = Service::find($id);
        
        $formatter = new NumeroALetras();
        $formatter->conector = 'Y';
        $service->letter = $formatter->toMoney($service->cost, 2, 'pesos', 'centavos');
        
        $service->day = Carbon::createFromFormat('Y-m-d', $service->date)->format('d');
        $pdf = PDF::loadView('admin.pdf.note', compact('service'));
        $pdf->setPaper('letter', 'portrait'); 
        return $pdf->stream();
        //return $pdf->download('report.pdf');   
    }
}
