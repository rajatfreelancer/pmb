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
    const SAVINGS_PREFIX = 'SV';
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
            Account::TYPE_SAVINGS => self::SAVINGS_PREFIX,
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

    public static function sendSms($numbers,$message){

        $test = "0";
        $username = env('TXTLOCAL_USERNAME');
        $hash = env('TXTLOCAL_HASH');

        // Data for text message. This is the text message data.
        $sender = env('SENDER'); // This is who the message appears to be from.
        //$numbers = "919643677327"; // A single number or a comma-seperated list of numbers
        //$message = "This is a test message from the PHP API script.";
        // 612 chars or less
        // A single number or a comma-seperated list of numbers
        $message = urlencode($message);
        $data = "username=".$username."&hash=".$hash."&message=".$message."&sender=".$sender."&numbers=".$numbers."&test=".$test;
        $ch = curl_init('http://api.textlocal.in/send/?');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch); // This is the result from the API
        curl_close($ch);

        return $result;
    }

    public static function getAddress()
    {
        return "Plot No. 144 Sector 39 west, Chandigarh - 160036 -- PMB001";
    }

    public function rules()
    {
        $array = [
            'name' => 'required|string',
            'last_name' => 'required|string',
            'number' => 'numeric|min:11',
            'father_spouse' => 'required|string',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
        ];

        return $array;
    }

    public function setData($data)
    {
        $this->name = $data->get('name');
        $this->last_name = $data->get('last_name');
        $this->number = $data->get('number');
        $this->email = $data->get('email');
        $this->father_spouse = $data->get('father_spouse');
        $this->address = $data->get('address');
        $this->city = $data->get('city');
        $this->state = $data->get('state');
        $this->country = $data->get('country');
        $this->role = $data->get('role');
        //$this->create_user_id = \Auth::guard('admins')->user()->id;
    }
    
}
