<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    //
    const TYPE_DD = 1;
    const TYPE_RD = 2;
    const TYPE_FD = 3;    
    const TYPE_MONTHLY_INCOME = 4;
    const TYPE_LOAN = 5;
    const TYPE_SAVINGS = 6;

    const STATUS_ACTIVE = 1;
    const STATUS_CLOSED = 2;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected $appends = [
        'ori_account_number',
        'payable_amount',
        'term',
        'installment_number',
        'paid_installment',
        'unpaid_installment',
        'paid_amount',
        'text',
        'denomination_amount_words',
        'maturity_amount_words',   
        'required_amount'         
    ];

    public function getRequiredAmountAttribute()
    {
        $denomination_amount = $this->denomination_amount;
        $installment_number = $this->installment_number;
        $amount = $denomination_amount * $installment_number;
        return $amount;
    }

    public function getDenominationAmountWordsattribute()
    {
        $number = $this->denomination_amount;    
        $amount = $this->numberWords($number);   
        return $amount;
    }
    public function getMaturityAmountWordsattribute()
    {
        $number = $this->maturity_amount;    
        $amount =  $this->numberWords($number);   
        return $amount;
    }
    public function numberWords($number) {

        $decimal = round($number - ($no = floor($number)), 2) * 100;
        $hundred = null;
        $digits_length = strlen($no);
        $i = 0;
        $str = array();
        $words = array(0 => '', 1 => 'one', 2 => 'two',
            3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
            7 => 'seven', 8 => 'eight', 9 => 'nine',
            10 => 'ten', 11 => 'eleven', 12 => 'twelve',
            13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
            16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
            19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
            40 => 'forty', 50 => 'fifty', 60 => 'sixty',
            70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
        $digits = array('', 'hundred','thousand','lakh', 'crore');
        while( $i < $digits_length ) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += $divider == 10 ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
            } else $str[] = null;
        }
        $Rupees = implode('', array_reverse($str));
        $paise = ($decimal) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
        return ($Rupees ? 'Rupees '.$Rupees : '') . $paise.' Only';
    }
    public function getTextAttribute()
    {
        return $this->ori_account_number;
    }

    /*public function getMaturityAmountAttribute()
    {
        if($this->account_type == self::TYPE_DD) {
            $interest_amt = $this->denomination_amount;
            $days = $this->getDays($this->policy_date, $this->maturity_date);   
            /*$days = 5;         
            for($i = 1; $i <= $days; $i++) {                
                $interest_amt = $interest_amt + (int) ($interest_amt * $this->interest_rate/100);
                
            }
            $years = round($this->duration/12);
            $n = 1;
            $interest_amt = self::interest($this->denomination_amount, $years, $this->interest_rate, $n);            
            return round($interest_amt); 
        }

        return ($this->payable_amount + $this->payable_amount * $this->interest_rate/100);        
    }*/

    public static function interest($investment,$year,$rate=15,$n=1)
    {
        $accumulated=0;
        if ($year > 1){
                $accumulated=self::interest($investment,$year-1,$rate,$n);
                }
        $accumulated += $investment;
        $accumulated = $accumulated * pow(1 + $rate/(100 * $n),$n);
        return $accumulated;
        }

    public function getInstallmentNumberAttribute()
    {
        if ($this->account_type == self::TYPE_DD) {
            $no_of_inst = $this->getDays($this->policy_date, date("Y-m-d"));
        }else{
            $no_of_inst = $this->getMonths($this->policy_date, date("Y-m-d"));            
        }
        
        return $no_of_inst > 0 ? $no_of_inst : 0;
    }

    public function getPaidInstallmentAttribute()
    {
        if($this->account_type != self::TYPE_SAVINGS) {
            return $this->transactions->sum('amount')/$this->denomination_amount;
        }
        return 0;
    }

    public function getPaidAmountAttribute()
    {
        return $this->transactions->sum('amount');
    }

    public function getUnpaidInstallmentAttribute()
    {
        if($this->installment_number >= $this->paid_installment) {
            return $this->installment_number - $this->paid_installment;
        }
        return 0;
    }

    public function getOriAccountNumberAttribute()
    {
        return Admin::getTygetPrefixpeOptions($this->account_type).$this->prefix.$this->account_number;
    }

    public function getTermAttribute()
    {
        if ($this->account_type == Account::TYPE_DD){
            return $this->getDays($this->policy_date, $this->maturity_date).' Days';
        }
        return $this->duration.' months';
    }

    public function transactions()
    {
        return $this->hasMany(AccountTransaction::class, 'account_id')->where('method', AccountTransaction::METHOD_CREDIT)->orderBy('paid_date');
    }

    public function createUser()
    {
        return $this->belongsTo(Admin::class, 'create_user_id');
    }

    public function setData($request)
    {
        $this->nominee_name = $request->nominee_name;
        $this->nominee_relation = $request->nominee_relation;
        $this->nominee_share = $request->nominee_share;
        $this->second_nominee_name = (!empty($request->second_nominee_name)) ? $request->second_nominee_name : 'NULL';
        $this->second_nominee_relation = (!empty($request->second_nominee_relation)) ? $request->second_nominee_relation : 'NULL';
        $this->second_nominee_share = (!empty($request->second_nominee_share)) ? $request->second_nominee_share : 'NULL';
        $this->account_type = $request->account_type;
        $this->interest_rate = $request->interest_rate;
        $this->denomination_amount = $request->denomination_amount;
        $this->duration = $request->duration;
        $this->prefix = $this->getMemberPrefix();
        $this->account_number = $this->getAccountNumber();
        $this->create_user_id = \Auth::guard('admins')->user()->id;
        $this->user_id = $request->user_id;
        $this->policy_date = $request->policy_date;
        $this->message_facility = $request->message_facility;
        $this->maturity_date = $request->maturity_date ? $request->maturity_date : date("Y-m-d", strtotime($this->policy_date.' + '.$this->duration.' months'));        
        $this->maturity_amount = $request->maturity_amount;
        //$this->maturity_amount = $this->payable_amount + $this->payable_amount * $this->interest_rate/100;
        
       /* if($this->account_type == self::TYPE_DD) {
            $interest_amt = $this->denomination_amount;
            $days = $this->getDays($this->policy_date, $this->maturity_date);
            for($i = 1; $i <= $days; $i++) {
                $interest_amt = $interest_amt + $interest_amt * $this->interest_rate/100;
            }
            $this->maturity_amount = $interest_amt; 
        }*/        
        $this->status = Account::STATUS_ACTIVE;
    }

    public function getPayableAmountAttribute()
    {
        if($this->account_type == self::TYPE_DD) {
            $no_of_days = $this->getDays($this->policy_date, $this->maturity_date);
            return $this->denomination_amount * $no_of_days;
        }elseif($this->account_type == self::TYPE_RD) {
            return  $this->denomination_amount * $this->duration;
        }

        return $this->denomination_amount;
    }

    public function getMemberPrefix()
    {
        $last_account_no = Account::where('account_type', $this->account_type)->orderBy('id', 'DESC')->first();
        $member_prefix = str_pad(1, 3, '0', STR_PAD_LEFT);

        if($last_account_no) {
            $member_id = (int)$last_account_no->account_number;
            if ($member_id == 9999999) {
                $member_prefix = $member_prefix + 1;
            }
        }       

        return $member_prefix;
    }
    
    public function getAccountNumber()
    {
        $prefix = Admin::getTygetPrefixpeOptions($this->account_type);
        $last_account_no = Account::where('account_type', $this->account_type)->orderBy('id', 'DESC')->first();
        $account_no = str_pad(1, 7, '0', STR_PAD_LEFT); 
        if($last_account_no) {
            $account_no = (int)$last_account_no->account_number + 1;
            $account_no = str_pad($account_no, 7, '0', STR_PAD_LEFT); 
        }

        if ($account_no == 9999999) {
            $account_no = str_pad(1, 7, '0', STR_PAD_LEFT); 
        }

        return $account_no;
    }

    public static function getStatusOptions($id = null)
    {
        $list = [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_CLOSED => 'Closed',
        ];

        if ($id === null) {
            return $list;        
        }

        if(isset($list[$id])) {
            return $list[$id];
        }

        return $id;
    }

    public static function getTypeOptions($id = null)
    {
        $list = [
            self::TYPE_DD => 'Daily Deposit',
            self::TYPE_RD => 'Reccurring Deposit',
            self::TYPE_FD => 'Fixed Deposit',
            self::TYPE_SAVINGS => 'Savings',
            //self::TYPE_MONTHLY_INCOME => 'Monthly Income Scheme',
           // self::TYPE_LOAN => 'Loan Scheme',
        ];

        if ($id === null) {
            return $list;        
        }

        if(isset($list[$id])) {
            return $list[$id];
        }

        return $id;
    }

    public static function getAccountTypeModeOptions($id = null)
    {
        $list = [
            self::TYPE_DD => 'Dly',
            self::TYPE_RD => 'Mly',
            self::TYPE_FD => 'Fd',
            self::TYPE_MONTHLY_INCOME => 'Mly',
            self::TYPE_LOAN => 'Mly',
        ];

        if ($id === null) {
            return $list;        
        }

        if(isset($list[$id])) {
            return $list[$id];
        }

        return $id;
    }

    public static function getDurationOptions($id = null)
    {
        $list = [
            self::TYPE_DD => DDSetting::class,
            self::TYPE_RD => RDSetting::class,
            self::TYPE_FD => FDSetting::class,
            self::TYPE_MONTHLY_INCOME => MonthlyScheme::class,
            self::TYPE_LOAN => LoanScheme::class,
        ];

        if ($id === null) {
            return $list;        
        }

        if(isset($list[$id])) {
            return $list[$id]::getDurationArray();
        }

        return $id;
    }

    public static function getClassOptions($id = null)
    {
        $list = [
            self::TYPE_DD => DDSetting::class,
            self::TYPE_RD => RDSetting::class,
            self::TYPE_FD => FDSetting::class,
            self::TYPE_MONTHLY_INCOME => MonthlyScheme::class,
            self::TYPE_LOAN => LoanScheme::class,
        ];

        if ($id === null) {
            return $list;        
        }

        if(isset($list[$id])) {
            return $list[$id];
        }

        return $id;
    }

    public static function getDenominationArray($id)
    {
        $list = [
            self::TYPE_DD => DDSetting::class,
            self::TYPE_RD => RDSetting::class,
            self::TYPE_FD => FDSetting::class,
            self::TYPE_MONTHLY_INCOME => MonthlyScheme::class,
            self::TYPE_LOAN => LoanScheme::class,
        ];

        if ($id === null) {
            return $list;        
        }

        if(isset($list[$id])) {
            return $list[$id]::getDenominationArray();            
        }
        return $id;
    }

    public function rules()
    {
        $array = [
        'nominee_name' => 'required',
        ];

        return $array;
    }

    public static function getDays($from, $to)
    {
        $to = \Carbon\Carbon::createFromFormat('Y-m-d', $to);

        $from = \Carbon\Carbon::createFromFormat('Y-m-d', $from);

        $diff_in_days = $to->diffInDays($from);

        return $diff_in_days - 1;
    }

    public static function getMonths($from, $to)
    {
        $to = \Carbon\Carbon::createFromFormat('Y-m-d', $to);

        $from = \Carbon\Carbon::createFromFormat('Y-m-d', $from);

        $diff_in_days = $to->diffInMonths($from);

        return $diff_in_days;
    }
    
    public function savingsAccounts()
    {
        $accounts = Account::where("user_id", $this->user_id)->where('status', Account::STATUS_ACTIVE)->where('account_type', Account::TYPE_SAVINGS)->get();
        return $accounts;
    }

    public static function getTotalPaid($type)
    {
        if ($type == "") {
            $type = [Account::TYPE_DD, Account::TYPE_RD];
        }elseif(!is_array($type)){
            $type = array($type);
        }

        $amounts = AccountTransaction::whereHas('account', function($q) use($type) {
            $q->whereIn('account_type', $type);
        })->where('method', AccountTransaction::METHOD_CREDIT)->sum('amount');

        return $amounts;
    }

    public static function getTotalRequired($type)
    {
        if ($type == "") {
            $type = [Account::TYPE_DD, Account::TYPE_RD];
        }elseif(!is_array($type)){
            $type = array($type);
        }
        $required_amount = Account::whereIn('account_type', $type)->get()->sum('required_amount');
        return $required_amount;
    }

}
