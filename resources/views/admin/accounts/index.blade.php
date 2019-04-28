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
                        Accounts
                        <span class="pull-right">
                            <a href="{{ route('admin.accounts.create') }}" class="btn btn-xs btn-primary">Add</a>
                        </span>
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">
                        <table width="100%" class="table table-striped table-bordered table-hover dataTables-example" id="list_table">
                            <thead>
                            <tr>
                                <th>Applicant Name</th>
                                <th>Policy Date</th>
                                <th>Policy Code</th>
                                <th>Amount</th>
                                <th>Term</th>
                                <th>Mode</th>                                
                                <th>Due Date</th>
                                <th>Inst No</th>
                                <th>Paid Inst</th>
                                <th>Unpaid Inst</th>
                                <th>Paid Amount</th>                                
                                <th>Req. Amount</th>
                                <th>Actions</th>
                               {{-- <th>Contact No.</th>--}}
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
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script>
       var list_table_tab;
       var hidden_columns = true;

       $(function () {
           var tu = "{{ route('admin.ajax.get.data') }}";
           /* if ($(window).width() <= 375) {
                $.fn.DataTable.ext.pager.numbers_length = 7;
            }*/

            var cols = [
                {"data": "applicant_name", "name": 'applicant_name'},
                {"data": "policy_date", "name": 'policy_date'},
                {"data": "policy_code", "name": 'policy_code'},
                {"data": "amount", "name": 'amount'},
                {"data": "term", "name": 'term'},
                {"data": "account_type", "name": 'account_type'},
                {"data": "maturity_date", "name": 'maturity_date'},
                {"data": "installment_number", "name": 'installment_number'},
                {"data": "paid_installment", "name": 'paid_installment'},
                {"data": "unpaid_installment", "name": 'unpaid_installment'},
                {"data": "paid_amount", "name": 'paid_amount'},
                {"data": "required_amount", "name": 'required_amount'},
                {"data": "actions", "name": "actions"},
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
                //pagingType: "simple_numbers",
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