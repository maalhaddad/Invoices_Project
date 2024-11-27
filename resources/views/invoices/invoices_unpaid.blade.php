@extends('layouts.master')
@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <!--Internal   Notify -->
<link href="{{URL::asset('assets/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
@endsection
@section('title', 'الفواتير الغير مدفوعه ')
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/الفواتير الغير مدفوعه
                    </span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
@include('error-page.error')

@if (Session::has('delete_invoice'))
<script>

     window.onload = function (){
        notif({
            msg: 'تم حذف الفاتورة بنجاح',
            type: 'success'
        })
     }

</script>

@endif

@if (Session::has('Status_Update'))
<script>

     window.onload = function (){
        notif({
            msg: 'تم تحديث حالة الدفع بنجاح ',
            type: 'success'
        })
     }

</script>

@endif
    <!-- row -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card mg-b-20">

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example1" class="table key-buttons text-md-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">#</th>
                                    <th class="border-bottom-0">رقم الفاتوره</th>
                                    <th class="border-bottom-0">تاريخ الفاتوره</th>
                                    <th class="border-bottom-0">تاريخ الاستحقاق</th>
                                    <th class="border-bottom-0">المنتج</th>
                                    <th class="border-bottom-0">القسم</th>
                                    <th class="border-bottom-0">الخصم</th>
                                    <th class="border-bottom-0">نسبة الضريبه</th>
                                    <th class="border-bottom-0">قسمة الضريبه</th>
                                    <th class="border-bottom-0">الاجمالي</th>
                                    <th class="border-bottom-0">الحاله</th>
                                    <th class="border-bottom-0">الملاحظات</th>
                                    <th class="border-bottom-0">العمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($invoices) && $invoices->count() > 0)

                                    @foreach ($invoices as $invoice)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $invoice->invoice_number }}</td>
                                            <td>{{ $invoice->invoice_Date }}</td>
                                            <td>{{ $invoice->Due_date }}</td>
                                            <td>{{ $invoice->product }}</td>
                                            <td><a href="{{ route('invoiceDetails.show', $invoice->id) }}" target="_blank">
                                                    {{ $invoice->Section->name }}
                                                </a></td>
                                            <td>{{ $invoice->Discount }}</td>
                                            <td>{{ $invoice->Rate_VAT }}</td>
                                            <td>{{ $invoice->Value_VAT }}</td>
                                            <td>{{ $invoice->Total }}</td>
                                            <td>

                                                @php
                                                    if ($invoice->Value_Status == 1) {
                                                        $className = 'text-success';
                                                    } elseif ($invoice->Value_Status == 2) {
                                                        $className = 'text-danger';
                                                    } else {
                                                        $className = 'text-warning';
                                                    }
                                                @endphp
                                                <span class="{{ $className }}">{{ $invoice->Status }}</span>
                                            </td>
                                            <td>{{ $invoice->note }}</td>

                                            <td>
                                                <div class="dropdown">
                                                    <button aria-expanded="false" aria-haspopup="true"
                                                        class="btn ripple btn-primary btn-sm" data-toggle="dropdown"
                                                        type="button">العمليات<i
                                                            class="fas fa-caret-down ml-1"></i></button>
                                                    <div class="dropdown-menu tx-13">
                                                        @can('تعديل الفاتورة')
                                                            <a class="dropdown-item"
                                                                href="{{ route('invoices.edit',$invoice->id) }}">تعديل
                                                                الفاتورة</a>
                                                                @endcan

                                                                @can('حذف الفاتورة')
                                                            <a class="dropdown-item" href="modaldemo9"
                                                                data-invoice_id="{{ $invoice->id }}" data-toggle="modal"
                                                                data-target="#delete_invoice"><i
                                                                    class="text-danger fas fa-trash-alt"></i>&nbsp;&nbsp;حذف
                                                                الفاتورة</a>
                                                                @endcan

                                                                @can('تغير حالة الدفع')
                                                                <a class="dropdown-item"
                                                            href="{{ route('invoices.show',$invoice->id) }}"><i
                                                                class=" text-success fas
                                                            fa-money-bill"></i>&nbsp;&nbsp;تغير
                                                            حالة
                                                            الدفع</a>
                                                            @endcan
                                                            @can('ارشفة الفاتورة')
                                                            <a class="dropdown-item" href="#" data-invoice_id="{{ $invoice->id }}"
                                                                data-toggle="modal" data-target="#Transfer_invoice"><i
                                                                    class="text-warning fas fa-exchange-alt"></i>&nbsp;&nbsp;نقل الي
                                                                الارشيف</a>
                                                                @endcan

                                                    </div>
                                                </div>

                                            </td>

                                        </tr>
                                    @endforeach
                                @endif



                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="delete_invoice">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">حذف الفاتوره</h6><button aria-label="Close" class="close"
                            data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <form action="{{ route('invoices.destroy','test') }}" method="post">
                        @method('delete')
                        @csrf
                        <div class="modal-body">
                            <p>هل انت متاكد من عملية الحذف ؟</p><br>
                            <input type="hidden" name="invoice_id" id="invoice_id" value="">
                            {{-- <input class="form-control" name="name" id="delete_invoice" type="text" readonly value="" > --}}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                            <button type="submit" class="btn btn-danger">تاكيد</button>
                        </div>
                </div>
                </form>
            </div>
        </div>

    </div>
    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
    <!-- Internal Data tables -->
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
    <!--Internal  Datatable js -->
    <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>
    <!--Internal  Notify js -->
<script src="{{URL::asset('assets/plugins/notify/js/notifIt.js')}}"></script>
<script src="{{URL::asset('assets/plugins/notify/js/notifit-custom.js')}}"></script>


    <script>
        $('#delete_invoice').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var invoice_id = button.data('invoice_id')
             console.log(invoice_id);
            var modal = $(this)
            modal.find('.modal-body #invoice_id').val(invoice_id);

        })
    </script>

@endsection



