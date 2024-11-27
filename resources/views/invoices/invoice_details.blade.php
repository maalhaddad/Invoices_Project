@extends('layouts.master')
@section('css')
@endsection
@section('title', 'تفاصيل الفاتوره')
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تفاصيل
                    الفاتوره</span>
            </div>
        </div>

    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <!-- row -->

    @include('error-page.error')
    <div class="row">
        <div class="col-xl-12">
            <!-- div -->
            <div class="card mg-b-20" id="tabs-style2">
                <div class="card-body">
                    <div class="text-wrap">
                        <div class="example">
                            <div class="panel panel-primary tabs-style-2">
                                <div class=" tab-menu-heading">
                                    <div class="tabs-menu1">
                                        <!-- Tabs -->
                                        <ul class="nav panel-tabs main-nav-line">
                                            <li><a href="#tab4" class="nav-link active" data-toggle="tab">بيانات
                                                    الفاتوره</a></li>
                                            <li><a href="#tab5" class="nav-link" data-toggle="tab">حالة الدفع</a></li>
                                            <li><a href="#tab6" class="nav-link" data-toggle="tab">مرفقات الفاتوره</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="panel-body tabs-menu-body main-content-body-right border">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab4">


                                            <div class="table-responsive mt-15">

                                                <table class="table table-striped" style="text-align:center">
                                                    @if (isset($invoice))
                                                        <tbody>
                                                            <tr>
                                                                <th scope="row">رقم الفاتورة</th>
                                                                <td> {{ $invoice->invoice_number }} </td>
                                                                <th scope="row">تاريخ الاصدار</th>
                                                                <td> {{ $invoice->invoice_Date }} </td>
                                                                <th scope="row">تاريخ الاستحقاق</th>
                                                                <td> {{ $invoice->Due_date }} </td>
                                                                <th scope="row">القسم</th>
                                                                <td> {{ $invoice->Section->name }} </td>
                                                            </tr>

                                                            <tr>
                                                                <th scope="row">المنتج</th>
                                                                <td> {{ $invoice->product }} </td>
                                                                <th scope="row">مبلغ التحصيل</th>
                                                                <td> {{ $invoice->Amount_collection }} </td>
                                                                <th scope="row">مبلغ العمولة</th>
                                                                <td> {{ $invoice->Amount_Commission }} </td>
                                                                <th scope="row">الخصم</th>
                                                                <td> {{ $invoice->Discount }} </td>
                                                            </tr>


                                                            <tr>
                                                                <th scope="row">نسبة الضريبة</th>
                                                                <td> {{ $invoice->Rate_VAT }} </td>
                                                                <th scope="row">قيمة الضريبة</th>
                                                                <td> {{ $invoice->Value_VAT }} </td>
                                                                <th scope="row">الاجمالي مع الضريبة</th>
                                                                <td> {{ $invoice->Total }} </td>
                                                                <th scope="row">الحالة الحالية</th>
                                                                <?php
                                                                $className = $invoice->Value_Status == 1 ? 'success' : ($invoice->Value_Status == 2 ? 'danger' : 'warning');

                                                                ?>



                                                                <td><span
                                                                        class="badge badge-pill badge-{{ $className }}">{{ $invoice->Status }}</span>
                                                                </td>

                                                            </tr>

                                                            <tr>
                                                                <th scope="row">ملاحظات</th>
                                                                <td> {{ $invoice->note }} </td>
                                                            </tr>
                                                        </tbody>
                                                    @endif

                                                </table>

                                            </div>

                                        </div>
                                        <div class="tab-pane" id="tab5">
                                            <div class="table-responsive mt-15">
                                                <table class="table center-aligned-table mb-0 table-hover"
                                                    style="text-align:center">
                                                    <thead>
                                                        <tr class="text-dark">
                                                            <th>#</th>
                                                            <th>رقم الفاتورة</th>
                                                            <th>نوع المنتج</th>
                                                            <th>القسم</th>
                                                            <th>حالة الدفع</th>
                                                            <th>تاريخ الدفع </th>
                                                            <th>ملاحظات</th>
                                                            <th>تاريخ الاضافة </th>
                                                            <th>المستخدم</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        @foreach ($invoice->Details as $Details)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $Details->invoice_number }}</td>
                                                                <td>{{ $Details->product }}</td>
                                                                <td>{{ $invoice->Section->name }}</td>
                                                                <?php
                                                                $className = $Details->Value_Status == 1 ? 'success' : ($Details->Value_Status == 2 ? 'danger' : 'warning');

                                                                ?>
                                                                <td><span
                                                                        class="badge badge-pill badge-{{ $className }}">{{ $Details->Status }}</span>
                                                                </td>

                                                                <td>{{ $Details->Payment_Date }}</td>
                                                                <td>{{ $Details->note }}</td>
                                                                <td>{{ $Details->created_at }}</td>
                                                                <td>{{ $Details->user }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>


                                            </div>

                                        </div>


                                        <div class="tab-pane" id="tab6">
                                             @can('اضافة مرفق')


                                            <div class="card-body">
                                                <p class="text-danger">* صيغة المرفق pdf, jpeg ,.jpg , png </p>
                                                <h5 class="card-title">اضافة مرفقات</h5>
                                                <form method="post" action="{{ route('invoiceAttachments.store') }}"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="customFile"
                                                            name="file_name" required>
                                                        <input type="hidden" id="id" name="id"
                                                            value="{{ $invoice->id }}">
                                                        <label class="custom-file-label" for="customFile">حدد
                                                            المرفق</label>
                                                    </div><br><br>
                                                    <button type="submit" class="btn btn-primary btn-sm "
                                                        name="uploadedFile">تاكيد</button>
                                                </form>
                                            </div>
                                            @endcan
                                            <div class="table-responsive mt-15">
                                                <table class="table center-aligned-table mb-0 table table-hover"
                                                    style="text-align:center">
                                                    <thead>
                                                        <tr class="text-dark">
                                                            <th scope="col">م</th>
                                                            <th scope="col">اسم الملف</th>
                                                            <th scope="col">قام بالاضافة</th>
                                                            <th scope="col">تاريخ الاضافة</th>
                                                            <th scope="col">العمليات</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        @foreach ($invoice->Attachments as $attachment)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $attachment->file_name }}</td>
                                                                <td>{{ $attachment->Created_by }}</td>
                                                                <td>{{ $attachment->created_at }}</td>
                                                                <td colspan="2">
                                                                    @php
                                                                        $path_file = explode(
                                                                            '/',
                                                                            $attachment->file_name,
                                                                        );
                                                                    @endphp
                                                                    <a class="btn btn-outline-success btn-sm"
                                                                        href="{{ route('fileshow', $attachment->file_name) }}"
                                                                        role="button"><i class="fas fa-eye"></i>&nbsp;
                                                                        عرض</a>

                                                                    <a class="btn btn-outline-info btn-sm"
                                                                        {{-- route('download',$path_file[1],$path_file[2]) --}}
                                                                        href="{{ url('get_file') }}/{{ $path_file[1] }}/{{ $path_file[2] }}"
                                                                        role="button"><i class="fas fa-download"></i>&nbsp;
                                                                        تحميل</a>

                                                                    @can('حذف المرفق')


                                                                    <button class="btn btn-outline-danger btn-sm"
                                                                        data-toggle="modal"
                                                                        data-file_name="{{ $path_file[2] }}"
                                                                        data-invoice_number="{{ $path_file[1] }}"
                                                                        data-id_file="{{ $attachment->id }}"
                                                                        data-target="#delete_file">حذف</button>
                                                                    @endcan

                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>




                        <!---Prism Pre code-->
                    </div>
                </div>
            </div>
        </div>




    </div>


    <div class="modal fade" id="delete_file" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">حذف المرفق</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('delete_file') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <p class="text-center">
                        <h6 style="color:red"> هل انت متاكد من عملية حذف المرفق ؟</h6>
                        </p>

                        <input type="hidden" name="id" id="id_file" value="">
                        <input type="hidden" name="file_name" id="file_name" value="">
                        <input type="hidden" name="invoice_number" id="invoice_number" value="">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">الغاء</button>
                        <button type="submit" class="btn btn-danger">تاكيد</button>
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
    <!--Internal  Datepicker js -->
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <!-- Internal Select2 js-->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <!-- Internal Jquery.mCustomScrollbar js-->
    <script src="{{ URL::asset('assets/plugins/custom-scroll/jquery.mCustomScrollbar.concat.min.js') }}"></script>
    <!-- Internal Input tags js-->
    <script src="{{ URL::asset('assets/plugins/inputtags/inputtags.js') }}"></script>
    <!--- Tabs JS-->
    <script src="{{ URL::asset('assets/plugins/tabs/jquery.multipurpose_tabcontent.js') }}"></script>
    <script src="{{ URL::asset('assets/js/tabs.js') }}"></script>
    <!--Internal  Clipboard js-->
    <script src="{{ URL::asset('assets/plugins/clipboard/clipboard.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/clipboard/clipboard.js') }}"></script>
    <!-- Internal Prism js-->
    <script src="{{ URL::asset('assets/plugins/prism/prism.js') }}"></script>


    <script>
        $('#delete_file').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id_file = button.data('id_file')
            var file_name = button.data('file_name')
            var invoice_number = button.data('invoice_number')
            var modal = $(this)

            modal.find('.modal-body #id_file').val(id_file);
            modal.find('.modal-body #file_name').val(file_name);
            modal.find('.modal-body #invoice_number').val(invoice_number);

        })
    </script>
@endsection
