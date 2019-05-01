<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountTransaction extends Model
{
    //
    const MEDTHOD_CREDIT = 1;
    const MEDTHOD_DEBIT = 2;

    protected $appends = [
        'total'
    ];

    public function getTotalAttribute()
    {
        $transaction = AccountTransaction::whereDate('paid_date', '<=', $this->paid_date)->where('account_id', $this->account_id)->where('method', self::MEDTHOD_CREDIT)->sum('amount');
        return $transaction;
    }

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
            'paid_date'=>'required',
        ];

        return $array;
    }


    public function setData($request)
    {
        $this->amount = $request->amount;
        $this->account_id = $request->account_id;
        $this->method = $request->method ? $request->method : self::MEDTHOD_CREDIT; 
        $this->paid_date = $request->paid_date ? date("Y-m-d H:i:s", strtotime($request->paid_date)) : date("Y-m-d H:i:s");
        $this->create_user_id = $request->create_user_id ? $request->create_user_id : \Auth::guard('admins')->user()->id;
    }
}

