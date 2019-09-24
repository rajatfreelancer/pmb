<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element"> <span>
                            <img alt="image" class="img-circle" width="50px" height="50px"src="{{ asset('img/logo.jpeg') }}" />
                             </span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">Admin</strong>
                             </span> <span class="text-muted text-xs block">CEO<b class="caret"></b></span> </span> </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">

                        <li>
                        <a class="dropdown-item" href="{{ route('admin.logout') }}"
                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">

                            <i class="fa fa-sign-out"></i> Log out
                        </a>
                        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        </li>
                    </ul>
                </div>
                <div class="logo-element">
                    IN+
                </div>
            </li>
            <li class="active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li><a href="{{ route('admin.user.index') }}">Customer</a></li>
            @if($auth_user->hasRole('Admin'))
                <li><a href="{{ route('admin.staff.index') }}">Staff</a></li>
            @endif
            <!--li><a href="{{ route('admin.local.backup') }}">DB BackUp</a></li>
            <li><a href="{{ route('admin.txt.send.sms') }}">Send Sms</a></li-->
            <li><a href="{{ route('admin.accounts') }}">RD/DD Accounts</a></li>
            <li><a href="{{ route('admin.accounts.fd') }}">FD Accounts</a></li>
            <li><a href="{{ route('admin.accounts.savings') }}">Savings Accounts</a></li>
            <li><a href="{{ route('admin.accounts.mis') }}">MIS</a></li>
            <li><a href="{{ route('admin.transactions.index') }}">Transactions</a></li>
        </ul>

    </div>
</nav>