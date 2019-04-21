<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MonthlyScheme extends Model
{
    //
    const DURATION_60 = 60;
    
    public static function getDurationArray($id = null)
    {
        $list = [
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
}
