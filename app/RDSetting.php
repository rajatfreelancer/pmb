<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RDSetting extends Model
{
    //
    const DURATION_12 = 12;
    const DURATION_24 = 24;
    const DURATION_36 = 36;
    
    const DENOMINATION_AMT_500 = 500;
    const DENOMINATION_AMT_1000 = 1000;
    const DENOMINATION_AMT_2000 = 2000;
    const DENOMINATION_AMT_3000 = 3000;
    const DENOMINATION_AMT_4000 = 4000;
    const DENOMINATION_AMT_5000 = 5000;
    const DENOMINATION_AMT_10000 = 10000;
      
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
            self::DENOMINATION_AMT_500 => 'Rs. 500',
            self::DENOMINATION_AMT_1000 => 'Rs. 1000',
            self::DENOMINATION_AMT_2000 => 'Rs. 2000',
            self::DENOMINATION_AMT_3000 => 'Rs. 3000',
            self::DENOMINATION_AMT_4000 => 'Rs. 4000',
            self::DENOMINATION_AMT_5000 => 'Rs. 5000',
            self::DENOMINATION_AMT_10000 => 'Rs. 10,000',
        ];

        if ($id === null) {
            return $list;        
        }

        if(isset($list[$id])) {
            return $list[$id];
        }

        return $id;
    }
    
    public static function getMaturityAmount($duration, $denomination)
    {
        $list[self::DURATION_12] = [
            self::DENOMINATION_AMT_500 => 6335,
            self::DENOMINATION_AMT_1000 => 12670,
            self::DENOMINATION_AMT_2000 => 25340,
            self::DENOMINATION_AMT_3000 => 38010,
            self::DENOMINATION_AMT_4000 => 50680,
            self::DENOMINATION_AMT_5000 => 63350,
            self::DENOMINATION_AMT_10000 => 126700,
        ];

        $list[self::DURATION_24] = [
            self::DENOMINATION_AMT_500 => 13475,
            self::DENOMINATION_AMT_1000 => 26950,
            self::DENOMINATION_AMT_2000 => 53900,
            self::DENOMINATION_AMT_3000 => 80849,
            self::DENOMINATION_AMT_4000 => 107798,
            self::DENOMINATION_AMT_5000 => 134750,
            self::DENOMINATION_AMT_10000 => 269500,
        ];

        $list[self::DURATION_36] = [
            self::DENOMINATION_AMT_500 => 21600,
            self::DENOMINATION_AMT_1000 => 43200,
            self::DENOMINATION_AMT_2000 => 86400,
            self::DENOMINATION_AMT_3000 => 129600,
            self::DENOMINATION_AMT_4000 => 172800,
            self::DENOMINATION_AMT_5000 => 216000,
            self::DENOMINATION_AMT_10000 => 432000,
        ];

        if(isset($list[$duration][$denomination])) {
            return $list[$duration][$denomination];
        }
        return $list;
    }


    public static function getAccountDetails($request)
    {
        $maturity_amount = self::getMaturityAmount($request->duration, $request->denomination_amount);
        $deposit =  $request->denomination_amount * $request->duration;
        $data = [
            'account_type' => Account::TYPE_RD,
            'denomination_amount' => $request->denomination_amount,
            'duration' => $request->duration,
            'deposit_amount' => $deposit,
            'maturity_amount' => $maturity_amount
        ];

        return json_encode($data);
    }
}
