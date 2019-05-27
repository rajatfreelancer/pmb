<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountTransaction extends Model
{
    //
    const METHOD_CREDIT = 1;
    const METHOD_DEBIT = 2;

    protected $appends = [
        'total'
    ];

    public function createUser()
    {
        return $this->belongsTo(Admin::class, 'create_user_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function getTotalAttribute()
    {
        $transaction = AccountTransaction::whereDate('paid_date', '<=', $this->paid_date)->where('account_id', $this->account_id)->where('method', self::METHOD_CREDIT)->sum('amount');
        return $transaction;
    }

    public static function getMethodOptions($id = null)
    {
        $list = [
            self::METHOD_CREDIT => 'CREDIT',
            self::METHOD_DEBIT => 'DEBIT',
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
        $this->method = $request->method ? $request->method : self::METHOD_CREDIT; 
        $this->paid_date = $request->paid_date ? date("Y-m-d H:i:s", strtotime($request->paid_date)) : date("Y-m-d H:i:s");
        $this->create_user_id = $request->create_user_id ? $request->create_user_id : \Auth::guard('admins')->user()->id;
    }
}

