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
                        User

                        <span class="pull-right">
                            <a href="{{ route('admin.staff.create') }}" class="btn btn-xs btn-primary">Add</a>
                        </span>
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">
                        <table width="100%" class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($staffs))
                                @foreach($staffs as $staff)
                                    <tr class="odd gradeX">
                                        <td>{{ $staff->name }} {{ $staff->last_name }}</td>
                                        <td>{{ $staff->email }}</td>
                                        <td>{{ $staff->number }}</td>
                                        <td>{{ $staff->role }}</td>
                                        {{--<td>{{ $user->mobile }}</td>--}}
                                        {{--<td>@if($customer->status == 'inactive') No @else Yes @endif</td>--}}
                                        <td><a href="{{ route('admin.staff.edit',$staff->id) }}" class="btn btn-xs btn-primary">Edit</a>
                                        <form action="{{ route('admin.staff.destroy', $staff->id) }}" method="post" onsubmit="return confirm('Do you really want to delete this staff?')">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <button type="submit" class="btn btn-xs btn-danger">Delete</button>

                                        </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
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

@append