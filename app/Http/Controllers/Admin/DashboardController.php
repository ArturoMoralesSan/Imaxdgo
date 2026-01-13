<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use App\Models\Service;
use App\Models\Expense;
use App\Models\TypeExpense;
use App\Models\Study;
use App\Models\Branch;
use App\Models\Doctor;
use DB;
class DashboardController extends Controller
{

    public function index()
    {
        $dateNow     = Carbon::now();
        $dateFormat  = $dateNow->format('Y-m-d');
        $string_date = date('d M');
        $start_date = request('start_date') != null ? request('start_date') : $dateFormat;
        $end_date   = request('end_date') != null ? \Request('end_date') : $dateFormat;


        if (!Auth::user()->isSuperAdmin()) {
            // Usuario normal: solo su sucursal
            $branches = Branch::with(['services' => function($q) use ($start_date, $end_date) {
                $q->whereBetween('date', [$start_date, $end_date]);
            }])->where('id',Auth::user()->branch_id)->whereHas('services', function($q) use ($start_date, $end_date){
                $q->whereBetween('date', [$start_date, $end_date]);
            })->get();

            return view('admin.dashboard', compact('branches'));
        }

        // -------------------- SUPERADMIN --------------------

        // ---------------- Servicios ----------------
        $servicesStats = DB::table('services')
            ->leftJoin('payment_service', 'services.id', '=', 'payment_service.service_id')
            ->whereBetween('services.date', [$start_date, $end_date])
            ->select(
                DB::raw('COUNT(DISTINCT services.id) as services_count'),
                DB::raw('SUM(payment_service.cost) as services_amount')
            )
            ->first();

        $servicesCount = $servicesStats->services_count ?? 0;
        $CostbyServices = $servicesStats->services_amount ?? 0;
        $ordersAll = $servicesCount;
        $ingreso = number_format($CostbyServices, 2, '.', ',');

        // ---------------- Gastos ----------------
        $expensesCount = Expense::whereBetween('date', [$start_date, $end_date])->sum('amount');
        $gasto = number_format($expensesCount, 2, '.', ',');

        // ---------------- Servicios por día ----------------
        $days = DB::table('services')
            ->join('payment_service','services.id','=','payment_service.service_id')
            ->whereBetween('services.date', [$start_date, $end_date])
            ->select(DB::raw("DATE_FORMAT(services.date,'%d %M') as day"), DB::raw("SUM(payment_service.cost) as total"))
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total','day');

        // ---------------- Servicios por método de pago ----------------
        $servicesPerPayments = DB::table('payment_service')
            ->join('services','payment_service.service_id','=','services.id')
            ->join('payments','payment_service.payment_id','=','payments.id')
            ->whereBetween('services.date', [$start_date, $end_date])
            ->select('payments.name', DB::raw('SUM(payment_service.cost) as total'))
            ->groupBy('payments.name')
            ->pluck('total','name');

        // ---------------- Estudios ----------------
        $studiesCount = DB::table('studies')
            ->join('service_study','studies.id','=','service_study.study_id')
            ->join('services','service_study.service_id','=','services.id')
            ->whereBetween('services.date', [$start_date, $end_date])
            ->groupBy('studies.id','studies.name')
            ->select('studies.id','studies.name', DB::raw('COUNT(services.id) as services_count'))
            ->orderByDesc('services_count')
            ->get();

        // ---------------- Gastos por tipo ----------------
        $expensesByType = DB::table('type_expenses')
            ->join('expenses','expenses.type_expense_id','=','type_expenses.id')
            ->whereBetween('expenses.date', [$start_date, $end_date])
            ->groupBy('type_expenses.id','type_expenses.name')
            ->select('type_expenses.id','type_expenses.name', DB::raw('SUM(expenses.amount) as total'))
            ->orderByDesc('total')
            ->get()
            ->map(fn($t) => [
                'id' => $t->id,
                'name' => $t->name,
                'expenses_sum_amount' => number_format($t->total,2,'.',',')
            ]);

        // ---------------- Branches con ingresos ----------------
        $branches = DB::table('branches')
            ->leftJoin('services', function($join) use ($start_date, $end_date) {
                $join->on('branches.id','=','services.branch_id')
                    ->whereBetween('services.date', [$start_date, $end_date]);
            })
            ->leftJoin('payment_service', 'services.id', '=', 'payment_service.service_id')
            ->groupBy('branches.id','branches.name')
            ->select(
                'branches.id',
                'branches.name',
                DB::raw('COUNT(DISTINCT services.id) as count_services'),
                DB::raw('SUM(payment_service.cost) as amount_services')
            )
            ->get()
            ->map(function ($branch) {
                return [
                    'id' => $branch->id,
                    'name' => $branch->name,
                    'count_services' => $branch->count_services ?? 0,
                    'amount_services' => number_format($branch->amount_services ?? 0, 2, '.', ','),
                    'amount_services_raw' => $branch->amount_services ?? 0,
                ];
            });

        // ---------------- Branches con gastos ----------------
        $branchesExpenses = DB::table('branches')
            ->leftJoin('expenses', function($join) use ($start_date, $end_date) {
                $join->on('branches.id','=','expenses.branch_id')
                    ->whereBetween('expenses.date', [$start_date, $end_date]);
            })
            ->groupBy('branches.id','branches.name')
            ->select(
                'branches.id',
                'branches.name',
                DB::raw('SUM(expenses.amount) as amount_expenses')
            )
            ->get()
            ->map(function ($branch) {
                return [
                    'id' => $branch->id,
                    'name' => $branch->name,
                    'amount_expenses' => number_format($branch->amount_expenses ?? 0, 2, '.', ','),
                    'amount_expenses_raw' => $branch->amount_expenses ?? 0,
                ];
            });

            // ---------------- Top Global Doctors ----------------
            $topGlobalDoctors = DB::table('services')
                ->join('doctors','services.doctor_id','=','doctors.id')
                ->whereBetween('services.date', [$start_date, $end_date])
                ->groupBy('doctors.id','doctors.name','doctors.last_name')
                ->select('doctors.id','doctors.name','doctors.last_name', DB::raw('COUNT(services.id) as total_services'))
                ->orderByDesc('total_services')
                ->limit(30)
                ->get();

            // ---------------- Top Global Doctors por Branch ----------------
            $topGlobalDoctorsByBranch = DB::table('services')
                ->join('doctors','services.doctor_id','=','doctors.id')
                ->join('branches','services.branch_id','=','branches.id')
                ->whereBetween('services.date', [$start_date, $end_date])
                ->select(
                    'branches.id as branch_id',
                    'branches.name as branch_name',
                    'doctors.id as doctor_id',
                    'doctors.name',
                    'doctors.last_name',
                    DB::raw('COUNT(services.id) as total_services')
                )
                ->groupBy(
                    'branches.id',
                    'branches.name',
                    'doctors.id',
                    'doctors.name',
                    'doctors.last_name'
                )
                ->orderBy('branch_id')
                ->orderByDesc('total_services')
                ->get()
                ->groupBy('branch_name');

            // ---------------- Doctores con días sin servicio ----------------
            $DoctorsDIs = DB::table('doctors')
                ->leftJoin('services','doctors.id','=','services.doctor_id')
                ->groupBy('doctors.id','doctors.name','doctors.last_name')
                ->select(
                    'doctors.id','doctors.name','doctors.last_name',
                    DB::raw('MAX(services.created_at) as last_service_date')
                )
                ->get()
                ->map(function($d) {
                    if ($d->last_service_date) {
                        \Carbon\Carbon::setLocale('es');
                        $daysWithoutService = \Carbon\Carbon::parse($d->last_service_date)->diffInDays(now());
                        $lastServiceHuman = \Carbon\Carbon::parse($d->last_service_date)->diffForHumans();
                    } else {
                        $daysWithoutService = PHP_INT_MAX;
                        $lastServiceHuman = 'No ha enviado';
                    }

                    return [
                        'id' => $d->id,
                        'name' => $d->name,
                        'last_name' => $d->last_name,
                        'last_service_date' => $lastServiceHuman,
                        'last_service_human' => $lastServiceHuman,
                        'days_without_service' => $daysWithoutService
                    ];
                })
                ->sortByDesc('days_without_service')
                ->take(60)
                ->values();

        return view('admin.dashboard-admin', compact(
            'DoctorsDIs','topGlobalDoctors','topGlobalDoctorsByBranch','branches',
            'studiesCount','servicesPerPayments','expensesCount','ordersAll',
            'servicesCount','CostbyServices','string_date','ingreso','gasto','days',
            'branchesExpenses','expensesByType'
        ));
    }



