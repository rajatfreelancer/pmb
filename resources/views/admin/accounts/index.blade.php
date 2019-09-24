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
                            <a href="{{ route('admin.account.export', $type) }}" onclick="event.preventDefault();
                                                     document.getElementById('export-form').submit();"class="btn btn-xs btn-primary">Export</a>
                        </span>
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">
                        @if($type == "")
                        <form method="POST" action="{{ route('admin.account.export.post', $type) }}" id="export-form">
                            @csrf()
                             <div class="form-group">
                                <div class="col-md-4">
                                    <label>Staff Id</label>
                                <select title="None Selected" name="create_user_id" id="create_user_id" class="form-control" data-column="{{ $type == '' ? 13 : 0 }}">
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
                            @endif
                        <table width="100%" class="table table-striped table-bordered table-hover dataTables-example" id="list_table">
                            <thead>
                            <tr>
                                <th>Actions</th>
                                <th>Applicant Name</th>
                                <th>Policy Date</th>
                                <th>Policy Code</th>                                           
                                @if($type == "") 
                                <th>Amount</th>
                                <th>Term</th>
                                <th>Mode</th>                                
                                <th>Due Date</th>
                                <th>Inst No</th>
                                <th>Paid Inst</th>
                                <th>Unpaid Inst</th>
                                <th>Paid Amount</th>                                
                                <th>Req. Amount</th>
                                @elseif($type == App\Account::TYPE_FD)
                                    <th>Amount</th>
                                    <th>Term</th>
                                  <th>Maturity Date</th>     
                                  <th>Maturity Amount</th>     
                                  <th>Interest Rate</th>   
                                  <th>Actual Maturity Date</th>     
                                  <th>Actual Maturity Amount</th>   
                                  <th>Actual Interest Rate</th>  
                                  <th>Remarks</th>
                                  @elseif($type == App\Account::TYPE_MONTHLY_INCOME)
                                  <th>Amount</th>
                                  <th>Monthly Installment</th>
                                  <th>Maturity Date</th>
                                @endif
                                <th>Status</th>   
                                @if($type == App\Account::TYPE_SAVINGS)                                
                                <td>Balance</td>
                                @endif
                                <th>Created By</th> 
                            </tr>
                            </thead>
                           <tbody>                           
                            </tbody>   
                             @if($type == "")                          
                            <tfoot>
                                <td></td>
                                 <td></td>
                                <td></td>
                                <td></td>                                           
                                @if($type == "") 
                                <td id="sum_amt"></td>
                                <td></td>
                                <td></td>                                
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td id="sum_paid_amt"></td>                                
                                <td id="sum_req_amt"></td>
                                @elseif($type == App\Account::TYPE_FD)
                                    <td></td>
                                    <td></td>
                                  <td></td>     
                                  <td></td>     
                                  <td></td>   
                                  <td></td>     
                                  <td></td>   
                                  <td></td>  
                                  <td></td>  
                                @endif
                                @if($type == App\Account::TYPE_SAVINGS)                                
                                <td></td>
                                @elseif($type == App\Account::TYPE_MONTHLY_INCOME)
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                @endif
                                <td></td>   
                                <td></td> 
                                
                            </tfoot>
                            @endif
                        </table>
                        <!-- /.table-responsive -->
                        </div>
                        @if($type == "")
                        <div class="data_table_balance">
                <p>
                    <span class="d_table_label"> Grand Total Paid Amount: </span>
                    @php $paid = App\Account::getTotalPaid($type); @endphp
                    <span class="d_table_amount">Rs.{{ $paid }}</span><span class="d_table_amount" id="amount_paid"></span><br>

                    <span class="d_table_label"> Grand Total Required Amount: </span>
                    <span class="d_table_amount">Rs.{{  App\Account::getTotalRequired($type) }}</span><span class="d_table_amount" id="amount_paid"></span>
                </p>
            </div>
                        @endif
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

    <script src="https://cdn.datatables.net/plug-ins/1.10.12/api/sum().js"></script>
    <script>
       var list_table_tab;
       var hidden_columns = true;

       $(function () {
           var tu = "{{ route('admin.ajax.get.data') }}";
           var type = "{{ isset($type) ? $type : '' }}";
           /* if ($(window).width() <= 375) {
                $.fn.DataTable.ext.pager.numbers_length = 7;
            }*/

            if(type == "") {
                var cols = [
                    {"data": "actions", "name": "actions"},
                    {"data": "applicant_name", "name": 'applicant_name'},
                    {"data": "policy_date", "name": 'policy_date'},
                    {"data": "policy_code", "name": 'policy_code'},
                    {"data": "amount", "name": 'amount'},
                    {"data": "term", "name": 'term'},
                    {"data": "account_type_mode", "name": 'account_type_mode'},
                    {"data": "maturity_date", "name": 'maturity_date'},
                    {"data": "installment_number", "name": 'installment_number'},
                    {"data": "paid_installment", "name": 'paid_installment'},
                    {"data": "unpaid_installment", "name": 'unpaid_installment'},
                    {"data": "paid_amount", "name": 'paid_amount'},
                    {"data": "required_amount", "name": 'required_amount'},
                    {"data": "status", "name": "status"},                    
                    {"data": "create_user_name", "name": "create_user_name"},                   
                    {"data": "created_at", "name": "created_at", 'searchable': false, 'visible': false}
                ];

                var order = [
                    [13, 'desc']
                ];
            }else if(type == "{{ App\Account::TYPE_FD }}") {
                var tu = "{{ $url }}";
                var cols = [
                    {"data": "actions", "name": "actions"},
                    {"data": "applicant_name", "name": 'applicant_name'},
                    {"data": "policy_date", "name": 'policy_date'},
                    {"data": "policy_code", "name": 'policy_code'},
                    {"data": "amount", "name": 'amount'},
                    {"data": "term", "name": 'term'},
                    {"data": "maturity_date", "name": 'maturity_date'},
                    {"data": "maturity_amount", "name": 'maturity_amount'},
                    {"data": "interest_rate", "name": 'interest_rate'},
                    {"data": "actual_maturity_date", "name": 'actual_maturity_date'},
                    {"data": "actual_maturity_amount", "name": 'actual_maturity_amount'},
                    {"data": "actual_interest_rate", "name": 'actual_interest_rate'},
                    {"data": "status", "name": "status"},  
                    {"data": "create_user_name", "name": "create_user_name"},
                    {"data": "remarks", "name": 'remarks'},
                    {"data": "created_at", "name": "created_at", 'searchable': false, 'visible': false}
                ];

                var order = [
                    [13, 'desc']
                ];
            }else if(type == "{{ App\Account::TYPE_SAVINGS}}") {

                var tu = "{{ $url }}";
                var cols = [
                    {"data": "actions", "name": "actions"},
                    {"data": "applicant_name", "name": 'applicant_name'},
                    {"data": "policy_date", "name": 'policy_date'},
                    {"data": "policy_code", "name": 'policy_code'},
                    {"data": "status", "name": "status"},          
                    {"data": "balance", "name": 'balance'},                              
                    {"data": "created_at", "name": "created_at", 'searchable': false, 'visible': false}
                ];

                var order = [
                    [5, 'desc']
                ];
            }else if(type == "{{ App\Account::TYPE_MONTHLY_INCOME}}") {      
                var tu = "{{ $url }}";        
                var cols = [
                    {"data": "actions", "name": "actions"},
                    {"data": "applicant_name", "name": 'applicant_name'},
                    {"data": "policy_date", "name": 'policy_date'},
                    {"data": "policy_code", "name": 'policy_code'},                       
                    {"data": "denomination_amount", "name": 'denomination_amount'},  
                    {"data": "monthly_amount", "name": 'monthly_amount'},                        
                    {"data": "maturity_date", "name": 'maturity_date'},        
                    {"data": "status", "name": "status"},                    
                    {"data": "created_at", "name": "created_at", 'searchable': false, 'visible': false}
                ];

                var order = [
                    [5, 'desc']
                ];
            }

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
                order: order,
                drawCallback:function(){
                    var api = this.api(); 
                    if (type == "") {
                    var sum_amt = api.column(4, {page:'current'} ).data().sum();
                    var sum_paid_amt = api.column(11, {page:'current'} ).data().sum();
                    var sum_req_amt = api.column(12, {page:'current'} ).data().sum();
                    $("#sum_amt").html(sum_amt);
                    $("#sum_paid_amt").html(sum_paid_amt);
                    $("#sum_req_amt").html(sum_req_amt);
                    }

                },
                /*
                drawCallback:function() {
                    if(type == ""){
                    var total = this.api().columns(10).data().reduce(function (a, b){
                        var a = parseInt(a);
                        var b = parseInt(b);
                        return eval(a) + eval(b);
                    }, 0);
                    $("#amount_paid").html(total);
                    }
                }*/
            });
            $(".datepicker").datepicker({format:"yyyy-mm-dd"});

            $(".datepicker").change(function(){
                var start_date = $("#start_date").val();
                var end_date = $("#end_date").val();
                var v = start_date+'&'+end_date;
                list_table_tab.columns(1).search(v, true, false).draw();
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
