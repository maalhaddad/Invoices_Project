<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    function __construct()
    {
        $this->middleware(['permission:قائمة المستخدمين'], ['only' => ['index']]);
        $this->middleware(['permission:اضافة مستخدم'], ['only' => ['create','store']]);
        $this->middleware(['permission:تعديل مستخدم'], ['only' => ['edit','update']]);
        $this->middleware(['permission:حذف مستخدم'], ['only' => ['delete']]);
    }
    public function index()
    {
        $data = User::latest()->paginate(5);
        return view('users.show_user',compact('data'));
    }

    public function create()
    {
        $roles = Role::select('name')->get();
        return view('users.Add_user',compact('roles'));
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles_name' => 'required'
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
        $user->assignRole($request->input('roles_name'));

        return redirect()->route('users.index')
                        ->with('success','User created successfully');
    }

    public function show($id)
    {
        $user = User::find($id);
        return view('users.show',compact('user'));
    }

    public function edit($id)
    {
        $user = User::find($id);
        // return $user->getRoleNames();
        $roles = Role::select('name')->get();
        $userRole = $user->roles->pluck('name','name')->all();
        return view('users.edit',compact('user','roles','userRole'));
    }

    public function update(Request $request,$id)
    {
        // return $request;
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles_name' => 'required'
        ]);

        $input = $request->all();
        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));
        }

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();

        $user->assignRole($request->input('roles_name'));

        return redirect()->route('users.index')
                        ->with('success','User updated successfully');
    }

    public function destroy(Request $request)
    {
         User::find($request->user_id)->delete();
        return redirect()->route('users.index')
                        ->with('success','User deleted successfully');
    }
}
