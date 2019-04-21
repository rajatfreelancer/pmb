<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>{{ @$title }}</h2>
        <ol class="breadcrumb">
            {{--<li>
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            </li>--}}
            @if(isset($maintitle))
                <li>
                    <a href="{{ route($mainlink) }}">{{$maintitle}}</a>
                </li>
            @endif
            @if(isset($title))
                <li class="active">
                    <strong>{{ @$title }}</strong>
                </li>
            @endif
        </ol>
    </div>
    <div class="col-lg-2">

    </div>
</div>