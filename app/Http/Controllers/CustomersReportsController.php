<?php

namespace App\Http\Controllers;

use App\Models\Sections;
use Illuminate\Http\Request;
use App\Models\Invoices;

class CustomersReportsController extends Controller
{

    public function index()
    {
        $Sections = Sections::all();
        return view('reports.customers_reports',compact('Sections'));
    }

    public function SearchCustomers(Request $request)
    {
        try
        {
            $searchData = [
                'Section' => $request->Section,
                'start_at' => $request->start_at,
                'end_at' => $request->end_at,
                'product' => $request->product,
            ];
            $query = Invoices::query();

            if($request->Section && $request->start_at == '' && $request->end_at == '')
            {
                $query->where('section_id',$request->Section)->where('product', $request->product);
            }

            else
            {
               $query->whereBetween('invoice_Date', [$request->start_at, $request->end_at])
               ->where('section_id',$request->Section)->where('product', $request->product);
            }

            $invoices = $query->get();
            $Sections = Sections::all();
            return view('reports.customers_reports',
            [
                'searchData' => $searchData,
                'Sections' => $Sections,
                'invoices' => $invoices,]);
        }
        catch(\Exception $ex)
        {
            return view('reports.customers_reports')->with(['Error' => $ex->getMessage()]);
        }

    }
}
