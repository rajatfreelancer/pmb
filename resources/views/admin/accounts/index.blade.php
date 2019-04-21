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
                            <a href="{{ route('admin.user.create') }}" class="btn btn-xs btn-primary">Add</a>
                        </span>
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">
                        <table width="100%" class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                            <tr>
                                <th>Member Id</th>
                                <th>Account Holder Name</th>
                                <th>Account No.</th>
                                <th>Father/Spouse Name</th>
                                <th>Address</th>
                                <th>Contact No.</th>
                                <th>BA ID</th>
                                <th>Installment Amount</th>
                                <th>Duration</th>
                                <th>Maturity Amount</th>
                                <th>Date of Issue</th>
                                <th>Account Type</th>
                                <th>Nominee Name</th>
                                <th>Date of Maturity</th>                                
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($users))
                                @foreach($users as $user)
                                    <tr class="odd gradeX">
                                        <td>{{ $user->ori_member_id }}</td>
                                        <td>{{ $user->name }} {{ $user->last_name }}</td>
                                        <td>{{ $user->ori_account_number }}</td>
                                        <td>{{ $user->father_spouse }}</td>
                                        <td>{{ $user->address }}</td>
                                        <td>{{ $user->number }}</td>
                                        <td>{{ $user->createUser->name }}</td>
                                        <td>{{ $user->account->denomination_amount }}</td>
                                        <td>{{ $user->account->duration }} Months</td>
                                        <td>{{ $user->account->maturity_amount }}</td>
                                        <td>{{ $user->created_at }}</td>
                                        <td>{{ $user->account->getTypeOptions($user->account->account_type) }}</td>
                                        <td>{{ $user->account->nominee_name}}</td>
                                        <td>{{ $user->account->maturity_date}}</td>
                                        <td><a href="{{ route('admin.user.edit',$user->id) }}" class="btn btn-xs btn-primary">Edit</a>
                                        <form action="{{ route('admin.user.destroy', $user->id) }}" method="post" onsubmit="return confirm('Do you really want to delete this product?')">
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