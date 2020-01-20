<?php

namespace App\Http\Controllers;

use App\Expense;
use App\Repositories\ExpenseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;

class ExpenseController extends Controller
{
    protected $model;

    public function __construct(Expense $expense)
    {
       // set the model
       $this->model = new ExpenseRepository($expense);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->model->all();
    }

    public function getByUser($id)
    {
        return $this->model->where('user_id', $id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|integer',
            'expense_category_id' => 'required|integer',
            'entry_date' => 'required|date',
            'user_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return ['error' => $validator->errors()];
        }

        // create record and pass in only fields that are fillable
        return $this->model->create($request->only($this->model->getModel()->fillable));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->model->show($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|integer',
            'expense_category_id' => 'required|integer',
            'entry_date' => 'required|date',
            'user_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return ['error' => $validator->errors()];
        }

        // update model and only pass in the fillable fields
        $this->model->update($request->only($this->model->getModel()->fillable), $id);

        return $this->model->find($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->model->delete($id);
    }

    /**
     * My Expenses Chart data
     */
    public function getChart($id)
    {
        $expenseChart = Expense::select(
            DB::raw('sum(amount) as sum'), 
            'expense_categories.name'
        )
        ->where('expenses.user_id', $id)
        ->leftJoin('expense_categories', 'expense_categories.id', '=', 'expenses.expense_category_id')
        ->groupBy('expense_category_id')
        ->get();

        $total = Expense::where('user_id', $id)->sum('amount');

        $values = array();
        $names = array();
        if ($total > 0) {
            foreach ($expenseChart as $chart) {
                array_push($values, round(($chart->sum/$total)*100, 2));
                array_push($names, $chart->name);
            }
        }

        $return = array('values' => $values, 'names' => $names, 'data' => $expenseChart);

        return $return;
    }
}
