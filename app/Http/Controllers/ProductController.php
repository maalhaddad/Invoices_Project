<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sections;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['permission:المنتجات'], ['only' => ['index']]);
        $this->middleware(['permission:اضافة منتج'], ['only' => ['create','store']]);
        $this->middleware(['permission:تعديل منتج'], ['only' => ['edit','update']]);
        $this->middleware(['permission:حذف منتج'], ['only' => ['delete']]);
    }
    public function index()
    {
        $sections = Sections::all();
        $products = Product::all();

        return view('products.products', compact(['sections', 'products']));
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string',
                'description' => 'required',
            ],
            [
                'name.required' => 'ادخل اسم المنتج',
                'name.string' => ' يجب ان يكون اسم المنتج نص ',
                'description.required' => 'ادخل البيان'
            ]
        );

        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'section_id' => $request->section_id
        ]);

        return redirect()->back()->with('Add', 'تم الاضافة بنجاح');
    }


    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {

        $request->validate(
            [
                'name' => 'required:products,name,' . $request->id,
                'description' => 'required',
            ],
            [
                'name.required' => 'ادخل اسم المنتج',
                'name.string' => ' يجب ان يكون اسم المنتج نص ',
                'description.required' => 'ادخل البيان'
            ]
        );
        $id = Sections::where('name', $request->section_name)->first()->id;
        $product = Product::findOrFail($request->id);
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'section_id' => $id
        ]);
        return redirect()->back()->with('edit', 'تم التعديل بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $product = Product::findOrFail($request->id)->delete();

        if ($product) {
            return redirect()->back()->with('delete', 'تم الحذف بنجاح');
        }
    }
}
