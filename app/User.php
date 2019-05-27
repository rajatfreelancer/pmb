<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;

    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     *
     */
    protected $fillable = [
        'name', 'email', 'password','father_spouse', 'number','address','last_name','city','state','country', 'member_id', 'create_user_id'
    ];

    protected $appends = [
        'ori_member_id',
        'text'
    ];

    public function account()
    {
        return $this->hasOne(Account::class, 'user_id');
    }
    
    public function createUser()
    {
        return $this->belongsTo(Admin::class, 'create_user_id');
    }

    public function getTextAttribute()
    {
        return Admin::MEMBER_PREFIX.$this->prefix.$this->member_id;
    }

    public function getOriMemberIdAttribute()
    {
        return Admin::MEMBER_PREFIX.$this->prefix.$this->member_id;
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /*public function rules()
    {
        $array = [
            'email' => 'required|email|unique:users,email,'.$this->id.',id',
            'name' => 'required|string',
            'number' => 'numeric|min:0',
        ];

        if ($this->id == null) {
            $array['email'] = 'required|email|unique:users,email';
            $array['password'] = 'required|string|min:6';
        }

        return $array;
    }*/

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
        $this->create_user_id = \Auth::guard('admins')->user()->id;
    }

    public function getMemberPrefix()
    {
        $last_member_id = User::orderBy('id', 'DESC')->first();
        $member_prefix = str_pad(1, 3, '0', STR_PAD_LEFT);

        if($last_member_id) {
            $member_id = (int)$last_member_id->member_id;
            if ($member_id == 9999999) {
                $member_prefix = $member_prefix + 1;
            }
        }       

        return $member_prefix;
    }
    
    public function getMemberId()
    {
        $prefix = Admin::MEMBER_PREFIX;

        $last_member_id = User::orderBy('id', 'DESC')->first();
        $member_id = str_pad(1, 7, '0', STR_PAD_LEFT); 
        if($last_member_id) {
            $member_id = (int)$last_member_id->member_id + 1;
            $member_id = str_pad($member_id, 7, '0', STR_PAD_LEFT); 
        }

        if ($member_id == 9999999) {
            $member_id = str_pad(1, 7, '0', STR_PAD_LEFT); 
        }

        return $member_id;
    }

}
