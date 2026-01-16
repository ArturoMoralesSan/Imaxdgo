<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
use App\Models\Service;
use App\Models\Expense;
use App\Models\Doctor;
use App\Models\Branch;
use App\Models\DailyClosure;
use Carbon\Carbon;
use Auth;
use App\Models\RaceRegistration;
use Illuminate\Support\Facades\DB;
use Luecano\NumeroALetras\NumeroALetras;
use Illuminate\Support\Facades\Mail;
use App\Mail\DailyClosureReportMail;

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


   public function pdfDoctor($id)
    {
        $start_date = \Request('start_date');
        $end_date   = \Request('end_date');

        $servicesQuery = Service::with(['doctor', 'studies', 'branch'])
            ->where('doctor_id', $id);

        // Solo aplica el filtro de fechas si ambas están presentes
        if ($start_date && $end_date) {
            $servicesQuery->whereBetween('date', [$start_date, $end_date]);
        }

        $services = $servicesQuery->get();

        $doctor = $services->first()?->doctor;
        $doctorName = $doctor ? "{$doctor->name} {$doctor->last_name}" : 'No disponible';

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

        // Formatea fechas solo si existen
        $start_date = $start_date ? Carbon::parse($start_date)->format('d/m/Y') : null;
        $end_date   = $end_date ? Carbon::parse($end_date)->format('d/m/Y') : null;

        $pdf = PDF::loadView('admin.pdf.doctorservice', compact(
            'totalServices', 'doctorName', 'studiesCount', 'start_date', 'end_date',
            'patients', 'branchesCount', 'lastPatientDate', 'lastPatientBranch'
        ));

        $pdf->setPaper('letter', 'portrait');
        return $pdf->stream('reporte-doctor.pdf', ['Attachment' => false]);
    }


    public function exportDoctorPdf(Request $request)
    {
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

        // Ejecutar consulta
        $doctors = $query->get();

        // Separar en dos colecciones
        $withServices = $doctors->filter(fn($d) => $d->count_services_raw > 0);
        $withoutServices = $doctors->filter(fn($d) => $d->count_services_raw == 0);

        // Generar PDF
        return Pdf::loadView('admin.pdf.exportdoctors', [
            'withServices'     => $withServices,
            'withoutServices'  => $withoutServices,
            'start_date'       => $start_date,
            'end_date'         => $end_date,
            'search'           => $search,
            'sort_by'          => $sortKey,
            'sort_dir'         => $sortAsc,
        ])->stream('reporte_doctores.pdf');
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

    public function closeDay()
    {
        $user     = Auth::user();
        $branchId = $user->branch_id;
        $date     = Carbon::now()->format('Y-m-d');

        if (DailyClosure::where('branch_id', $branchId)->where('date', $date)->exists()) {
            return response()->json([
                'message' => 'Esta sucursal ya tiene un cierre de hoy.'
            ], 422);
        }

        $services = Service::whereDate('date', $date)
            ->where('branch_id', $branchId)
            ->with('payments')
            ->get();

        $expenses = Expense::whereDate('date', $date)
            ->where('branch_id', $branchId)
            ->sum('amount');

        $totals = ['cash' => 0, 'card' => 0, 'transfer' => 0];

        foreach ($services as $service) {
            foreach ($service->payments as $payment) {
                match ($payment->key_name) {
                    'efectivo' => $totals['cash'] += $payment->pivot->cost,
                    'tarjeta-debito-credito' => $totals['card'] += $payment->pivot->cost,
                    'transferencia' => $totals['transfer'] += $payment->pivot->cost,
                };
            }
        }

        $total         = $totals['cash'] + $totals['card'] + $totals['transfer'] - $expenses;
        $totalDelivery = $totals['cash'] - $expenses;

        DailyClosure::create([
            'branch_id'      => $branchId,
            'date'           => $date,
            'cash_total'     => $totals['cash'],
            'card_total'     => $totals['card'],
            'transfer_total' => $totals['transfer'],
            'expenses'       => $expenses,
            'total'          => $total,
            'total_delivery' => $totalDelivery,
            'closed_at'      => now(),
        ]);

        $branchesToClose = Branch::where('ending', 1)->pluck('id');

        $closedTodayCount = DailyClosure::whereDate('date', $date)
            ->whereIn('branch_id', $branchesToClose)
            ->count();

        if ($closedTodayCount === $branchesToClose->count()) {

            $closures = DailyClosure::with('branch')
                ->whereDate('date', $date)
                ->whereIn('branch_id', $branchesToClose)
                ->get();

            Mail::to('admin@tudominio.com')
                ->send(new DailyClosureReportMail($closures, $date));
        }

        return response()->json([
            'message' => 'Day successfully closed'
        ]);
    }


        
 
}
