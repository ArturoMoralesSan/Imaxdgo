<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
use App\Models\Service;
use App\Models\Expense;
use Carbon\Carbon;
use Auth;
use App\Models\RaceRegistration;

use Luecano\NumeroALetras\NumeroALetras;

class PdfController extends Controller
{
    
    public function pdf($id)
    {
        $dateNow    = Carbon::now();
        $dateFormat = $dateNow->format('Y-m-d');

        $start_date = \Request('start_date') != null ? \Request('start_date') : $dateFormat;
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
        
        $servicesPerPayments = [];
            foreach ($services as $service) {
                foreach ($service->payments as $payment) {
                    $paymentMethod = $payment->name;

                    if (!isset($servicesPerPayments[$paymentMethod])) {
                        $servicesPerPayments[$paymentMethod] = 0;
                    }
    
                    $servicesPerPayments[$paymentMethod] += $payment->pivot->cost;
                }
            }

            $servicesPerPayments = collect($servicesPerPayments);
            $serviceswithinCash  =  collect($servicesPerPayments);
            foreach ($serviceswithinCash as $key => $value) {
                if ($key == 'Efectivo') {
                    $serviceswithinCash->forget($key);
                }
            }

        $start_date = Carbon::createFromFormat('Y-m-d', $start_date)->format('d/m/Y');
        $end_date   = Carbon::createFromFormat('Y-m-d', $end_date)->format('d/m/Y');

        
        
        $pdf = PDF::loadView('admin.pdf.index', compact('services', 'expenses', 'start_date', 'end_date', 'servicesPerPayments', 'serviceswithinCash'));
        $pdf->setPaper('letter', 'portrait'); 
        return $pdf->stream('reporte-ingresos-sucursal.pdf',['Attachment' => false]);
        //return $pdf->download('reporte-ingresos-sucursal.pdf');   
    }

    public function pdfDoctors($id, $branch_id = null)
    {
        $dateNow    = Carbon::now();
        $dateFormat = $dateNow->format('Y-m-d');

        $start_date = \Request('start_date') ?? $dateFormat;
        $end_date   = \Request('end_date') ?? $dateFormat;

        if (!Auth::user()->isSuperAdmin()) {
            $branch_id = Auth::user()->branch_id;
        }

        $servicesQuery = Service::with(['doctor', 'studies', 'branch'])
            ->whereBetween('date', [$start_date, $end_date])
            ->where('doctor_id', $id);

        if ($branch_id) {
            $servicesQuery->where('branch_id', $branch_id);
        }

        $services = $servicesQuery->get();

        $doctor = $services->first()?->doctor;
        $doctorName = $doctor ? "{$doctor->name} {$doctor->last_name}" : 'No disponible';
        $branchName = ($branch_id && $services->isNotEmpty()) ? $services->first()->branch->name : null;

        // Lista plana: un renglón por estudio
        $patients = $services->flatMap(function ($service) {
            return $service->studies->map(function ($study) use ($service) {
                return [
                    'patient_name' => $service->patient,
                    'study_name' => $study->name,
                    'branch_name' => $service->branch->name ?? 'Sin sucursal',
                ];
            });
        });

        // Conteo de estudios
        $studiesCount = $patients
            ->groupBy('study_name')
            ->map(fn($group, $study) => ['study_name' => $study, 'count' => $group->count()])
            ->sortByDesc('count')
            ->values();

        // Conteo por sucursal (por paciente único)
        $branchesCount = $patients
            ->groupBy('branch_name')
            ->map(fn($group, $branch) => [
                'branch_name' => $branch,
                'count' => $group->unique('patient_name')->count()
            ])
            ->values();

        // Último paciente
        $lastService = $services->sortByDesc('date')->first();
        $lastPatientDate = optional($lastService)->date ? Carbon::parse($lastService->date)->format('d/m/Y') : 'N/A';
        $lastPatientBranch = optional($lastService->branch)->name ?? 'N/A';

        $totalServices = $services->count();
        $start_date = Carbon::parse($start_date)->format('d/m/Y');
        $end_date   = Carbon::parse($end_date)->format('d/m/Y');

        $pdf = PDF::loadView('admin.pdf.doctorservices', compact(
            'totalServices', 'doctorName', 'branchName', 'studiesCount', 'start_date', 'end_date',
            'patients', 'branchesCount', 'lastPatientDate', 'lastPatientBranch'
        ));

        $pdf->setPaper('letter', 'portrait');
        return $pdf->stream('reporte-ingresos-sucursal.pdf', ['Attachment' => false]);
    }


    public function pdfRace($id)
    {
        if (!Auth::user()->isSuperAdmin()) { 
            $id = Auth::user()->branch_id;
        }
        $registers = RaceRegistration::where('branch_id', $id)
        ->get();
        
        $pdf = PDF::loadView('admin.pdf.race', compact('registers'));
        $pdf->setPaper('letter', 'portrait'); 
        return $pdf->stream('reporte-carrera-sucursal.pdf',['Attachment' => false]);
        //return $pdf->download('reporte-ingresos-sucursal.pdf');   
    }

    public function pdfEgreso($id)
    {
        $dateNow    = Carbon::now();
        $dateFormat = $dateNow->format('Y-m-d');

        $start_date = \Request('start_date') != null ? \Request('start_date') : $dateFormat;
        $end_date   = \Request('end_date') != null ? \Request('end_date') : $dateFormat ;
        
        if (!Auth::user()->isSuperAdmin()) {
            $start_date = \Request('start_date') != null ? \Request('start_date') : $dateFormat;
            $end_date   = \Request('end_date') != null ? \Request('end_date') : $dateFormat ;

            $id = Auth::user()->branch_id;
        }
        
        $expenses = Expense::whereBetween('date', [$start_date, $end_date])
        ->orderBy('date')
        ->where('branch_id', $id)
        ->with('type_expense')
        ->get();
        
        $start_date = Carbon::createFromFormat('Y-m-d', $start_date)->format('d/m/Y');
        $end_date   = Carbon::createFromFormat('Y-m-d', $end_date)->format('d/m/Y');

        
        
        $pdf = PDF::loadView('admin.pdf.indexExpenses', compact('expenses', 'start_date', 'end_date'));
        $pdf->setPaper('letter', 'portrait'); 
        return $pdf->stream('reporte-gastos-sucursal.pdf',['Attachment' => false]);
        //return $pdf->download('reporte-gasto-sucursal.pdf');   
    }

    public function pdfGasto($id)
    {
        $dateNow    = Carbon::now();
        $dateFormat = $dateNow->format('Y-m-d');

        $start_date = \Request('start_date') != null ? \Request('start_date') : $dateNow->subDays(5)->format('Y-m-d');
        $end_date   = \Request('end_date') != null ? \Request('end_date') : $dateFormat ;
        $expenses = Expense::whereBetween('date', [$start_date, $end_date])
        ->orderBy('date')
        ->where('type_expense_id', $id)
        ->with('type_expense', 'branch')
        ->get();

        // Agrupar por la relación 'branch'
        $groupedExpenses = $expenses->groupBy('branch.name');
        $start_date = Carbon::createFromFormat('Y-m-d', $start_date)->format('d/m/Y');
        $end_date   = Carbon::createFromFormat('Y-m-d', $end_date)->format('d/m/Y');

        
        
        $pdf = PDF::loadView('admin.pdf.indexGastos', compact('expenses', 'start_date', 'end_date','groupedExpenses'));
        $pdf->setPaper('letter', 'portrait'); 
        return $pdf->stream('repote-gastos-recurrentes', ['Attachment' => false]); 
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
