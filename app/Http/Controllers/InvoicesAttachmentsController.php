<?php

namespace App\Http\Controllers;

use App\Models\Invoices;
use App\Models\InvoicesAttachments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoicesAttachmentsController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:اضافة مرفق'], ['only' => ['store']]);
        $this->middleware(['permission:حذف مرفق'], ['only' => ['deleteAttachment']]);
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
        try {

            $this->validate($request, [

                'file_name' => 'mimes:pdf,jpeg,png,jpg',

            ], [
                'file_name.mimes' => 'صيغة المرفق يجب ان تكون   pdf, jpeg , png , jpg',
            ]);

            $invoice = Invoices::where('id', $request->id)->first();
            if ($request->hasFile('file_name')) {
                $file = $request->file('file_name');
                $fileName =  $file->getClientOriginalName();
                $file_path = public_path('Attachment/' . $invoice->invoice_number);
                // Save dataBase
                $attchment = new InvoicesAttachments();
                $attchment->file_name = 'Attachment/' . $invoice->invoice_number . '/' . $fileName;
                $attchment->invoice_number = $invoice->invoice_number;
                $attchment->Created_by = (Auth::user()->name);
                $attchment->invoice_id = $invoice->id;


                if ($attchment->save()) {
                    $file->move($file_path, $fileName);
                }
            }

            return redirect()->back()->with('Add', 'تم اضافة المرفق بنجاح');
        } catch (\Exception $ex) {

            return redirect()->back()->with('error', $ex->getMessage());
        }
    }  // End Store


    public function show(InvoicesAttachments $invoicesAttachments)
    {
        //
    }


    public function edit(InvoicesAttachments $invoicesAttachments)
    {
        //
    }


    public function update(Request $request, InvoicesAttachments $invoicesAttachments)
    {
        //
    }


    public function destroy(InvoicesAttachments $invoicesAttachments)
    {
        //
    }
}
