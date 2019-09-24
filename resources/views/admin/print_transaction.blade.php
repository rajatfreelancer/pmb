<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="{{ asset('css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">
    <style>


        @media print {
            .page-break { display: block; page-break-before: always; }
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
            <p id="hide_div"> 
                Account No: {{ $account->ori_account_number}}
                Account Name: {{ $account->user->name }}({{ $account->user->ori_member_id}})<br>
                Hide Transactions before
                <input type="text" class="datepicker" value="{{ $account->policy_date }}" id="start_date" name="start_date" />
                <!-- <br>Show First Row
                <input type="checkbox" value="1" id="check_first_row" name="check_first_row"  checked="" /><br>-->
                <a id="print" class="btn btn-primary ui button blue">Print</a>
                <a id="back" class="btn btn-primary ui button blue" href="{{ route('admin.accounts') }}">Back</a>
            </p>

        </div>
    </div>
    <div class="row">
        <table style="width:100%;">
       <!--  <thead>
        <tr class="first_row" style="border-top: 1px solid #000";border-bottom: 1px solid #000;">
            <th scope="col" style="width:20%;border-right: 0px solid #000;">Date</th>
            <th scope="col" style="width:20%;border-right: 0px solid #000;">Particular/Transaction ID</th>
            <th scope="col" style="width:20%;border-right: 0px solid #000;">Credit Amount</th>
            <th scope="col" style="width:20%;border-right: 0px solid #000;">Debit Amount</th>
            <th scope="col" style="width:20%;border-right: 0px solid #000;">Total Amount</th>
        </tr>
        </thead> -->
        <tbody>
             @if(!empty($transactions))
            @foreach($transactions as $transaction)
        <tr class="transactions_row">         
            <td style="width:20%;border-right: 0x solid #000;"> {{ \Carbon\Carbon::parse($transaction->paid_date)->format('d-m-Y') }}
                <input type="hidden" id="date_val" value="{{ strtotime($transaction->paid_date) }}"></td>
            <td scope="row" style="width:20%;border-right: 0px solid #000;"> {{ $transaction->transaction_id }}</td>           
           
            @if($transaction->method == \App\AccountTransaction::METHOD_CREDIT)
                <td style="width:20%;border-right: 0px solid #000;">{{ $transaction->amount }}</td>
                <td style="width:20%;border-right: 0px solid #000;"></td>
            @else
                <td style="width:20%;border-right: 0px solid #000;"></td>
                <td style="width:20%;border-right: 0px solid #000;">{{ $transaction->amount }}</td>
            @endif
            <td style="width:20%;border-right: 0px solid #000;">{{ $transaction->total }}</td>
        </tr>
        @endforeach
        @endif
        </tbody>
    </table>
         
    </div>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="{{ asset('js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>

<script>
    $(".datepicker").datepicker({format:"yyyy-mm-dd"});

    $("#check_first_row").change(function () {
        if($(this).is(":checked")) {
        $(".first_row").show();
        }else{
            $(".first_row").hide();
        }
    });

    $("#print").click(function () {
        $(this).hide();
        $("#hide_div").hide();
        $("#back").hide();
        window.print();
        $(this).show();
        $("#back").show();
        $("#hide_div").show();
    });

        $("#start_date").change(function(){
            var val = $(this).val();
            var time = new Date(val);
            var ms = time.valueOf();
            var s = ms / 1000;
            $(".transactions_row").each(function(){
                var date = $(this).find("#date_val").val();
                if (date < s) {
                    $(this).hide();
                }else{
                    $(this).show();
                }
            })
        })
</script>
</body>
</html>