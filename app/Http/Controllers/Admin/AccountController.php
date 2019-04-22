<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Account;
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
        $data['accounts'] = Account::with('user')->orderBy('id')->get();
        $data['title'] = "Accounts";
        return view('admin.accounts.index',$data);
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
        $data['maintitle'] = "Account";
        $data['mainlink'] = "admin.accounts.index";
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
            $model->setData($request);
            $model->save();  
        
            if($request->documents) {
                foreach($request->documents as $documents) {
                    SystemFile::saveUploadedFile($documents, $model, 'document');
                }
            }
            
            \DB::commit();
            return redirect()->route('admin.user.index')->with('success', 'User is successfully added.');
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
        $data['title'] = "Edit User";
        $data['maintitle'] = "User";
        $data['mainlink'] = "admin.user.index";
        $data['user'] = User::find($id);
        return view('admin.user.edit',$data);
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
        $model = User::where('id', $id)->first();

        $validator = \Validator::make($request->all(), $model->rules());

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        try {
            $model->setData($request);
            $model->save();
            return redirect()->route('admin.user.index')->with('success', 'User is successfully updated.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Something went Wrong: ' . $e->getMessage());
        }
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

    public function getUsersList(Request $request)
    {
        $users = User::select('users.id','users.prefix', 'users.member_id')
                        ->where('member_id', 'LIKE', '%' . $request->q . '%')
                        ->get()->toArray();

        return response()->json(['data' => $users, 'status' => 200]);        
    }

    public function dailyDeposite(){
        return view('admin.daily_deposite.index');
    }

}
