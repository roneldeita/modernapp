<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Post extends Model
{
    protected $fillable = [
    	'postcategory_id',
    	'title',
    	'body'
    ];

    protected $appends = ['owner', 'shortbody', 'category', 'created', 'updated'];

    public function user(){

    	return $this->belongsTo('App\User');

    }

    public function postcategory(){

        return $this->belongsTo('App\Postcategory');

    }

    //example of accessors
    public function getShortbodyAttribute(){

        return str_limit($this->body, 55);

    }

    public function getCreatedAttribute(){

        $created = Carbon::parse($this->created_at)->diffForHumans();

        return $created;
    }
    
    public function getUpdatedAttribute(){

        $updated = Carbon::parse($this->updated_at)->diffForHumans();

        return $updated;
    }

    public function getOwnerAttribute(){

        return $this->user->name;

    }

    public function getCategoryAttribute(){

        return $this->postcategory->name;

    }

    public function comments(){

        return $this->morphMany('App\Comment', 'commentable');

    }

}
