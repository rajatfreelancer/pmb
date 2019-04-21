<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoanScheme extends Model
{
    //
    const DURATION_40 = 40;
    const DURATION_180 = 180;

    public static function getDurationArray($id = null)
    {
        $list = [
            self::DURATION_40 => '40 Days',
            self::DURATION_180 => '180 Days',
        ];

        if ($id === null) {
            return $list;        
        }

        if(isset($list[$id])) {
            return $list[$id];
        }

        return $id;
    }
}
