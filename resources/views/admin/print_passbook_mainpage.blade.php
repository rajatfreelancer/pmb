<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <style>


        @media print {
            .page-break	{ display: block; page-break-before: always; }
            .print_setting { display: block; page-break-before: always; }
           /* .print_setting{-webkit-transform: rotate(-90deg) scale(.68,.68);
                -moz-transform:rotate(-90deg) scale(.58,.58);
                zoom: 200%;size: landscape;}*/

        }
    </style>

    <title>PassBook</title>
</head>
<body class="print_setting">

<div class="container" style="line-height: 1.3; margin: 380px 5px 5px 5px;height: 304px;width: 100%;">
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
                <div class="col-md-3"><b>Member Id</b></div>
                <div class="col-sm-3">{{ $account->user->ori_member_id}}</div>
                <div class="col-sm-3"><b>Account No</b></div>
                <div class="col-sm-3">{{ $account->ori_account_number }}</div>
            </div>
            <div class="col-md-12 row">
                <div class="col-md-3"><b>Account Holder Name</b></div>
                <div class="col-sm-3">{{ (!empty($account->user->name)) ? $account->user->name : '' }}</div>
                <div class="col-sm-3"><b>S/D/Wo</b></div>
                <div class="col-sm-3">{{ (!empty($account->user->father_spouse)) ? $account->user->father_spouse : '' }}</div>
            </div>
            <div class="col-md-12 row">
                <div class="col-md-3"><b>Address</b></div>
                <div class="col-sm-9">{{ (!empty($account->user->address)) ? $account->user->address : '' }}</div>
            </div>
            <br>
            <div class="col-md-12 row">
                <div class="col-md-3"><b>Contact No.</b></div>
                <div class="col-sm-3">{{ (!empty($account->user->number)) ? $account->user->number : '' }}</div>
                <div class="col-sm-3"><b>BA ID</b></div>
                <div class="col-sm-3">{{ $account->user->createUser->name }}</div>
            </div>
            <div class="col-md-12 row">
                <div class="col-md-3"><b>Installemnt Ammount</b></div>
                <div class="col-sm-9">{{ (!empty($account->denomination_amount)) ? $account->denomination_amount : '' }}</div>
            </div>
            <div class="col-md-12 row">
                <div class="col-md-3"><b>Maturity Ammount.</b> </div>
                <div class="col-sm-3">{{ (!empty($account->maturity_amount)) ? $account->maturity_amount : '' }} (On Regular A/C)</div>
                <div class="col-sm-3"><b>CA ID</b></div>
                <div class="col-sm-3">{{ $account->createUser->name }}</div>
            </div>
            <br>
            <div class="col-md-12 row">
                <div class="col-md-3"><b>Date OF Issue.</b> </div>
                <div class="col-sm-3">{{ $account->policy_date }}</div>
                <div class="col-sm-3"><b>Account Type</b></div>
                <div class="col-sm-3">{{ (!empty($account->account_type)) ? $account->getTypeOptions($account->account_type) : '' }}</div>
            </div>
            <div class="col-md-12 row">
                <div class="col-md-3"><b>Nominee Name.</b> </div>
                <div class="col-sm-3">{{ (!empty($account->nominee_name)) ? $account->nominee_name : '' }}</div>
                <div class="col-md-3"><b>Date OF Maturity.</b> </div>
                <div class="col-sm-3">{{ (!empty($account->maturity_date)) ? $account->maturity_date : '' }}</div>
            </div>
            <div class="col-md-12 row">
                <div class="col-md-3"><b>Branch Address</b></div>
                <div class="col-sm-9">{{ App\Admin::getAddress() }}</div>
            </div>
        </div>
        <div class="col-md-3">
        <div class="picture_div" style="height:200px; border:1px solid #000;width: 150px">
            @if(!empty($account->user->profile_pic))
                <img width="50%" height="150px" src="{{ asset('uploads') }}/{{ $account->user->profile_pic }}">
            @endif
</div>

            <br>
            <br>
            <br>
            <br>
            <div>Auth Sign & Stamp</div>
        </div>
    </div>
</div>


{{--<div class="container page-break">
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Date</th>
            <th scope="col">Particular/Transaction ID</th>
            <th scope="col">Credit Amount</th>
            <th scope="col">Debit Amount</th>
            <th scope="col">Total Amount</th>
        </tr>
        </thead>
        <tbody>
        @if(!empty($transactions))
            @foreach($transactions as $transaction)
        <tr>
            <th scope="row">{{ \Carbon\Carbon::parse($transaction->paid_date)->format('d-m-Y') }}</th>
            <td>{{ $transaction->id }}</td>
            @if($transaction->method == \App\AccountTransaction::MEDTHOD_CREDIT)
                <td>{{ $transaction->amount }}</td>
                <td></td>
            @else
                <td></td>
                <td>{{ $transaction->amount }}</td>
            @endif
            <td>{{ $transaction->total }}</td>
        </tr>
        @endforeach
        @endif
        </tbody>
    </table>
</div>--}}

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