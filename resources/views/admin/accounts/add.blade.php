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
                        <h5>Add Account</h5>
                    </div>
                    <div class="ibox-content">
                        <form method="post" class="form-horizontal" action="{{ route('admin.accounts.store') }}"  enctype="Multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Member Id*</label>
                                </div>
                                <div class="col-md-6">
                                <select title="None Selected" name="user_id" id="users_select2" class="form-control users_select2" required>
                                    <option value="{{ $user->id }}" selected="selected">{{ $user->text }}</option>
                                </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Member Name*</label>
                                </div>
                                <div class="col-md-6">
                                <span id="member_name" class="member_name form-control"></span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Account Type</label>
                                </div>
                                <div class="col-md-6">
                                    <select name="account_type" id="account_type" class="form-control">
                                    <option value="">Select Account Type</option>
                                    @foreach(App\Account::getTypeOptions() as $type => $value) 
                                    <option value="{{ $type }}">{{ $value }}</option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" id="duration_div">
                                <div class="col-md-3">
                                    <label class="control-label">Duration(in Months)</label>
                                </div>
                                <div class="col-md-6">
                                <input type="text" class="form-control" name="duration" id="duration"/> 
                                </div>
                            </div>
                            <div class="form-group" id="policy_date_div">
                                <div class="col-md-3">
                                    <label class="control-label">Policy Date</label>
                                </div>
                                <div class="col-md-6">
                                <input type="text" class="form-control datepicker" value="{{ date('Y-m-d') }}" name="policy_date" id="policy_date" /> 
                                </div>
                            </div>

                            <div class="form-group" id="maturity_amount_div">
                                <div class="col-md-3">
                                    <label class="control-label">Maturity Amount</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="maturity_amount"/>
                                </div>
                            </div>
                            <div class="form-group" id="maturity_date_div">
                                <div class="col-md-3">
                                    <label class="control-label">Maturity Date</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control datepicker" value="{{ date('Y-m-d') }}" name="maturity_date" id="maturity_date" />
                                </div>
                            </div>

                            <div class="form-group" id="denomination_div">
                                <div class="col-md-3">
                                    <label class="control-label" id="denomination_amount_label">Denomination Amount</label>
                                </div>
                                <div class="col-md-6">
                                   <input type="text" class="form-control" name="denomination_amount"/>
                                </div>
                            </div>

                            <div class="form-group" id="interest_rate_div">
                                <div class="col-md-3">
                                    <label class="control-label">Interest Rate (in %)</label>
                                </div>
                                <div class="col-md-6">
                                   <input type="text" class="form-control" name="interest_rate"/>
                                </div>
                            </div>

                            <div class="form-group" id="nominee_name_div">
                                <div class="col-md-3">
                                    <label class="control-label">Message Facility</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="checkbox" class="form-control" name="message_facility"
                                           value="1">
                                </div>
                            </div>

                            <div class="form-group" id="nominee_name_div">
                                <div class="col-md-3">
                                    <label class="control-label">First Nominee Name</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="nominee_name"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group" id="nominee_relation_div">
                                <div class="col-md-3">
                                    <label class="control-label">First Nominee Relation</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="nominee_relation"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">First Nominee Share (in %)</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="nominee_share"
                                           value="100" max="100" id="first_nominee_share">
                                </div>
                            </div>
                            <button class="btn btn-primary" id="add_second_nominee">Add Second Nominee</button>
                            <div id="second_nominee_div" style="display:none;">
                                <div class="form-group">
                                    <div class="col-md-3">
                                        <label class="control-label">Second Nominee Name</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="second_nominee_name"
                                            value="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-3">
                                        <label class="control-label">Second Nominee Relation</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="second_nominee_relation"
                                            value="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-3">
                                        <label class="control-label">Second Nominee Share</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="second_nominee_share"
                                            value="" id="second_nominee_share">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Attach Documents</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="file" name="documents" multiple>
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
    
    $('.users_select2').on('select2:select', function (e) {
        var data = e.params.data;
        console.log(data);
        $("#member_name").html(data.name+' '+data.last_name);
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
                if(val == "{{ App\Account::TYPE_FD}}"){
                    $("#denomination_amount_label").html('Amount');
                    $("#denomination_div").show();
                    $("#duration_div").show();
                    $("#maturity_amount_div").show();
                    $("#interest_rate_div").show();
                    $("#interest_rate_div").show();
                }
                if(val == "{{ App\Account::TYPE_RD}}" || val == "{{ App\Account::TYPE_RD}}"){
                    $("#denomination_amount_label").html('Denomination Amount');
                    $("#denomination_div").show();
                    $("#duration_div").show();
                    $("#maturity_amount_div").show();
                    $("#interest_rate_div").show();
                    $("#interest_rate_div").show();
                }
                 if(val == "{{ App\Account::TYPE_SAVINGS}}"){
                    $("#denomination_div").hide();
                    $("#duration_div").hide();
                    $("#maturity_amount_div").hide();
                    $("#interest_rate_div").hide();
                    $("#interest_rate_div").hide();
                }
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