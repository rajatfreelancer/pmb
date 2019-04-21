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

    protected $appends = [
        'ori_account_number',
        'maturity_date',
        'maturity_amount'
    ];

    public function getOriAccountNumberAttribute()
    {
        return Admin::getTygetPrefixpeOptions($this->account_type).$this->prefix.$this->account_number;
    }

    public function getMaturityAmountAttribute()
    {
        $details = json_decode($this->account_type_details, true);
        return $details['maturity_amount'];

    }

    public function getMaturityDateAttribute()
    {
        $duration = $this->duration;
        $date = date("Y-m-d", strtotime("+".$this->duration.' months'));
        return $date;
    }

    public function setData($request)
    {
        $this->nominee_name = $request->nominee_name;
        $this->nominee_relation = $request->nominee_relation;
        $this->nominee_share = $request->nominee_share;
        $this->second_nominee_name = $request->second_nominee_name;
        $this->second_nominee_relation = $request->second_nominee_relation;
        $this->second_nominee_share = $request->second_nominee_share;        
        $this->account_type = $request->account_type;
        $this->interest_rate = $request->interest_rate;
        $this->denomination_amount = $request->denomination_amount;
        $this->duration = $request->duration;
        $this->prefix = $this->getMemberPrefix();
        $this->account_number = $this->getAccountNumber();
        $this->create_user_id = \Auth::guard('admins')->user()->id;   
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

    public static function getTypeOptions($id = null)
    {
        $list = [
            self::TYPE_DD => 'Daily Deposit',
            self::TYPE_RD => 'Reccurring Deposit',
            self::TYPE_FD => 'Fixed Deposit',
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


}
