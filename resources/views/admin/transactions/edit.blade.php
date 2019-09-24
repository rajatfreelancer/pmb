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
                        <h5>Edit Transaction- {{ $transaction->id }} </h5>
                        <span class="pull-right">
                            <a href="{{ route('admin.transactions.index') }}" class="btn btn-xs btn-primary">Transactions</a>
                        </span>
                    </div>
                    <div class="ibox-content">
                        <form method="post" class="form-horizontal" action="{{ route('admin.transactions.update', $transaction->id) }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="PUT">
                            @if(empty($id))
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Member Id*</label>
                                </div>
                                <div class="col-md-6">
                                <select title="None Selected" name="user_id" id="users_select2" class="form-control users_select2" required>       
                                    <option value="{{ $transaction->account->user->id }}" selected="selected">{{ $transaction->account->user->text }}</option>
                                </select>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Account Id*</label>
                                </div>
                                <div class="col-md-6">
                                <select title="None Selected" name="account_id" id="accounts_select2" class="form-control accounts_select2" required>                   <option value="{{ $transaction->account_id }}" selected="selected">{{ $transaction->account->text }}</option>                 
                                </select>
                                </div>
                            </div>
                            @else
                            <input type="hidden" name="account_id" value="{{ $id }}">
                            @endif

                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Amount*</label>
                                </div>
                                <div class="col-md-6">
                                <input type="text" name="amount" class="form-control" value="{{ old('amount', $transaction->amount) }}">
                                    
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Paid Date*</label>
                                </div>
                                <div class="col-md-6">
                                <input type="text" name="paid_date" class="form-control datepicker" value="{{ old('paid_date', date('Y-m-d', strtotime($transaction->paid_date))) }}">
                                </div>
                            
                            </div>

                            @if($auth_user->hasRole('Admin'))
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">CA ID</label>
                                </div>
                                <div class="col-md-6">
                                    <select name="create_user_id" id="create_user_id" class="form-control">
                                    <option value="">Select CA</option>
                                    @foreach(App\Admin::where('id', '!=', $auth_user->id) as $id => $name)
                                    <option value="{{ $id }}" {{ $id == $transaction->create_user_id }}>{{ $id.'-'.$name }}</option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Method</label>
                                </div>
                                <div class="col-md-6">
                                    <select name="method" id="account_type" class="form-control">
                                    <option value="">Select Account Method</option>
                                    @foreach(App\AccountTransaction::getMethodOptions() as $type => $value)
                                    <option value="{{ $type }}" {{ $type == $transaction->method ? 'selected' : ''}}>{{ $value }}</option>
                                    @endforeach
                                    </select>
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
    $(".datepicker").datepicker({'format':'yyyy-mm-dd'});
    $('.users_select2').select2({
        ajax: {
    url: "{{ url('admin/get-users-list') }}",
    dataType: 'json',
    processResults: function (data) {
      // Tranforms the top-level key of the response object from 'items' to 'results'
      return {
        results: data.data
      };
    },
    // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
  }
    });
$('.users_select2').on('select2:select', function (e) {
    var data = e.params.data;
    console.log(data);
        var val = data.id;
     $('.accounts_select2').select2({        
        ajax: {
    url: "{{ url('admin/get-accounts-list') }}/"+val,
    dataType: 'json',
    processResults: function (data) {
      // Tranforms the top-level key of the response object from 'items' to 'results'
      return {
        results: data.data
      };
    },
    // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
  }
    });
     });
})

</script>
@endsection