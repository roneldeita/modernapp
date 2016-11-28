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
        'photo_id', 'theme_id', 'name', 'email', 'password', 'confirmed', 'confirmation_code'
    ];

    protected $appends = ['created', 'updated', 'profile_picture'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $photo_dir ='/images/profile_picture/';
    protected $avatar    ='avatar-placeholder.jpg';

    public function photo(){
        return $this->belongsTo('App\Photo');
    }

    public function comments(){

        return $this->hasMany('App\Comments');
    }

    public function posts(){

        return $this->hasMany('App\Post');

    }

    public function theme(){

        return $this->belongsTo('App\Theme');
    
    }

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

    public function getProfilepictureAttribute(){

        if($this->photo === null){

            $filename = $this->avatar;

        }else{

            $filename = $this->photo['filename'];

        }

        return $this->photo_dir . $filename;
    }

}
