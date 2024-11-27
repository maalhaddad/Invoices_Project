<?php

namespace App\Http\Controllers;

use App\Models\Invoices;
use App\Models\InvoicesAttachments;
use App\Models\InvoicesDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoicesDetailsController extends Controller
{

    public function __construct()
    {
      $this->middleware('auth');
    }
    public function index()
    {
        //
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        $invoice = Invoices::findOrFail($id);
        return view('invoices.invoice_details',compact('invoice'));
       


    }


    public function edit(InvoicesDetails $invoicesDetails)
    {
        //
    }


    public function update(Request $request, InvoicesDetails $invoicesDetails)
    {
        //
    }


    public function destroy(Request $request)
    {
        try {
            $attachment = InvoicesAttachments::findOrFail($request->id)->delete();

            if($attachment)
            {
                Storage::disk('public_Attachment')->delete($request->invoice_number.'\\'.$request->file_name);
            }
            return redirect()->back()->with('delete','تم الحذف بنجاح');
        } catch (\Exception $ex) {

             return redirect()->back()->withErrors($ex->getMessage());
        }

    }

    public function get_file($invoicenum,$fileName){

        $file = public_path('Attachment').'\\'.$invoicenum.'\\'.$fileName;
        return response()->download($file);
    }
}
