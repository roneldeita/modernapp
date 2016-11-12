<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Comment extends Model
{
    protected $fillable = ['body', 'commentable_id', 'commentable_type'];

    protected $appends = ['created', 'owner'];

    public function commentable(){

    	return $this->morphTo();

    }

    public function user(){

    	return $this->belongsTo('App\User');

    }

    //example of accessors
    public function getCreatedAttribute(){

        $created = Carbon::parse($this->created_at)->diffForHumans();

        return $created;
    }

    public function getOwnerAttribute(){

    	$owner = $this->user->name;

    	return $owner;

    }
    
}
