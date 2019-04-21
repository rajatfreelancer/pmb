<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DDSetting extends Model
{
    //
    const DURATION_12 = 12;
    const DURATION_24 = 24;
    const DURATION_36 = 36;

    const DENOMIATION_AMT_50 = 50;
    const DENOMINATION_AMT_100 = 100;
    const DENOMINATION_AMT_200 = 200;
    const DENOMINATION_AMT_300 = 300;
    const DENOMINATION_AMT_400 = 400;
    const DENOMINATION_AMT_500 = 500;
    const DENOMINATION_AMT_1000 = 1000;

    const INTEREST_DURATION_12 = 8;
    const INTEREST_DURATION_24 = 10;
    const INTEREST_DURATION_36 = 11;
    
    public static function getDurationArray($id = null)
    {
        $list = [
            self::DURATION_12 => '12 Months',
            self::DURATION_24 => '24 Months',
            self::DURATION_36 => '36 Months',
        ];

        if ($id === null) {
            return $list;        
        }

        if(isset($list[$id])) {
            return $list[$id];
        }

        return $id;
    }

    public static function getDenominationArray($id = null)
    {
        $list = [
            self::DENOMIATION_AMT_50 => 'Rs. 50',
            self::DENOMINATION_AMT_100 => 'Rs. 100',
            self::DENOMINATION_AMT_200 => 'Rs. 200',
            self::DENOMINATION_AMT_300 => 'Rs. 300',
            self::DENOMINATION_AMT_400 => 'Rs. 400',
            self::DENOMINATION_AMT_500 => 'Rs. 500',
            self::DENOMINATION_AMT_1000 => 'Rs. 1000',
        ];

        if ($id === null) {
            return $list;        
        }

        if(isset($list[$id])) {
            return $list[$id];
        }

        return $id;
    }

    public static function getInterestArray($id = null)
    {
        $list = [
            self::DURATION_12 => self::INTEREST_DURATION_12,
            self::DURATION_24 => self::INTEREST_DURATION_24,
            self::DURATION_36 => self::INTEREST_DURATION_36,
        ];

        if ($id === null) {
            return $list;        
        }

        if(isset($list[$id])) {
            return $list[$id];
        }

        return $id;
    }

    public static function getAccountDetails($request)
    {
        $interest = self::getInterestArray($request->duration);
        $deposit =  $request->denomination_amount * $request->duration;
        $maturity_amount = ($deposit * $interest/100) + $deposit;
        $data = [
            'account_type' => Account::TYPE_DD,
            'denomination_amount' => $request->denomination_amount,
            'duration' => $request->duration,
            'interest' => $interest,
            'deposit_amount' => $deposit,
            'maturity_amount' => $maturity_amount
        ];

        return json_encode($data);
    }

}
