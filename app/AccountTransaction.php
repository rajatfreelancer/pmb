<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountTransaction extends Model
{
    //
    const METHOD_CREDIT = 1;
    const METHOD_DEBIT = 2;

    protected $appends = [
        'total',
        'total_left',
        'transaction_id'
    ];

    public function getTotalLeftAttribute()
    {
        $required_amount = AccountTransaction::whereDate('paid_date', '<=', $this->paid_date)->where('account_id', $this->account_id)->where('method', self::METHOD_CREDIT)->get()->sum('payable_amount');        
        $left = $this->total - $required_amount;
        return $left;
    }
    
        public function getTransactionIdAttribute()
    {
        return str_pad($this->id, 8, '0', STR_PAD_LEFT);
    }

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

