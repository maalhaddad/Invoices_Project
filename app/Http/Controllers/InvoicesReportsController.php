<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoices;

class InvoicesReportsController extends Controller
{

    public function index()
    {
        return view('reports.invoices_reports');
    }

    public function Search_Invoices(Request $request)
    {
        $searchData = [
            'type' => $request->type,
            'start_at' => $request->start_at,
            'end_at' => $request->end_at,
            'rdio' => $request->rdio,
        ];

        try {
            $query = Invoices::query();

            if ($request->rdio == 1) {
                if ($request->type && $request->start_at == '' && $request->end_at == '') {
                    $query->where('Status', $request->type);
                } else {
                    $query->whereBetween('invoice_Date', [$request->start_at, $request->end_at])
                        ->where('Status', $request->type);
                }
            } else {
                $query->where('invoice_number', $request->invoice_number);
            }

            $invoices = $query->get();
            return view('reports.invoices_reports', compact('invoices', 'searchData'));
        } catch (\Exception $ex) {
            return view('reports.invoices_reports')->with(['Error' => $ex->getMessage()]);
        }
    }
}
