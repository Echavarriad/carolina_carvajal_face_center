<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Jenssegers\Date\Date;

class User extends Authenticatable {

    use Notifiable;

    protected $fillable = [
        'name',        
        'lastname',              
        'document',
        'type_document',
        'mobile',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    protected $hidden = ['password', 'change_password'];

    public function getLastLoginAttribute($date) {
        return new Date($date);
    }

    public function getCreatedAtAttribute($date) {
        return new Date($date);
    }

    public function setPasswordAttribute($password) {
        $this->attributes['password'] = bcrypt($password);
    }

    public function orders(){
        return $this->hasMany(Order::class, 'customer_id')->with('items', 'status')->orderBy('order_date', 'DESC');
    }

    public function address(){
        return $this->hasMany(CustomerAddress::class, 'customer_id')->with('city', 'state')->orderBy('principal', 'DESC');
    }

    public function getFormatDateAttribute(){
        return $this->created_at->format('d') . '/' . $this->created_at->format('m') . '/' .  $this->created_at->format('Y') . ' ' . $this->created_at->format('h') . ':' .  $this->created_at->format('i') . ' ' .  $this->created_at->format('A');
    }

    public function changePassword($changePassword, $password){
        $this->change_password = $changePassword;
        $this->password = $password;

        $this->save();
    }

}

