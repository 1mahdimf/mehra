@extends('admin.layouts/contentLayoutMaster')

@section('title', 'پدیدآورندگان')

@section('vendor-style')
    {{-- vendor css files --}}
    <link rel="stylesheet" href="{{ asset('admin/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" type="text/css" href="{{asset('admin/css/base/plugins/forms/pickers/form-flat-pickr.css')}}">
@endsection


@section('content')
    <!-- Ajax Sourced Server-side -->
    <section id="ajax-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h4 class="card-title">لیست پدیدآورنده ها</h4>
                    </div>
                    <div class="card-datatable">
                        <table class="datatables-ajax table table-responsive" id="creators">
                            <thead>
                            <tr>
                                <th>شناسه</th>
                                <th>عنوان</th>
                                <th>نوع</th>
                                <th>نامک</th>
                                <th>نام</th>
                                <th>نام خانوادگی</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/ Ajax Sourced Server-side -->
@endsection


@section('vendor-script')
    {{-- vendor files --}}
    <script src="{{ asset('admin/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/js/tables/datatable/responsive.bootstrap5.js') }}"></script>
    <script src="{{ asset('admin/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
@endsection

@section('page-script')
    {{-- Page js files --}}
    @php(
    $table_config = "
        ajax: {
            url: assetPath+dt_ajax_id,
        },
        columns: [
            {data: 'id', name: 'شناسه'},
            {data: 'title', name: 'عنوان'},
            {data: 'types', name: 'نوع'},
            {data: 'slug', name: 'نامک'},
            {data: 'first_name', name: 'نام'},
            {data: 'last_name', name: 'نام خانوادگی'},
        ],
         dom: 'lBfrtip',")
{{--    <script src="{{ asset('admin/js/scripts/tables/table-datatables-advanced.js') }}"></script>--}}
@endsection