    /* public function index()
    {
        $dateNow     = Carbon::now();
        $dateFormat  = $dateNow->format('Y-m-d');
        $date        = $dateNow->locale('es');
        $string_date = $date->day.' ' .$date->monthName;
        $ServicesAmount = 0;
        
        if (!Auth::user()->isSuperAdmin()) {
            $start_date = \Request('start_date') != null ? \Request('start_date') : $dateFormat;
            $end_date   = \Request('end_date') != null ? \Request('end_date') : $dateFormat ;

        
            $branches = Branch::with(['services' => function($q) use ($start_date, $end_date) {
                $q->whereBetween('date', [$start_date, $end_date]);
            }])->where('id',Auth::user()->branch_id)->whereHas('services', function($q) use ($start_date, $end_date){
                $q->whereBetween('date', [$start_date, $end_date]);
            })->get();
            return view('admin.dashboard', compact('branches'));   

        } else {

            //$start_date = \Request('start_date') != null ? \Request('start_date') : $dateNow->subDays(0)->format('Y-m-d');
            $start_date = \Request('start_date') != null ? \Request('start_date') : $dateFormat;
            $end_date   = \Request('end_date') != null ? \Request('end_date') : $dateFormat ;

            $Servicesnow   = Service::where('date', $dateFormat);
            $servicesCount = 0;
            if($Servicesnow->get()->isEmpty()) {
                $Servicesnow = 0;
            } else {
                $servicesCount = $Servicesnow->count();
                
                foreach ($Servicesnow->get() as $service) {
                    $ServicesAmount =+ $ServicesAmount + $service->cost;
                }
            }           

            $ingreso = number_format(
                $ServicesAmount, 
                2, '.', ',');
                $gasto = number_format(
                    Expense::where('date', $dateFormat)
                    ->sum('amount'), 
                    2, '.', ','
            );
            
            $services  = Service::whereBetween('date', [$start_date, $end_date]);
            $expensesCount = Expense::whereBetween('date', [$start_date, $end_date])->sum('amount');

            $ordersAll = $services->count();
            $CostbyServices = 0;
            foreach ($services->get() as $service) {
                $CostbyServices =+ $CostbyServices + $service->cost;
            }

            $days = $services->orderBy('date')
            ->get()
            ->groupBy(function ($val) {
                $dateParse   = Carbon::parse($val->date);
                $date        = $dateParse->locale('es');
                return $date->day.' ' .$date->monthName;
            })->map(function ($service) {
                return $service->sum('cost');
            });

            $servicesPerPayments = [];
            foreach ($services->get() as $service) {
                foreach ($service->payments as $payment) {
                    $paymentMethod = $payment->name;

                    if (!isset($servicesPerPayments[$paymentMethod])) {
                        $servicesPerPayments[$paymentMethod] = 0;
                    }
    
                    $servicesPerPayments[$paymentMethod] += $payment->pivot->cost;
                }
            }
            $servicesPerPayments = collect($servicesPerPayments);
            
            
            $studiesCount = Study::whereHas('services', function($q) use ($start_date, $end_date){
                $q->whereBetween('date', [$start_date, $end_date]);
            })
            ->withCount([
                'services', 
                'services as services_count' => function ($query) use ($start_date, $end_date) {
                    $query->whereBetween('date', [$start_date, $end_date]);
                }])
            ->orderBy('services_count', 'desc')
            ->get();



            $expensesByType = TypeExpense::join('expenses', 'expenses.type_expense_id', '=', 'type_expenses.id')
                ->whereBetween('expenses.date', [$start_date, $end_date])
                ->groupBy(
                    'type_expenses.id',
                    'type_expenses.name',
                    'type_expenses.created_at',
                    'type_expenses.updated_at'
                )
                ->select(
                    'type_expenses.id',
                    'type_expenses.name',
                    'type_expenses.created_at',
                    'type_expenses.updated_at',
                    DB::raw('SUM(expenses.amount) as expenses_sum_amount')
                )
                ->orderBy('expenses_sum_amount', 'desc')
                ->get();

            
            
            $expensesByType->transform(function ($type) {
                $type->expenses_sum_amount = number_format($type->expenses_sum_amount, 2, '.', ',');
                return $type;
            });
        

            $branches = Branch::with(['services' => function($q) use ($start_date, $end_date) {
                $q->whereBetween('date', [$start_date, $end_date]);
            }])->whereHas('services', function($q) use ($start_date, $end_date){
                $q->whereBetween('date', [$start_date, $end_date]);
            })->get();

        
            $branchesExpenses = Branch::with(['expenses' => function($q) use ($start_date, $end_date) {
                $q->whereBetween('date', [$start_date, $end_date]);
            }])->whereHas('expenses', function($q) use ($start_date, $end_date){
                $q->whereBetween('date', [$start_date, $end_date]);
            })->get();


            $servicesData = Service::with(['doctor', 'branch'])
            ->whereBetween('date', [$start_date, $end_date])
            ->get()
            ->filter(fn($service) => $service->doctor !== null);

            // --- Top Global ---
            $topGlobalDoctors = $servicesData
            ->groupBy('doctor.id')
            ->map(function ($services) {
                $doctor = $services->first()->doctor;
                return [
                    'id' => $doctor->id,
                    'name' => $doctor->name . ' ' . $doctor->last_name,
                    'total_services' => $services->count(),
                    'doctor' => $doctor,
                ];
            })
            ->sortByDesc('total_services')
            ->take(30)
            ->values();

            // --- Top por cada sucursal ---
            $topGlobalDoctorsByBranch = $servicesData
                ->groupBy(fn($service) => $service->branch->name)
                ->map(function ($servicesInBranch) {
                    $doctorsGrouped = $servicesInBranch->groupBy('doctor.id')
                        ->map(function ($servicesByDoctor) {
                            $doctor = $servicesByDoctor->first()->doctor;
                            return [
                                'id' => $doctor->id,
                                'name' => $doctor->name . ' ' . $doctor->last_name,
                                'count_services' => $servicesByDoctor->count(),
                                'branch_id' => $servicesByDoctor->first()->branch->id,
                            ];
                        });
                    return $doctorsGrouped->sortByDesc('count_services')->take(30)->values();
                });

                $DoctorsDIs = Doctor::withCount('services')
                ->withMax('services', 'created_at') // services_max_created_at (puede ser null)
                ->get()
                ->map(function ($doctor) {
                    return [
                        'id' => $doctor->id,
                        'name' => $doctor->name,
                        'last_name' => $doctor->last_name,
                        'last_service_date' => $doctor->last_service_date, // Usa tu accessor
                        'days_without_service' => $doctor->services_max_created_at
                            ? Carbon::parse($doctor->services_max_created_at)->diffInDays(now())
                            : PHP_INT_MAX, // Para que los que nunca han enviado queden arriba
                    ];
                })
                ->sortByDesc('days_without_service') // Ordena del que más días lleva sin enviar
                ->take(60)
                ->values();


            return view('admin.dashboard-admin', compact('DoctorsDIs','topGlobalDoctors','topGlobalDoctorsByBranch','branches','studiesCount','servicesPerPayments','expensesCount','ordersAll', 'servicesCount', 'CostbyServices','string_date', 'ingreso', 'gasto', 'days', 'branchesExpenses', 'expensesByType'));   
            
        } 
    }*/ 
}
