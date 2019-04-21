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
                        <form method="post" class="form-horizontal" action="{{ route('admin.staff.store') }}">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Name*</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="name"
                                           value="{{ old('name') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Last Name*</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="last_name"
                                           value="{{ old('last_name') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Father Spouse*</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="father_spouse"
                                           value="{{ old('father_spouse') }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Email*</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="email"
                                           value="{{ old('email') }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Password*</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="password" class="form-control" name="password"
                                           value="{{ old('password') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Address</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="address"
                                           value="{{ old('address') }}">
                                </div>

                            </div>

                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Number*</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="number"
                                           value="{{ old('number') }}" required>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">City</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="city"
                                           value="{{ old('city') }}">
                                </div>

                            </div>

                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">State</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="state"
                                           value="{{ old('state') }}">
                                </div>

                            </div>

                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Country</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="country"
                                           value="{{ old('country') }}">
                                </div>

                            </div>

                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Role*</label>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-control" name="role">
                                        @if(!empty($roles))
                                            @foreach($roles as $role)
                                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                                            @endforeach
                                        @endif
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
