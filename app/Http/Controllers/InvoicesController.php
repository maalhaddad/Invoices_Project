<?php

namespace App\Http\Controllers;

use App\Models\Invoices;
use App\Models\InvoicesAttachments;
use App\Models\InvoicesDetails;
use App\Models\Sections;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class InvoicesController extends Controller
{

    function __construct()
    {

        $this->middleware(['permission:الفواتير'], ['only' => ['index']]);
        $this->middleware(['permission:ارشيف الفواتير'], ['only' => ['ShowInvoicesArchive',]]);
        $this->middleware(['permission:الفواتير المدفوعة'], ['only' => ['ShowInvoicesPaid',]]);
        $this->middleware(['permission:الفواتير الغير مدفوعة'], ['only' => ['ShowInvoicesUnpaid',]]);
        $this->middleware(['permission:الفواتير المدفوعة جزئيا'], ['only' => ['ShowInvoicesPartial',]]);
        // ================================================================================================

        $this->middleware(['permission:حذف الفاتورة'], ['only' => ['Archive_Invoices']]);
        $this->middleware(['permission:تعديل الفاتورة'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:اضافة فاتورة'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:طباعةالفاتورة'], ['only' => ['Print_invoices']]);
        $this->middleware(['permission:تغير حالة الدفع'], ['only' => ['show']]);



    }

    public function index()
    {
        $invoices = Invoices::all();

        return view('invoices.invoices', compact('invoices'));
    }


    public function create()
    {
        $sections = Sections::all();
        return view('invoices.add_invoice', compact('sections'));
    }


    public function store(Request $request)
    {

        $invoice = Invoices::create([
            "invoice_number" => $request->invoice_number,
            "invoice_Date" => $request->invoice_Date,
            "Due_date"  => $request->Due_date,
            "section_id"  => $request->Section,

            "product"  => $request->product,
            "Amount_collection"  => $request->Amount_collection,
            "Amount_Commission"  => $request->Amount_Commission,
            "Discount"  => $request->Discount,
            "Rate_VAT"  => $request->Rate_VAT,
            "Value_VAT"  => $request->Value_VAT,
            "Status" => 'غير مدفوعة',
            'Value_Status' => 2,
            "Total"  => $request->Total,
            "note"  => $request->note,
        ]);


        $invoice_id = Invoices::latest()->first()->id;

        InvoicesDetails::create([
            'invoice_id' => $invoice_id,
            "invoice_number" => $request->invoice_number,
            "section" => $request->Section,
            "product" => $request->product,
            "note" => $request->note,
            "Status" => 'غير مدفوعة',
            'Value_Status' => 2,
            'user' => (Auth::user()->name),
        ]);

        if ($request->hasFile('pic')) {
            $file = $request->file('pic');
            $fileName = $file->getClientOriginalName();
            $invoice_number = $request->invoice_number;
            $attachment = new InvoicesAttachments();
            $attachment->file_name = 'Attachment/' . $invoice_number . '/' . $fileName;
            $attachment->invoice_number = $invoice_number;
            $attachment->invoice_id = $invoice_id;
            $attachment->Created_by = (Auth::user()->name);
            if ($attachment->save()) {
                // save file
                $file->move(public_path('Attachment/' . $invoice_number), $fileName);
            }
        }


        return redirect()->back()->with('Add', 'تم اضافة الفاتتوره بنجاح');
    } // End Store


    public function show($id)
    {
        $invoices = Invoices::findOrFail($id);
        return view('invoices.status_update', compact('invoices'));
    }

    public function edit($id)
    {
        $invoice = Invoices::findOrFail($id);
        $sections = Sections::all();
        return view('invoices.add_invoice', compact(['sections', 'invoice']));
    }


    public function update(Request $request, $id)
    {



        $invoice = Invoices::findOrFail($id);
        $invoice_number = $invoice->invoice_number;
        $invoice->update([
            "invoice_number" => $request->invoice_number,
            "invoice_Date" => $request->invoice_Date,
            "Due_date"  => $request->Due_date,
            "section_id"  => $request->Section,
            "product"  => $request->product,
            "Amount_collection"  => $request->Amount_collection,
            "Amount_Commission"  => $request->Amount_Commission,
            "Discount"  => $request->Discount,
            "Rate_VAT"  => $request->Rate_VAT,
            "Value_VAT"  => $request->Value_VAT,
            "Total"  => $request->Total,
            "note"  => $request->note,

        ]);

        // تحديث جدول المرفقات ان وجدت
        if ($invoice->Attachments()->exists()) {
            $file_name = explode('/', $invoice->Attachments[0]->file_name);

            $invoice->Attachments()->update([
                'invoice_number' => $invoice->invoice_number,
                'file_name' => $file_name[0] . '/' . $invoice->invoice_number . '/' . $file_name[2],
            ]);


            $filePath = public_path('Attachment');
            rename($filePath . '\\' . $invoice_number, $filePath . '\\' . $invoice->invoice_number);
        }
        return redirect()->route('invoices.index')->with('edit', 'تم تحديث البيانات بنجاح');
    } // End Update


    public function destroy(Request $request, $typeDelete)
    {

        //حذف الفاتورة
        $id = $request->invoice_id;
        if ($typeDelete == 'Archive') {
            $invoice = Invoices::withTrashed()->find($id);
        } else {
            $invoice = Invoices::findOrFail($id);
        }

        $invoice_number = $invoice->invoice_number;


        if ($invoice->Attachments()->exists()) {
            //  حذف المرفقات من السيرفر
            $path = public_path('Attachment\\' . $invoice_number);
            File::deleteDirectory($path);
        }

        $invoice->forceDelete();
        session()->flash('delete_invoice');
        return redirect('/invoices');
    }

    public function Archive_Invoices(Request $request)
    {

        if ($request->type_pro == 1) {
            $invoice = Invoices::findOrFail($request->invoice_id);
            $invoice->delete();
            session()->flash('Archive');

            return redirect('/invoices');
        } else {
            $invoice = Invoices::withTrashed()->find($request->invoice_id);
            $invoice->restore();
            session()->flash('mov_Archive');
            return redirect('/invoices/invoices_Archive');
        }
    }

    public function Print_invoices($id)
    {

        $invoices = Invoices::findOrFail($id);
        return view('invoices.Print_invoice', compact('invoices'));
    }

    public function getSections(Request $request)
    {
        // جلب بيانات القسم بأستخدام Ajx
        $id = $request->input('id');
        $products = DB::table('products')->where('section_id', $id)->pluck('name', 'id');

        return response()->json($products);
    }

    public function Status_Update(Request $request)
    { // دالة تحديث حالة الدفع

        $invoice = Invoices::findOrFail($request->invoice_id);
        $Value_Status = $request->Status == 'مدفوعة' ? 1 : 3;

        $invoice->update([
            'Value_Status' => $Value_Status,
            'Status' => $request->Status,
            'Payment_Date' => $request->Payment_Date

        ]);

        InvoicesDetails::create([
            'invoice_id' => $request->invoice_id,
            "invoice_number" => $request->invoice_number,
            "section" => $request->Section,
            "product" => $request->product,
            "note" => $request->note,
            "Status" => $request->Status,
            'Value_Status' => $Value_Status,
            'Payment_Date' => $request->Payment_Date,
            'user' => (Auth::user()->name),
        ]);

        session()->flash('Status_Update');
        return redirect('/invoices');
    }

    public function ShowInvoicesArchive()
    {  // داله عرض الفواتير حسب التصنيف
        $invoices = Invoices::onlyTrashed()->get();
        return view('invoices.invoices_Archive', compact('invoices'));
    }

    public function ShowInvoicesPaid()
    {
        $invoices = Invoices::where('Value_Status',1)->get();
        return view( 'invoices.invoices_paid' , compact('invoices'));
    }

    public function ShowInvoicesUnpaid()
    {
        $invoices = Invoices::where('Value_Status',2)->get();
        return view( 'invoices.invoices_unpaid', compact('invoices'));
    }

    public function ShowInvoicesPartial()
    {
        $invoices = Invoices::where('Value_Status',3)->get();
        return view( 'invoices.invoices_Partial' , compact('invoices'));
    }
}
