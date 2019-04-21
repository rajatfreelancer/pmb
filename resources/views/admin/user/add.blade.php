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
                        <h5>Add User</h5>
                    </div>
                    <div class="ibox-content">
                        <form method="post" class="form-horizontal" action="{{ route('admin.user.store') }}" enctype="Multipart/form-data">
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
                                    <label class="control-label">S/D/W/O*</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="father_spouse"
                                           value="{{ old('father_spouse') }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Contact Number*</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="number"
                                           value="{{ old('number') }}" required>
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
                                           value="India">
                                </div>
                            </div>                           
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Profile Picture</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="file" name="profile_picture">
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
<script>
$(document).ready(function(){
    $("#account_type").change(function(){
        var val = $(this).val();    
        if (val != "{{ App\Account::TYPE_MONTHLY_INCOME }}" && val != "{{ App\Account::TYPE_LOAN }}"){    
            update_durations(val);
        }else{
            $("#duration_div").hide();
        }
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