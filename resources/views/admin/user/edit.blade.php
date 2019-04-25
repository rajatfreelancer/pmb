@extends('admin.layouts.admin_layout')
@section('content')

    @include('admin.layouts.admin_breadcum')

    <div class="wrapper wrapper-content">
        @include('admin.flash_message')

        <div class="row">

            <div class="col-lg-12">

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Edit User</h5>
                    </div>
                    <div class="ibox-content">
                        <form method="post" class="form-horizontal" action="{{ route('admin.user.update',$user->id) }}">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Name*</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="name"
                                           value="{{ old('name',$user->name) }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Last Name*</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="last_name"
                                           value="{{ old('last_name',$user->last_name) }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">S/D/W/O*</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="father_spouse"
                                           value="{{ old('father_spouse',$user->father_spouse) }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Contact Number*</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="number"
                                           value="{{ old('number',$user->number) }}" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Address</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="address"
                                           value="{{ old('address',$user->address) }}">
                                </div>

                            </div>


                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">City</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="city"
                                           value="{{ old('city',$user->city) }}">
                                </div>

                            </div>

                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">State</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="state"
                                           value="{{ old('state',$user->state) }}">
                                </div>

                            </div>

                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Country</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="country"
                                           value="{{ old('country',$user->country) }}">
                                </div>

                            </div>

                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Profile Picture</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="file" name="profile_picture">
                                </div>

                                <img src="{{ asset('uploads') }}/{{ $user->profile_pic }}" width="100px" height="100px">
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


