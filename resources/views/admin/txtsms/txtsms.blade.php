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
                        <h5>{{ @$title }}</h5>
                    </div>
                    <div class="ibox-content">
                        <form method="post" class="form-horizontal" action="{{ route('admin.send.sms') }}">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Number*</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="number"
                                           value="{{ old('number') }}" placeholder="mobile number start with 91">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Message*</label>
                                </div>
                                <div class="col-md-6">
                                    <textarea type="text" class="form-control" name="message"></textarea>
                                </div>
                            </div>
                            <input type="submit" value="submit">
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection