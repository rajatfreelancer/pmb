<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Hello, world!</title>
</head>
<body>

<div class="container" style="margin-top: 150px">
    <div class="row">
        <div>
            <p> <a id="print" class="btn btn-primary ui button blue">Print</a>
                <a id="back" class="btn btn-primary ui button blue" href="{{ route('admin.accounts') }}">Back</a>
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-9">
            <div class="col-md-12 row">
                <div class="col-md-3">Member Id</div>
                <div class="col-sm-3">{{ $account->user->ori_member_id}}</div>
                <div class="col-sm-3">Account No</div>
                <div class="col-sm-3">{{ $account->ori_account_number }}</div>
            </div>
            <div class="col-md-12 row">
                <div class="col-md-3">Account Holder Name</div>
                <div class="col-sm-3">{{ (!empty($account->user->name)) ? $account->user->name : '' }}</div>
                <div class="col-sm-3">S/D/Wo</div>
                <div class="col-sm-3">{{ (!empty($account->user->father_spouse)) ? $account->user->father_spouse : '' }}</div>
            </div>
            <div class="col-md-12 row">
                <div class="col-md-3">Address</div>
                <div class="col-sm-9">{{ (!empty($account->user->address)) ? $account->user->address : '' }}</div>
            </div>
            <br>
            <div class="col-md-12 row">
                <div class="col-md-3">Contact No. </div>
                <div class="col-sm-3">{{ (!empty($account->user->number)) ? $account->user->number : '' }}</div>
                <div class="col-sm-3">BA ID</div>
                <div class="col-sm-3">{{ $account->user->createUser->name }}</div>
            </div>
            <div class="col-md-12 row">
                <div class="col-md-3">Installemnt Ammount</div>
                <div class="col-sm-9">{{ (!empty($account->denomination_amount)) ? $account->denomination_amount : '' }}</div>
            </div>
            <div class="col-md-12 row">
                <div class="col-md-3">Maturity Ammount. </div>
                <div class="col-sm-3">{{ (!empty($account->maturity_amount)) ? $account->maturity_amount : '' }} (On Regular A/C)</div>
                <div class="col-sm-3">CA ID</div>
                <div class="col-sm-3">{{ $account->createUser->name }}</div>
            </div>
            <br>
            <div class="col-md-12 row">
                <div class="col-md-3">Date OF Issue. </div>
                <div class="col-sm-3">{{ $account->policy_date }}</div>
                <div class="col-sm-3">Account Type</div>
                <div class="col-sm-3">{{ (!empty($account->account_type)) ? $account->getTypeOptions($account->account_type) : '' }}</div>
            </div>
            <div class="col-md-12 row">
                <div class="col-md-3">Nominee Name. </div>
                <div class="col-sm-3">{{ (!empty($account->nominee_name)) ? $account->nominee_name : '' }}</div>
                <div class="col-md-3">Date OF Maturity. </div>
                <div class="col-sm-3">{{ (!empty($account->maturity_date)) ? $account->maturity_date : '' }}</div>
            </div>
            <div class="col-md-12 row">
                <div class="col-md-3">Branch Address</div>
                <div class="col-sm-9"></div>
            </div>
        </div>
        <div class="col-md-3">
            @if(!empty($account->user->profile_pic))
                <img width="50%" height="150px" src="{{ asset('uploads') }}/{{ $account->user->profile_pic }}">
            @endif


            <br>
            <br>
            <br>
            <br>
            <div>Auth Sign & Stamp</div>
        </div>
    </div>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script>
    $("#print").click(function () {
        $(this).hide();
        $("#back").hide();
        window.print();
        $(this).show();
        $("#back").show();

    });
</script>
</body>
</html>