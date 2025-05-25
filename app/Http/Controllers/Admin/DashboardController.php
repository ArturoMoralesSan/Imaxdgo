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
                ->groupBy('type_expenses.id')
                ->select('type_expenses.*', DB::raw('SUM(expenses.amount) as expenses_sum_amount'))
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
            ->take(5);

            $doctorIds = $topGlobalDoctors->pluck('id');

            $topGlobalDoctorsByBranch = $servicesData
                ->filter(fn($service) => $doctorIds->contains($service->doctor_id))
                ->groupBy(function ($service) {
                    return $service->branch->name;
                })
                ->map(function ($servicesInBranch) {
                    return $servicesInBranch->groupBy('doctor.id')
                        ->map(function ($servicesByDoctor) {
                            $doctor = $servicesByDoctor->first()->doctor;
                            return [
                                'id' => $doctor->id,
                                'name' => $doctor->name . ' ' . $doctor->last_name,
                                'count_services' => $servicesByDoctor->count(),
                                'branch_id' => $servicesByDoctor->first()->branch->id,
                            ];
                        });
                });


            return view('admin.dashboard-admin', compact('topGlobalDoctors','topGlobalDoctorsByBranch','branches','studiesCount','servicesPerPayments','expensesCount','ordersAll', 'servicesCount', 'CostbyServices','string_date', 'ingreso', 'gasto', 'days', 'branchesExpenses', 'expensesByType'));   
            
        } 
    }
}
