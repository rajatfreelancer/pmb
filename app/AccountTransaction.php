<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountTransaction extends Model
{
    //
    const MEDTHOD_CREDIT = 1;
    const MEDTHOD_DEBIT = 2;


    public static function getMethodOptions($id = null)
    {
        $list = [
            self::MEDTHOD_CREDIT => 'CREDIT',
            self::MEDTHOD_DEBIT => 'DEBIT',
        ];

        if ($id === null) {
            return $list;
        }

        if(isset($list[$id])) {
            return $list[$id];
        }

        return $id;
    }

    public function rules()
    {
        $array = [
            'amount' => 'required',
            'method'=>'required',
        ];

        return $array;
    }

    public function setData($request)
    {
        $this->amount = $request->amount;
        $this->account_id = $request->account_id;
        $this->method = $request->method;
        $this->create_user_id = \Auth::guard('admins')->user()->id;
    }
}

