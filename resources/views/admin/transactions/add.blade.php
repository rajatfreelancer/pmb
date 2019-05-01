@extends('admin.layouts.admin_layout')
@section('content')
    @include('admin.layouts.admin_breadcum')

    <div class="wrapper wrapper-content">
        {{--@include('theme.flash_message')--}}
        @include('admin.flash_message')

        <div class="row">

            <div class="col-lg-12">

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Add Transaction</h5>
                    </div>
                    <div class="ibox-content">
                        <form method="post" class="form-horizontal" action="{{ route('admin.transactions.store') }}">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Amount*</label>
                                </div>
                                <div class="col-md-6">
                                <input type="text" name="amount" class="form-control" value="{{ old('amount') }}">


                                    <input type="hidden" name="account_id" value="{{ $id }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Method</label>
                                </div>
                                <div class="col-md-6">
                                    <select name="method" id="account_type" class="form-control">
                                    <option value="">Select Account Method</option>
                                    @foreach(App\AccountTransaction::getMethodOptions() as $type => $value)
                                    <option value="{{ $type }}">{{ $value }}</option>
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
@endsection