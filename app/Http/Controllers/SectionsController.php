<?php

namespace App\Http\Controllers;

use App\Models\Sections;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionsController extends Controller
{

     function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['permission:اضافة قسم'], ['only' => ['store']]);
    }
    public function index()
    {
        $sections = Sections::all();

        return view('sections.sections' ,compact('sections'));
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {

         $roul = $this-> getroule();
         $messages = $this->getmessages();
        $validate =Validator::make($request->all(),$roul,$messages);

        if(!$validate->fails())
        {
            $section = Sections::create([
                'name' => $request->name,
                'description' =>$request->description,
                'created_by' => Auth::User()->name,
            ]);

            if($section)
            {
                return redirect()->back()->with(['Add'=> 'تمت الاضافة بنجاح']);
            }

        }

        return redirect()->back()->withErrors($validate)->withInput($request->all());

    }


    public function show(Sections $sections)
    {
        //
    }


    public function edit(Sections $sections)
    {
        //
    }


    public function update(Request $request)
    {
          $id = $request->id;
          $section = Sections::find($id);
          $validate =Validator::make($request->all(),
          [
            'name' => 'required|unique:sections,name,'.$id,
            'description' => 'required',
          ],
          $this->getmessages()
        );



        if(!$validate->fails())
        {
                $section->update([
                   'name' => $request->name,
                   'description' =>$request->description
                ]);

                return redirect()->back()->with(['edit'=> 'تم التعديل بنجاح']);
        }

        return redirect()->back()->withErrors($validate)->withInput($request->all());
    }


    public function destroy(Request $request)
    {
           $id = $request->id;

           $section = Sections::find($id)->delete();

           if($section){

               return redirect()->back()->with('delete','تم الحذف بنجاح');
           }
    }

    protected function getroule(){
        return [
            //دالة تحديد القيود لحقول ادخال البيانات
            'name' => 'required|unique:sections,name',
            'description' => 'required',
         ];
    }

    protected function getmessages(){
        return [
            // دالة رسائل التحقق من البيانات
            'name.required' => 'ادخل اسم القسم',
            'name.unique' => 'القسم موجود مسبقا',
            ];
    }
}
