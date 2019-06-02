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
        <div class="col-md-12">
            <div class="col-md-12 row"><b style="text-align: center;width: 100%;">FIXED DEPOSIT</b></div>
            <div class="row">
            <div class="col-md-7">
                <div class="row">
                    <div class="col-md-12"><b>Member Name </b>{{ (!empty($account->user->name)) ? $account->user->name : '' }}</div>
                </div>
                <div class="row" style="height:20px"> </div>
                <div class="row">
                    <div class="col-md-12"><b>Address </b>{{ (!empty($account->user->address)) ? $account->user->address : '' }}</div>
                </div>
                <div class="row" style="height:20px"> </div>
                <div class="row">
                    <div class="col-sm-12" style="text-transform: capitalize;">{{ $account->denomination_amount_words }}</div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="row">
                    <div class="col-md-4"><b>Member Id</b></div>
                    <div class="col-sm-8">{{ $account->user->ori_member_id}}</div>
                </div>
                <div class="row">
                    <div class="col-md-4"><b>Issue Date</b></div>
                    <div class="col-sm-8">{{ $account->policy_date }}</div>
                </div>
                <div class="row">
                    <div class="col-md-4"><b>Due Date</b></div>
                    <div class="col-sm-8">{{ $account->maturity_date }}</div>
                </div>
                <div class="row">
                    <div class="col-sm-12"><b>Account No.</b>  {{ $account->ori_account_number }}</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table width="100%" class="table table-striped table-bordered table-hover dataTables-example dataTable no-footer">
                    <thead>
                        <tr>
                            <td>Receipt No.</td>
                            <td>Issue Date</td>
                            <td>Due Date</td>
                            <td>Time Period</td>
                            <td>Rate of Interest</td>
                            <td>Amount</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $transaction->transaction_id }}</td>
                            <td>{{ $account->policy_date }}</td>
                            <td>{{ $account->maturity_date }}</td>
                            <td>FD-{{ $account->duration }}</td>
                            <td>{{ $account->interest_rate }}</td>
                            <td>{{ $account->denomination_amount }}</td>
                        </tr>
                    </tbody>
                </table>                
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <b>Nominee Name</b>  {{ $account->nominee_name }} @if(!empty($account->second_nominee_name) && $account->second_nominee_name != "NULL")
                , {{ $account->second_nominee_name }}
                @endif
            </div>
            <div class="col-md-3">
                <b>Maturity Amount</b> {{ $account->maturity_amount }} 
            </div>
            <div class="col-md-5">
                <b>Matuirty Amount</b> {{ $account->maturity_amount_words }} 
            </div>
        </div>
        <div class="row">
            <div class="col-md-9"></div>            
            <div class="col-md-3" style="text-align: center;">
                <br><hr style="border:1px solid #000;">Office Seal</div>
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