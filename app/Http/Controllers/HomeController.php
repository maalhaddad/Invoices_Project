<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoices;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $invoices = [
            'Total' => Invoices::sum('Total'),
            'count' => Invoices::count(),
        ];

        $invoicesUnpaid = [
            'Total' => Invoices::where('Value_Status', 2)->sum('Total'),
            'count' =>  Invoices::where('Value_Status', 2)->count(),
        ];
        $invoicesUnpaid['percentage'] = $this->get_percentage($invoices['count'],$invoicesUnpaid['count']);



        $invoicesPaid = [
            'Total' => Invoices::where('Value_Status', 1)->sum('Total'),
            'count' =>  Invoices::where('Value_Status', 1)->count(),
        ];
        $invoicesPaid['percentage'] = $this->get_percentage($invoices['count'],$invoicesPaid['count']);


        $invoicesPartial = [
            'Total' => Invoices::where('Value_Status', 3)->sum('Total'),
            'count' =>  Invoices::where('Value_Status', 3)->count(),
        ];
        $invoicesPartial['percentage'] = $this->get_percentage($invoices['count'],$invoicesPartial['count']);

        $chartjs = app()->chartjs
            ->name('barChartTest')
            ->type('bar')
            ->size(['width' => 350, 'height' => 200])
            ->labels(['الفواتير الغير المدفوعة', 'الفواتير المدفوعة', 'الفواتير المدفوعة جزئيا'])
            ->datasets([
                [
                    "label" => "الفواتير الغير المدفوعة",
                    'backgroundColor' => ['#ec5858'],
                    'data' => [$invoicesUnpaid['percentage']]
                ],
                [
                    "label" => "الفواتير المدفوعة",
                    'backgroundColor' => ['#81b214'],
                    'data' => [$invoicesPaid['percentage']]
                ],
                [
                    "label" => "الفواتير المدفوعة جزئيا",
                    'backgroundColor' => ['#ff9642'],
                    'data' => [$invoicesPartial['percentage']]
                ],


            ])
            ->options([]);

        $chartjs_2 = app()->chartjs
            ->name('pieChartTest')
            ->type('pie')
            ->size(['width' => 340, 'height' => 200])
            ->labels(['الفواتير الغير المدفوعة', 'الفواتير المدفوعة','الفواتير المدفوعة جزئيا'])
            ->datasets([
                [
                    'backgroundColor' => ['#ec5858', '#81b214','#ff9642'],
                    'data' => [$invoicesUnpaid['percentage'], $invoicesPaid['percentage'],$invoicesPartial['percentage']]
                ]
            ])
            ->options([]);


        return view('home', compact(
            'chartjs',
            'chartjs_2',
            'invoices',
            'invoicesUnpaid',
            'invoicesPaid',
            'invoicesPartial'

        ));
    }

    private function get_percentage($invoicesCount , $typeInvoicesCount)
    {
        return ($invoicesCount > 0) ? floor(($typeInvoicesCount / $invoicesCount )*100) : 0 ;
    }
}
