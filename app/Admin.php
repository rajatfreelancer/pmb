<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use App\Account;

class Admin extends Authenticatable
{
    use HasRoles;

    const DD_PREFIX = 'DD';
    const RD_PREFIX = 'RD';
    const FD_PREFIX = 'FD';
    const MN_PREFIX = 'MN';
    const LN_PREFIX = 'LN';
    const MEMBER_PREFIX = 'M';
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function getTygetPrefixpeOptions($id = null)
    {
        $list = [
            Account::TYPE_DD => self::DD_PREFIX,
            Account::TYPE_RD => self::RD_PREFIX,
            Account::TYPE_FD => self::FD_PREFIX,
            Account::TYPE_MONTHLY_INCOME => self::MN_PREFIX,
            Account::TYPE_LOAN => self::LN_PREFIX,
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
