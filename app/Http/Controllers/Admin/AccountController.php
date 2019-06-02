<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Account;
use App\Admin;
use App\AccountTransaction;
use App\Http\Models\SystemFile;


class AccountController extends Controller
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
        //$data['accounts'] = Account::with('user')->orderBy('id')->get();
        $data['title'] = "DD/RD Accounts";
        return view('admin.accounts.index',$data);
    }

    public function getData(Request $request)
    {
        $accounts = Account::select('accounts.*')->with('user', 'createUser')->whereIn('account_type', [Account::TYPE_DD, Account::TYPE_RD]);

       // echo '<pre>'; print_r($accounts->get());exit;
        $datatable = \DataTables::eloquent($accounts)
        ->filterColumn('create_user_name', function($query, $keyword){
            $query->where('create_user_id', $keyword);
        })
        ->filterColumn('policy_date', function($query, $keyword){
            $date = explode('&', $keyword);
            $start_date = date("Y-m-d", strtotime($date[0]));
            $end_date = date("Y-m-d", strtotime($date[1]));
            $query->whereBetween('policy_date', [$start_date, $end_date]);
        })
        ->addColumn('create_user_name', function($row){
            return $row->createUser->name;
        })
            ->addColumn('applicant_name', function ($row) {
                return $row->user->name.' '.$row->user->last_name;
            })
            ->addColumn("policy_date", function ($row) {
                return date("Y-m-d", strtotime($row->policy_date));
            })
            ->addColumn("policy_code", function ($row) {
                return $row->ori_account_number;
            })
            ->addColumn("amount", function ($row) {
                return $row->denomination_amount;
            })
             ->addColumn("term", function ($row) {
                return $row->term;
            })
            ->addColumn("account_type", function ($row) {
                return $row->getTypeOptions($row->account_type);
            })
            ->addColumn("maturity_date", function ($row) {
                return $row->maturity_date;
            })
            ->addColumn("account_type_mode", function($row){
              return $row->getAccountTypeModeOptions($row->account_type);  
            })
            ->addColumn("installment_number", function ($row) {
                return $row->installment_number;
            })
            ->addColumn("paid_installment", function ($row) {
                return $row->paid_installment;
            })
            ->addColumn("unpaid_installment", function ($row) {
                return $row->unpaid_installment;
            })
            ->addColumn("paid_amount", function ($row) {
                return $row->paid_amount;
            })
            ->addColumn("required_amount", function ($row) {
                return $row->payable_amount;
            })
            ->addColumn("actions", function ($row) {
                return '<a class="btn btn-primary" href='.route("admin.print.passbook",$row->id).'>print</a><a class="btn btn-primary" id="print_transactions_'.$row->id.'" href='.route("admin.print.transactions",$row->id).'>Print transactions</a><a class="btn btn-primary" href='.route("admin.transactions.create",['id'=> $row->id]).'>Add Installment</a><a class="btn btn-primary" href='.route("admin.accounts.mature",$row->id).'>Mature</a><a class="btn btn-primary" href='.route("admin.accounts.edit",$row->id).'>Edit</a>';
            });
        $datatable = $datatable->rawColumns(['actions', 'item_tags']);

        $datatable = $datatable->make(true);
        return $datatable;
        //return $datatable;
    }

    public function accountsFd()
    {
        $data['title'] = "FD Accounts";
        $data['type'] = Account::TYPE_FD;
        $data['url'] = route('admin.ajax.get.data.fd');
        return view('admin.accounts.index',$data);
    }

    public function getDataFD(Request $request)
    {
        $accounts = Account::select('accounts.*')->where('account_type', [Account::TYPE_FD]);

       // echo '<pre>'; print_r($accounts->get());exit;
        $datatable = \DataTables::eloquent($accounts)
            ->addColumn('applicant_name', function ($row) {
                return $row->user->name.' '.$row->user->last_name;
            })
            ->addColumn('create_user_name', function($row){
            return $row->createUser->name;
        })
            ->addColumn("policy_date", function ($row) {
                return date("Y-m-d", strtotime($row->policy_date));
            })
            ->addColumn("policy_code", function ($row) {
                return $row->ori_account_number;
            })
            ->addColumn("amount", function ($row) {
                return $row->denomination_amount;
            })
             ->addColumn("term", function ($row) {
                return $row->term;
            })
            ->addColumn("account_type", function ($row) {
                return $row->getTypeOptions($row->account_type);
            })
            ->addColumn("status", function ($row) {
                return $row->getStatusOptions($row->status);
            })
            ->addColumn("maturity_date", function ($row) {
                return $row->maturity_date;
            })
            ->addColumn("account_type_mode", function($row){
              return $row->getAccountTypeModeOptions($row->account_type);  
            })
            ->addColumn("installment_number", function ($row) {
                return $row->installment_number;
            })
            ->addColumn("paid_installment", function ($row) {
                return $row->paid_installment;
            })
            ->addColumn("unpaid_installment", function ($row) {
                return $row->unpaid_installment;
            })
            ->addColumn("paid_amount", function ($row) {
                return $row->paid_amount;
            })
            ->addColumn("required_amount", function ($row) {
                return $row->payable_amount;
            })
            ->addColumn("actions", function ($row) {
                if($row->status == Account::STATUS_CLOSED) {
                    return "No action";
                }
                return '<a class="btn btn-primary" href='.route("admin.accounts.mature",$row->id).'>Mature</a><a class="btn btn-primary" href='.route("admin.print.fd",$row->id).'>Print</a><a class="btn btn-primary" id="print_transactions_'.$row->id.'" href='.route("admin.print.transactions",$row->id).'>Print Transactions</a><a class="btn btn-primary" href='.route("admin.accounts.edit",$row->id).'>Edit</a>';
            });
        $datatable = $datatable->rawColumns(['actions', 'item_tags']);

        $datatable = $datatable->make(true);
        return $datatable;
        //return $datatable;
    }


    public function accountsSavings()
    {
        $data['title'] = "Savings Accounts";
        $data['type'] = Account::TYPE_SAVINGS;
        $data['url'] = route('admin.ajax.get.data.savings');    
        return view('admin.accounts.index',$data);
    }

     public function getDataSavings(Request $request)
    {
        $accounts = Account::select('accounts.*')->where('account_type', [Account::TYPE_SAVINGS]);

       // echo '<pre>'; print_r($accounts->get());exit;
        $datatable = \DataTables::eloquent($accounts)
            ->addColumn('applicant_name', function ($row) {
                return $row->user->name.' '.$row->user->last_name;
            })
            ->addColumn('create_user_name', function($row){
            return $row->createUser->name;
        })
            ->addColumn("policy_date", function ($row) {
                return date("Y-m-d", strtotime($row->policy_date));
            })
            ->addColumn("policy_code", function ($row) {
                return $row->ori_account_number;
            })
            ->addColumn("status", function ($row) {
                return $row->getStatusOptions($row->status);
            })
            ->addColumn("balance", function ($row) {
                return $row->transactions->sum('amount');
            })
            ->addColumn("actions", function ($row) {
                return '<a class="btn btn-primary" href='.route("admin.print.passbook",$row->id).'>print</a><a class="btn btn-primary" href='.route("admin.print.transactions",$row->id).'>Print Transactions</a><a class="btn btn-primary" href='.route("admin.transactions.create",['id'=> $row->id]).'>Add Transaction</a><a class="btn btn-primary" href='.route("admin.accounts.edit",$row->id).'>Edit</a>';
            });
        $datatable = $datatable->rawColumns(['actions']);

        $datatable = $datatable->make(true);
        return $datatable;
        //return $datatable;
    }
    
    public function accountsMis()
    {
        $data['title'] = "MIS Accounts";
        $data['type'] = Account::TYPE_MONTHLY_INCOME;
        $data['url'] = route('admin.ajax.get.data.mis');
        return view('admin.accounts.index',$data);
    }

    public function getDataMis(Request $request)
    {
        $accounts = Account::select('accounts.*')->where('account_type', [Account::TYPE_MONTHLY_INCOME]);

       // echo '<pre>'; print_r($accounts->get());exit;
        $datatable = \DataTables::eloquent($accounts)
            ->addColumn('applicant_name', function ($row) {
                return $row->user->name.' '.$row->user->last_name;
            })
            ->addColumn("policy_date", function ($row) {
                return date("Y-m-d", strtotime($row->policy_date));
            })
            ->addColumn('create_user_name', function($row){
            return $row->createUser->name;
        })
            ->addColumn("policy_code", function ($row) {
                return $row->ori_account_number;
            })
            ->addColumn("amount", function ($row) {
                return $row->denomination_amount;
            })
             ->addColumn("term", function ($row) {
                return $row->term;
            })
            ->addColumn("account_type", function ($row) {
                return $row->getTypeOptions($row->account_type);
            })
            ->addColumn("maturity_date", function ($row) {
                return $row->maturity_date;
            })
            ->addColumn("account_type_mode", function($row){
              return $row->getAccountTypeModeOptions($row->account_type);  
            })
            ->addColumn("installment_number", function ($row) {
                return $row->installment_number;
            })
            ->addColumn("paid_installment", function ($row) {
                return $row->paid_installment;
            })
            ->addColumn("unpaid_installment", function ($row) {
                return $row->unpaid_installment;
            })
            ->addColumn("paid_amount", function ($row) {
                return $row->paid_amount;
            })
            ->addColumn("required_amount", function ($row) {
                return $row->payable_amount;
            })
            ->addColumn("actions", function ($row) {
                return '<a class="btn btn-primary" href='.route("admin.print.passbook",$row->id).'>print</a><a class="btn btn-primary" href='.route("admin.transactions.create",['id'=> $row->id]).'>Add Installment</a><a class="btn btn-primary" href='.route("admin.accounts.edit",$row->id).'>Edit</a>';
            });
        $datatable = $datatable->rawColumns(['actions', 'item_tags']);

        $datatable = $datatable->make(true);
        return $datatable;
        //return $datatable;
    }

    public function mature($id)
    {
        $data['account'] = Account::find($id);
        $data['title'] = "Mature Account";
        return view('admin.accounts.mature',$data);
    }

    public function matureStore(Request $request, $id)
    {
        try{
            \DB::beginTransaction();
             $account = Account::find($id);
            if ($request->transfer_to_amount) {
                if ($request->transfer_to_amount > $request->actual_maturity_amount) {
                    return redirect()->back()->with('error', "Transfered amount can't be greater than maturity_amount");
                }
            }

           
            $account->actual_maturity_amount = $request->actual_maturity_amount;
            $account->actual_interest_rate = $request->actual_interest_rate;
            $account->actual_maturity_date = $request->actual_maturity_date;
            $account->remarks = $request->remarks;
            $account->status = Account::STATUS_CLOSED;
            $account->save();

            if ($request->transfer_to) {
                $savings_account = Account::find($request->transfer_to)->where('user_id', $account->user_id)->first();

                if ($savings_account) {
                    $transaction = new AccountTransaction();                    
                    $transaction->amount = $request->transfer_to_amount ? $request->transfer_to_amount : $account->actual_maturity_amount;
                    $transaction->account_id = $savings_account->id;
                    $transaction->method = AccountTransaction::METHOD_CREDIT; 
                    $transaction->paid_date = $account->actual_maturity_date;                    
                    $transaction->create_user_id = \Auth::guard('admins')->user()->id;
                    $transaction->save();
                    $account->transfered_transaction_id = $transaction->id;
                    $account->save();
                }
            }
            \DB::commit();    
            if ($account->account_type == Account::TYPE_FD) {
                return redirect()->route('admin.accounts.fd')->with('success', 'FD is successfully matured');
            }    
            if ($account->account_type == Account::TYPE_DD) {
                return redirect()->route('admin.accounts')->with('success', 'DD is Successfully matured');    
            }
            if ($account->account_type == Account::TYPE_RD) {
                return redirect()->route('admin.accounts')->with('success', 'RD is Successfully matured');    
            }
        }catch(\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data['user'] = new User();

        if ($request->id != null) {
            $data['user'] = User::find($request->id);
        }

        $data['title'] = "Add Account";
        $data['maintitle'] = "Accounts";
        $data['mainlink'] = "admin.accounts";
        return view('admin.accounts.add',$data);
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

        $model = new Account();
        $validator = \Validator::make($request->all(), $model->rules());

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        //echo '<pre>'; print_r($request->all());exit;
       // try {
           \DB::beginTransaction();

           $model = Account::where('status',Account::STATUS_ACTIVE)->where('account_type', $request->account_type)->where('user_id', $request->user_id)->first();

               if(!empty($model)){
                    return redirect()->back()->with('error', 'This member already have active account.');
               }
            $model = new Account();
            $model->setData($request);
            $model->save();  
        
            if($request->documents) {
                foreach($request->documents as $documents) {
                    SystemFile::saveUploadedFile($documents, $model, 'document');
                }
            }
            
            \DB::commit();
            if ($model->account_type == Account::TYPE_FD) {
                return redirect()->route('admin.accounts.fd')->with('success', 'Account is successfully added.');        
            }
            if ($model->account_type == Account::TYPE_SAVINGS) {
                return redirect()->route('admin.accounts.savings')->with('success', 'Account is successfully added.');        
            }
            return redirect()->route('admin.accounts')->with('success', 'Account is successfully added.');
        /*} catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Something went Wrong: ' . $e->getMessage());
        }*/

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
        $data['title'] = "Edit Account";
        $data['maintitle'] = "Account";
        $data['mainlink'] = "admin.accounts";
        $data['account'] = Account::find($id);
       // return $data;
        return view('admin.accounts.edit',$data);
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
        //return $request->all();
        $model = Account::find($id);
        $validator = \Validator::make($request->all(), $model->rules());

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        \DB::beginTransaction();


        $model = Account::find($id);
        $model->setData($request);
        $model->save();

        if($request->documents) {
            foreach($request->documents as $documents) {
                SystemFile::saveUploadedFile($documents, $model, 'document');
            }
        }

        \DB::commit();
        if ($model->account_type == Account::TYPE_FD) {
            return redirect()->route('admin.accounts.fd')->with('success', 'Account is successfully updated.');
        }
        if ($model->account_type == Account::TYPE_SAVINGS) {
            return redirect()->route('admin.accounts.savings')->with('success', 'Account is successfully updated.');
        }
        return redirect()->route('admin.accounts')->with('success', 'Account is successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $user = User::find($id);
            $user->delete();
            return redirect()->route('admin.user.index')->with('success', 'User has been deleted successfully.');
        } catch (\Excetion $e) {
            return redirect()->back()->with('error', 'Some Erros Found:- ' . $e->getMessage());
        }
    }

    public function getDuration(Request $request)
    {
        $duration = Account::getDurationOptions($request->val);
        return [
            'data' => $duration];
    }

    public function getDenominaton(Request $request)
    {
        $denomination = Account::getDenominationArray($request->val);
        return [
            'data' => $denomination];
    }

    public function getUsersList(Request $request, $id = null)
    {
        $users = User::select('users.id','users.prefix', 'users.member_id', 'users.name', 'users.last_name')
                        ->where('member_id', 'LIKE', '%' . $request->q . '%');

        if ($id != null) {
           // $users = $users->where('create_user_id', $id);
            ;
        }

        $users = $users->get();

        if ($request->q) {
            $users = $users->where('text', $request->q);
        }
        $users = $users->toArray();
        return response()->json(['data' => $users, 'status' => 200]);        
    }

    public function getAdminsList()
    {
        $admins = Admin::get()->toArray();
        return response()->json(['data' => $admins, 'status' => 200]);   
    }

    public function accounts()
    {
        $data['type'] = "";
        $data['url'] = route('admin.ajax.get.data');
        return view('admin.accounts.index', $data);
    }

    public  function printPassbook($id){
        $account = Account::find($id);
        $transactions = AccountTransaction::where('account_id',$id)->orderBy('paid_date')->get();
        return view('admin.print_passbook_mainpage', compact('account','transactions'));
    }

    public function printFd($id)
    {
        $account = Account::find($id);
        $transaction = AccountTransaction::where('account_id', $id)->first();
        if ($transaction == null) {
            $transaction = new AccountTransaction();
            $transaction->amount = $account->denomination_amount;
            $transaction->account_id = $account->id;
            $transaction->method = AccountTransaction::METHOD_CREDIT; 
            $transaction->paid_date = $account->policy_date;
            $transaction->create_user_id = $account->create_user_id;
            $transaction->save();            
        }
        return view('admin.print_fd', compact('account','transaction'));   
    }

    public function printTransactions($id)
    {
        $account = Account::find($id);        
        $transactions = $account->transactions;
        return view('admin.print_transaction', compact('account','transactions'));      
    }

    public function export(Request $request, $type = null)
    {
        //echo "<pre>";print_r($type);exit;

        $data = Account::with('user', 'createUser');

        if ($type != "") {
            $data = $data->where('account_type', $type);
        }else{
            $data = $data->whereIn('account_type', [Account::TYPE_RD, Account::TYPE_DD]);
        }

        if ($request->create_user_id) {
            $data = $data->where('create_user_id', $request->create_user_id);
        }

        if ($request->start_date && $request->end_date) {
            $data = $data->whereBetween('policy_date', [$request->start_date, $request->end_date]);
        }elseif ($request->start_date && !$request->end_date) {
            $data = $data->whereBetween('policy_date', [$request->start_date, date("Y-m-d H:i:s")]);
        }elseif (!$request->start_date && $request->end_date) {
            $data = $data->whereBetween('policy_date', [date("Y-m-d H:i:s"), $request->end_date]);
        } 

        $data = $data->get()->toArray();
        if ($type == "") {
            $headers = [
                'Applicant Name',
                'Policy Date',
                'Policy Code',
                'Amount',
                'Term',
                'Mode',
                'Due Date',
                'Inst No',
                'Paid Inst',
                'Unpaid Inst',
                'Paid Amount',
                'Req. Amount',
                'Created By'                
            ];
        }else if ($type == Account::TYPE_SAVINGS){
            $headers = [
                'Applicant Name',
                'Policy Date',
                'Policy Code',
                'Created By'                
            ];
        }else if ($type == Account::TYPE_FD){
            $headers = [
                'Applicant Name',
                'Policy Date',
                'Policy Code',
                'Amount',
                'Term',
                'Maturity Date',
                'Maturity Amount',
                'Interest Rate',
                'Actual Maturity Date',
                'Actual Maturity Amount',
                'Actual Interest Rate',
                'Created By'         
            ];
        }
        $array = [];
        $denomination_amount_total = 0;
        $paid_amount_total = 0;
        $payable_amount_total = 0;
        foreach ($data as $value) {
            if ($type == "") {
             $array_value = [
                    $value['user']['name'].''.$value['user']['last_name'],
                    date("Y-m-d", strtotime($value['policy_date'])),
                    $value['ori_account_number'],
                    $value['denomination_amount'],
                    $value['term'],
                    Account::getTypeOptions($value['account_type']),
                    $value['maturity_date'],
                    $value['installment_number'],
                    $value['paid_installment'],
                    $value['unpaid_installment'],
                    $value['paid_amount'],
                    $value['payable_amount'],
                    $value['create_user']['name']
                ];
                $denomination_amount_total = $denomination_amount_total + $value['denomination_amount'];
                $paid_amount_total = $paid_amount_total + $value['paid_amount'];
                $payable_amount_total = $payable_amount_total + $value['payable_amount'];
            }else if ($type == Account::TYPE_SAVINGS){
                $array_value = [
                    $value['user']['name'].''.$value['user']['last_name'],
                    date("Y-m-d", strtotime($value['policy_date'])),
                    $value['ori_account_number'],
                    $value['create_user']['name']
                ];
            }else if ($type == Account::TYPE_FD){
                $array_value = [
                    $value['user']['name'].''.$value['user']['last_name'],
                    date("Y-m-d", strtotime($value['policy_date'])),
                    $value['ori_account_number'],
                    $value['denomination_amount'],
                    $value['term'],
                    $value['maturity_date'],
                    $value['maturity_amount'],
                    $value['interest_rate'],
                    $value['actual_maturity_date'],
                    $value['actual_maturity_amount'],
                    $value['actual_interest_rate'],
                    $value['create_user']['name']
                ];
            }
            
            $array[] = array_combine($headers, $array_value);
        }
        if ($type == "") {
        $array_value_total = [
                    '',
                    '',
                    '',
                    $denomination_amount_total,
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    $paid_amount_total,
                    $payable_amount_total,
                    ''
                ];
          $array[] = array_combine($headers,$array_value_total);
      }

        return \Excel::create('accounts', function($excel) use ($array, $type) {
            
            $excel->sheet('mySheet', function($sheet) use ($array, $type)
            {
                $sheet->fromArray($array);
                if ($type == "") {
                    $paid = Account::getTotalPaid($type); 
                    $sheet->appendRow(array(
    'Grand Total Paid Amount: Rs.'.$paid
));
                    $sheet->appendRow(array(
    'Grand Total Required Amount: Rs.'.Account::getTotalRequired($type)
));
                }
            });
            })->download('xls');
    }



}
