<?php

namespace App\Http\Controllers\Admin;

use App\Http\Models\SystemFile;
use App\User;
use Illuminate\Http\Request;
use App\Account;
use App\AccountTransaction;


class UserController extends Controller
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
        $data['users'] = User::with('account')->orderBy('id')->get();
        $data['title'] = "User";
        return view('admin.user.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $data['title'] = "Add User";
        $data['maintitle'] = "User";
        $data['mainlink'] = "admin.user.index";
        return view('admin.user.add',$data);
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

        $model = new User();
        $validator = \Validator::make($request->all(), $model->rules());

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        //echo '<pre>'; print_r($request->all());exit;
       // try {
           \DB::beginTransaction();
            $model->setData($request);
            $model->prefix = $model->getMemberPrefix();
            $model->member_id = $model->getMemberId();

            if ($request->hasFile('profile_picture')) {
                $image =  SystemFile::uploadImage($request->file('profile_picture'));
                $model->profile_pic = $image;
            }

            $model->save();
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

            //return $request->all();
            if($request->hasFile('profile_picture')) {
                //dd('hi');
                $old_image = $model->profile_pic;
                if($old_image != null){
                    $path =public_path(). '/uploads/'.$old_image;
                    unlink($path);
                }
                $image =  SystemFile::uploadImage($request->file('profile_picture'));
                $model->profile_pic = $image;
            }
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
}
