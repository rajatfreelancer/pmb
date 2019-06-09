<?php

namespace App\Http\Controllers\Admin;

use App\AccountTransaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;
use App\User;
use App\Account;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth.admin');
    }

    public function index()
    {
        $data['title'] = "Transactions";
        return view('admin.transactions.index',$data);
    }

    public function getData(Request $request)
    {
         $accounts = AccountTransaction::select('account_transactions.*');

       // echo '<pre>'; print_r($accounts->get());exit;
        $datatable = \DataTables::eloquent($accounts)
        ->filterColumn('create_user_name', function($query, $keyword){
            $query->where('create_user_id', $keyword);
        })
        ->filterColumn('paid_date', function($query, $keyword){
            $date = explode('&', $keyword);
            if (!empty($date)) {
                if ($date[0]) {
                    $start_date = date("Y-m-d", strtotime($date[0]));
                }else{
                    $start_date = date("Y-m-d");
                }
                if ($date[1]) {
                    $end_date = date("Y-m-d", strtotime($date[1]));
                }else{
                    $end_date = date("Y-m-d");
                }
                $query->whereBetween('paid_date', [$start_date, $end_date]);
            }
        })
        ->editColumn('id', function($row){
            return $row->id;
        })
        ->addColumn('transaction_id', function ($row) {
            return $row->transaction_id;
        })
        ->addColumn('create_user_name', function($row){
            return $row->createUser->name;
        })
        ->addColumn('member_id', function ($row) {
                return $row->account->user->text;
            })
            ->addColumn('applicant_name', function ($row) {
                return $row->account->user->name.' '.$row->account->user->last_name;
            })
            ->addColumn("paid_date", function ($row) {
                return date("Y-m-d", strtotime($row->paid_date));
            })
            ->addColumn("account_code", function ($row) {
                return $row->account->ori_account_number;
            })
            ->addColumn("amount", function ($row) {
                return $row->amount;
            })
            ->addColumn("type", function ($row) {
                return $row->getMethodOptions($row->method);
            })
            ->addColumn("actions", function ($row) {
                return '<a class="btn btn-primary" href='.route("admin.transactions.edit",$row->id).'>Edit</a>';
            });

        $datatable = $datatable->rawColumns(['actions']);
        $datatable = $datatable->make(true);
        return $datatable;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $id = $request->get('id');
        $customers = User::all();
        $accounts = Account::all();
        return view('admin.transactions.add',compact('id', 'customers', 'accounts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return $request->all();
        $model = new AccountTransaction();
        $validator = \Validator::make($request->all(), $model->rules());

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        \DB::beginTransaction();

        $model->setData($request);        
        $model->save();        

        \DB::commit();
        return redirect()->route('admin.transactions.index')->with('success', 'Transaction is successfully added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $transaction = AccountTransaction::find($id);
        return view('admin.transactions.edit',compact('transaction'));
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
        $model = AccountTransaction::find($id);
        $validator = \Validator::make($request->all(), $model->rules());

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        \DB::beginTransaction();

        $model->setData($request);        
        $model->save();        

        \DB::commit();
        return redirect()->route('admin.transactions.index')->with('success', 'Transaction is successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getAccountList(Request $request, $id)
    {

        $accounts = Account::with('user')->where('create_user_id', $id)->where('status', Account::STATUS_ACTIVE)
        ->get();        
        if ($request->q) {
            //$accounts = $accounts->where('text', $request->q);
            $value = $request->q;
            $accounts = $accounts->reject(function($element) use ($value) {
                $text = strtolower($element->text);
                $value = strtolower($value);
    return mb_strpos($text, $value) === false;
});
        }
        $accounts = $accounts->toArray();
        //echo "<pre>";print_r(array_values($accounts));exit;
        
        return response()->json(['data' => array_values($accounts), 'status' => 200]);        
    }

    public function export(Request $request, $type = "")
    {
        //echo "<pre>";print_r($request->all());exit;

        $data = AccountTransaction::with('createUser', 'account', 'account.user');

        if ($request->create_user_id) {
            $data = $data->where('create_user_id', $request->create_user_id);
        }

        if ($request->start_date && $request->end_date) {
            $data = $data->whereBetween('paid_date', [$request->start_date, $request->end_date]);
        }elseif ($request->start_date && !$request->end_date) {
            $data = $data->whereBetween('paid_date', [$request->start_date, date("Y-m-d H:i:s")]);
        }elseif (!$request->start_date && $request->end_date) {
            $data = $data->whereBetween('paid_date', [date("Y-m-d H:i:s"), $request->end_date]);
        }

        $data = $data->get()->toArray();

        $headers = [
            'Applicant Name',
            'Member Id',
            'Account Code',
            'Amount',
            'Type',
            'Paid Date',
            'Created By'                
        ];
        
        $array = [];
        foreach ($data as $value) {
            if ($type == "") {
             $array_value = [
                    $value['account']['user']['name'].''.$value['account']['user']['last_name'],
                    $value['account']['user']['text'],
                    $value['account']['ori_account_number'],
                    $value['amount'],         
                    AccountTransaction::getMethodOptions($value['method']),
                    date("Y-m-d", strtotime($value['paid_date'])),
                    $value['create_user']['name']
                ];
            }
            $array[] = array_combine($headers, $array_value);
        }

        return \Excel::create('accounts_transactions', function($excel) use ($array) {
            
            $excel->sheet('mySheet', function($sheet) use ($array)
            {
                $sheet->fromArray($array);
            });
            })->download('xls');
    }
}
