<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{

    function __construct()
    {
        $this->middleware(['permission:عرض صلاحية'], ['only' => ['index']]);
        $this->middleware(['permission:role-create'], ['only' => ['create', 'store']]);
        // $this->middleware(['permission:role-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:role-delete'], ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $roles = Role::orderBy('id', 'DESC')->paginate(5);
        return view('roles.index', compact('roles'));

    }

    public function create()
    {
        $permission = Permission::get();
        return view('roles.create', compact('permission'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions(array_map('intval',$request->input('permission')));
        session()->flash('Add');
        return redirect()->route('roles.index');

    }


    public function show($id)
    {
        $role = Role::find($id);
        return view('roles.show', compact('role'));
    }

    public function edit($id)
    {
        $role = Role::find($id);
        $rolePermissions = $role->Permissions->pluck('id','id')->toArray();
        $permission = Permission::get();
        return view('roles.edit', compact('role', 'permission', 'rolePermissions'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);
        $role = Role::find($id);
        $role->update(['name' => $request->name ]);
        $role->syncPermissions(array_map('intval',$request->input('permission')));
        session()->flash('edit');
        return redirect()->route('roles.index');
    }

    public function destroy($id)
    {

        Role::find($id)->delete();
        session()->flash('delete');
        return redirect()->route('roles.index');

    }
}

