<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

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

    public function methods(){

        return $this->belongsToMany('App\Method');
    }

    public function access($method_id){

        $user = Auth::user();

        $method_ids =array();

        foreach($user->methods as $method){

            array_push($method_ids, $method->id);
        }

        if(in_array($method_id, $method_ids)){

            return true;

        }

        return false;

    }

}
