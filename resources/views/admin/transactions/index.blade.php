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
                        <span class="pull-right">
                            <a href="{{ route('admin.transactions.create') }}" class="btn btn-xs btn-primary">Add Installment</a>
                            <a href="{{ route('admin.transactions.export') }}" onclick="event.preventDefault();
                                                     document.getElementById('export-form').submit();"class="btn btn-xs btn-primary">Export</a>
                        </span>
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <form method="POST" action="{{ route('admin.account.export.post') }}" id="export-form">
                            @csrf()
                             <div class="form-group">
                                <div class="col-md-4">
                                    <label>Member Id</label>
                                <select title="None Selected" name="create_user_id" id="create_user_id" class="form-control" data-column="6">
                                <option value="">Select Staff</option>
                                    @foreach(App\Admin::all() as $admin)
                                        <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                                    @endforeach
                                </select>
                                </div>
                                <div class="col-md-4">
                                    <label>Start Date</label><br>
                               <input type="text" name="start_date" id="start_date" class="form-control datepicker">
                                </div>
                                <div class="col-md-4">
                                    <label>End Date</label><br>
                                <input type="text" name="end_date" id="end_date" class="datepicker form-control">
                                </div>
                            </div>
                        </form>
                            <div class="clearfix"></div>
                            <br>
                        <div class="table-responsive">
                        <table width="100%" class="table table-striped table-bordered table-hover dataTables-example" id="list_table">
                            <thead>
                            <tr>
                                <th>Applicant Name</th>
                                <th>Member Id</th>
                                <th>Account Code</th>
                                <th>Amount</th>
                                <th>Type</th>
                                <th>Date</th>
                                <th>Created By</th>
                                <th>Actions</th>
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
           var tu = "{{ route('admin.transactions.ajax.get.data') }}";
           /* if ($(window).width() <= 375) {
                $.fn.DataTable.ext.pager.numbers_length = 7;
            }*/

            var cols = [
                {"data": "applicant_name", "name": 'applicant_name'},
                {"data": "member_id", "name": 'member_id'},
                {"data": "account_code", "name": 'account_code'},                
                {"data": "amount", "name": 'amount'},
                {"data": "type", "name": 'type'},
                {"data": "paid_date", "name": 'paid_date'},
                {"data": "create_user_name", "name": 'create_user_name'},
                {"data": "actions", "name": "actions"},
                {"data": "updated_at", "name": "updated_at", 'searchable': false, 'visible': false}
            ];

            var order = [
                [8, 'desc']
            ];

            var list_table_tab = $("#list_table").DataTable({
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

            $(".datepicker").datepicker({format:"yyyy-mm-dd"});

            $(".datepicker").change(function(){
                var start_date = $("#start_date").val();
                var end_date = $("#end_date").val();
                var v = start_date+'&'+end_date;
                list_table_tab.columns(5).search(v, true, false).draw();
            });

            $("#create_user_id").change(function(){
                var i = $(this).attr('data-column');
                var v = $(this).val();
                var v = String(v);
                list_table_tab.columns(i).search(v, true, false).draw();

            })
        });
    </script>

@append