<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FDSetting extends Model
{
    //
    const DURATION_12 = 12;
    const DURATION_24 = 24;
    const DURATION_36 = 36;
    const DURATION_48 = 48;
    const DURATION_60 = 60;

    const DENOMINATION_AMT_5000 = 5000;
    const DENOMINATION_AMT_10000 = 10000;
    const DENOMINATION_AMT_20000 = 20000;
    const DENOMINATION_AMT_50000 = 50000;
    const DENOMINATION_AMT_100000 = 100000;
    
    public static function getDurationArray($id = null)
    {
        $list = [
            self::DURATION_12 => '12 Months',
            self::DURATION_24 => '24 Months',
            self::DURATION_36 => '36 Months',
            self::DURATION_48 => '48 Months',
            self::DURATION_60 => '60 Months',
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
            self::DURATION_12 => 9.5,
            self::DURATION_24 => 10,
            self::DURATION_36 => 11,
            self::DURATION_48 => 12,
            self::DURATION_60 => 12.5
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
            self::DENOMINATION_AMT_5000 => 'Rs. 5000',
            self::DENOMINATION_AMT_10000 => 'Rs. 10,000',
            self::DENOMINATION_AMT_20000 => 'Rs. 20,000',
            self::DENOMINATION_AMT_50000 => 'Rs. 50,000',
            self::DENOMINATION_AMT_100000 => 'Rs. 100,000',
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
        $maturity_amount = ($request->denomination_amount * $interest/100) + $request->denomination_amount;
        $data = [
            'account_type' => Account::TYPE_FD,
            'denomination_amount' => $request->denomination_amount,
            'duration' => $request->duration,
            'interest' => $interest,
            'maturity_amount' => $maturity_amount
        ];

        return json_encode($data);
    }
}
