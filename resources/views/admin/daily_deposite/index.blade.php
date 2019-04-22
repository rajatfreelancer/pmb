@extends('admin.layouts.admin_layout')
@section('content')

    {{--@include('theme.breadcrumbs')--}}
    @include('admin.layouts.admin_breadcum')

    <div class="wrapper wrapper-content">
        @include('admin.flash_message')

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        {{ @$title }}
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">
                        <table width="100%" class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                            <tr>
                                <th>Member Id</th>
                                <th>Account Type</th>
                                <th>Account No.</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        <!-- /.table-responsive -->
                        </div>
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-lg-12 -->
        </div>
    </div>
    <!-- /.row -->


@endsection
@section('js')
    <script type="text/javascript">
        var list_table_tab;
        var hidden_columns = true;

        $(function () {
            var tu = "{{ route('admin.daily.deposite.ajax') }}";
            if ($(window).width() <= 375) {
                $.fn.DataTable.ext.pager.numbers_length = 7;
            }

            var cols = [
                {"data": "member_id", "name": 'member_id'},
                {"data": "account_type", "name": 'account_type'},
                {"data": "account_number", "name": 'account_number'},
                {"data": "operations", "name": "operations", 'orderable': false, 'searchable': false},
                {"data": "updated_at", "name": "updated_at", 'searchable': false, 'visible': false}
            ];

            var order = [
                [5, 'desc']
            ];

            $("#list_table").DataTable({
                destroy: true,
                processing: true,
                stateSave: true,
                stateSaveParams: function (settings, data) {
                    data.start = 0;
                    data.search.search = "";
                    data.columns.forEach(function (a) {
                        a.search.search = "";
                    });
                    if (order.length > 0) {
                        if (order[0].length) {
                            data.order[0] = order[0][0];
                            data.order[1] = order[0][1];
                        }

                    }
                },
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                pagingType: "simple_numbers",
                paging: true,
                ordering: true,
                serverSide: true,
                ajax: tu,
                columns: cols,
                order: order
            });

        });
    </script>
@append