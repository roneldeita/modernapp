<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

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

    protected $appends = ['created', 'updated'];

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

    //example of accessors
    public function getCreatedAttribute(){

        $created = Carbon::parse($this->created_at)->diffForHumans();

        return $created;
    }
    
    public function getUpdatedAttribute(){

        $updated = Carbon::parse($this->updated_at)->diffForHumans();

        return $updated;
    }

}
