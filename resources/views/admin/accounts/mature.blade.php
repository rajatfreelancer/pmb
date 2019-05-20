@extends('admin.layouts.admin_layout')
@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    @include('admin.layouts.admin_breadcum')

    <div class="wrapper wrapper-content">
        {{--@include('theme.flash_message')--}}
        @include('admin.flash_message')

        <div class="row">

            <div class="col-lg-12">

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Mature Account</h5>
                    </div>
                    <div class="ibox-content">
                        <form method="post" class="form-horizontal" action="{{ route('admin.accounts.mature.store', $account->id) }}"  enctype="Multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Member Id*</label>
                                </div>
                                <div class="col-md-6">
                                <span class="form-control">{{ $account->user->name.' '.$account->user->last_name }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Account Type</label>
                                </div>
                                <div class="col-md-6">
                                    <span class="form-control">{{ $account->getTypeOptions($account->account_type) }}</span>
                                </div>
                            </div>
                            <div class="form-group" id="duration_div">
                                <div class="col-md-3">
                                    <label class="control-label">Duration(in Months)</label>
                                </div>
                                <div class="col-md-6">
                                     <span class="form-control">{{ $account->duration }}</span>
                                </div>
                            </div>

                            <div class="form-group" id="duration_div">
                                <div class="col-md-3">
                                    <label class="control-label">Policy Date</label>
                                </div>
                                <div class="col-md-6">
                                     <span class="form-control">{{ $account->policy_date }}</span>
                                 </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Interest Rate (in %)</label>
                                </div>
                                <div class="col-md-6">
                                    <span class="form-control">{{ $account->interest_rate }}</span>
                                </div>
                            </div>

                            <div class="form-group" id="duration_div">
                                <div class="col-md-3">
                                    <label class="control-label">Maturity Amount</label>
                                </div>
                                <div class="col-md-6">
                                     <span class="form-control">{{ $account->maturity_amount }}</span>
                                </div>
                            </div>
                            
                            <div class="form-group" id="duration_div">
                                <div class="col-md-3">
                                    <label class="control-label">Maturity Date</label>
                                </div>
                                <div class="col-md-6">
                                    <span class="form-control">{{ $account->maturity_date }}</span>
                                </div>
                            </div>
                             <div class="form-group" id="duration_div">
                                <div class="col-md-3">
                                    <label class="control-label">Actual Maturity Amount</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="actual_maturity_amount"/>
                                </div>
                            </div>
                             <div class="form-group" id="duration_div">
                                <div class="col-md-3">
                                    <label class="control-label">Actual Maturity Date</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control datepicker" name="actual_maturity_date"/>
                                </div>
                            </div>

                            <div class="form-group" id="duration_div">
                                <div class="col-md-3">
                                    <label class="control-label">Actual Interest Rate</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="actual_interest_rate"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Transfer to Account</label>
                                </div>
                                <div class="col-md-6">
                                <select title="None Selected" name="transfer_to" id="transfer_to" class="form-control transfer_to">
                                    @foreach($account->savingsAccounts() as $saving)
                                    <option value="{{ $saving->id }}">{{ $saving->account_number }} </option>
                                    @endforeach
                                </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Remarks</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="remarks" class="form-control" />
                                </div>
                            </div>
                            <button class="btn btn-primary" type="submit">Save</button>
                        </form>
                    </div>
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-lg-12 -->



        </div>
    </div>
    <!-- /.row -->
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script>
$(document).ready(function(){
    $(".datepicker").datepicker({format:"yyyy-mm-dd"});
    $('.users_select2').select2({
        ajax: {
    url: "{{ url('admin/get-users-list') }}",
    dataType: 'json',
    processResults: function (data) {
      // Tranforms the top-level key of the response object from 'items' to 'results'
      return {
        results: data.data
      };
    }
    // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
  }
    });

    $("#add_second_nominee").click(function(e){
        e.preventDefault();
        $("#second_nominee_div").toggle();
    });

    $('#first_nominee_share').keyup(function (e) {
        var share1 = $(this).val();
        if(isNaN(share1)) {
            alert('please enter only number');
            $(this).val('');
            return false;
        }
    });
    $('#first_nominee_share').blur(function (e) {

        var share1 = $(this).val();
        if(share1 > 100){
            alert('please enter equal or less then 100');
            $(this).val('');
            return false;
        }
        var share2 = 100- share1;
        $('#second_nominee_share').val(share2);
    });
    $("#account_type").change(function(){
        var val = $(this).val();    
        if (val != "{{ App\Account::TYPE_MONTHLY_INCOME }}" && val != "{{ App\Account::TYPE_LOAN }}"){    
            update_durations(val);
        }else{
            $("#duration_div").hide();
        }
    })

    $("#duration").change(function(){
        debugger;
        var val = $(this).val();
        var val = parseInt(val);
        var date = $("#policy_date").val();
        var second_date = new Date(date);
        var month = second_date.getMonth();

        second_date.setMonth(month + val);
        $("#maturity_date").datepicker("setDate", second_date);

    })

    function update_durations(val)
    {
        $.ajax({
            url:"{{ url('admin/get-duration') }}",
            type:"POST",
            data:{"_token":"{{ csrf_token() }}", 'val':val},
            success:function(res){
                var data = res.data;
                var html = "";
                for(key in data) {
                    html += '<option value="'+key+'">'+data[key]+'</option>';
                }
                $("#duration").html(html);
                $("#duration_div").show();
            }
        })
    }
})
</script>
@endsection