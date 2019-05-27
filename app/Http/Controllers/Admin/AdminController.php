<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
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
        $data['staffs'] = Admin::all();
        $data['title'] = "Staff";
        return view('admin.staff.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['title'] = "Add Staff";
        $data['maintitle'] = "Staff";
        $data['mainlink'] = "admin.staff.index";
        $data['roles'] = Role::all();
        return view('admin.staff.add',$data);
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
        $model = new Admin();
        $validator = \Validator::make($request->all(), $model->rules());

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        try {
            $model->setData($request);
            $model->password = bcrypt($request->password);

            if ($request->role) {
                $role_r = Role::where('name', '=', $request->role)->firstOrFail();
                $model->assignRole($role_r);
            }
            $model->save();


            return redirect()->route('admin.staff.index')->with('success', 'Staff is successfully added.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Something went Wrong: ' . $e->getMessage());
        }
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
        $data['title'] = "Edit Staff";
        $data['maintitle'] = "Staff";
        $data['mainlink'] = "admin.staff.index";
        $data['staff'] = Admin::find($id);
        $data['roles'] = Role::all();
        return view('admin.staff.edit',$data);
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
        $model = Admin::where('id', $id)->first();

        $validator = \Validator::make($request->all(), $model->rules());

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        try {
            $model->setData($request);            
            $model->save();
            if ($request->role) {
                $role_r = Role::where('name', '=', $request->role)->firstOrFail();
                $model->assignRole($role_r);
            }
            
            return redirect()->route('admin.staff.index')->with('success', 'Staff is successfully updated.');
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
            $user = Admin::find($id);
            $user->delete();
            return redirect()->route('admin.staff.index')->with('success', 'Staff has been deleted successfully.');
        } catch (\Excetion $e) {
            return redirect()->back()->with('error', 'Some Erros Found:- ' . $e->getMessage());
        }
    }
}
