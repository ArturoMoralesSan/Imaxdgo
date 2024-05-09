<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Branch;
use App\Models\TypeExpense;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\ExpenseRequest;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Auth;

class ExpenseController extends Controller
{
    public function index()
    {
        abort_unless(Gate::allows('view.expenses') || Gate::allows('create.expenses'), 403);
        
        $expenses = Expense::with('branch','type_expense')->orderBy('id', 'DESC');
        if (!Auth::user()->isSuperAdmin()) {
            $expenses = $expenses->where('branch_id', Auth::user()->branch_id);
        } 
        $expenses = $expenses->paginate(10);
        $expensesItems = Collect($expenses->items());
        return view('admin.gastos.index', compact('expenses', 'expensesItems'));   
    }

    public function create()
    {
        abort_unless(Gate::allows('view.expenses') || Gate::allows('create.expenses'), 403);
        if (!Auth::user()->isSuperAdmin()) {
            $branches = Branch::where('id', Auth::user()->branch_id)->pluck('name','id');
        } else {
            $branches = Branch::pluck('name','id');
        }
        
        $types_expense = TypeExpense::pluck('name','id');
        return view('admin.gastos.crear', compact('branches', 'types_expense'));   
    }

    public function save(ExpenseRequest $request)
    {
        abort_unless(Gate::allows('view.expenses') || Gate::allows('edit.expenses'), 403);
        
        $date = Carbon::createFromFormat('Y-m-d', $request->date);

        $expense = new Expense;
        $expense->date = $request->date;
        $expense->branch_id = $request->branch_id;
        $expense->type_expense_id = $request->expense_id;
        $expense->amount = $request->cost;
        $expense->person_name = $request->name;
        $expense->year = $date->year;
        $expense->month = $date->month;
        $expense->week = $date->week;

        $expense->save();

        alert('Se ha agregado un gasto.');

        return response('', 204, [
            'Redirect-To' => url('admin/gastos/')
        ]);
    }

    public function edit($id)
    {
        abort_unless(Gate::allows('view.expenses') || Gate::allows('edit.expenses'), 403);
        
        $expense = Expense::find($id);

        if (!Auth::user()->isSuperAdmin()) {
            $branches = Branch::where('id', Auth::user()->branch_id)->pluck('name','id');
        } else {
            $branches = Branch::pluck('name','id');
        }

        $types_expense = TypeExpense::pluck('name','id');
        return view('admin.gastos.editar', compact('expense', 'branches', 'types_expense'));
    }


    public function update(ExpenseRequest $request, $id)
    {
        abort_unless(Gate::allows('view.expenses') || Gate::allows('edit.expenses'), 403);


        $expense = Expense::find($id);
        $date = Carbon::createFromFormat('Y-m-d', $expense->date);

        $expense->branch_id = $request->branch_id;
        $expense->type_expense_id = $request->expense_id;
        $expense->amount = $request->cost;
        $expense->person_name = $request->name;
        $expense->year = $date->year;
        $expense->month = $date->month;
        $expense->week = $date->week;

        $expense->save();

        alert('Se ha actualizado un gasto.');

        return response('', 204, [
            'Redirect-To' => url('admin/gastos/')
        ]);
    }

    public function delete($id)
    {
        abort_unless(Gate::allows('view.expenses') || Gate::allows('create.expenses'), 403);

        $expense = Expense::find($id);
        $expense->delete();

        return response('', 204);

    }
}
