<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Postcategory extends Model
{
    protected $fillable = ['name'];

    protected $appends = ['created', 'updated'];

    public function posts(){

        return $this->hasMany('App\Post');

    }

    public function getCreatedAttribute(){

    	$created = Carbon::parse($this->created_at)->diffForHumans();

    	return $created;

    }

    public function getUpdatedAttribute(){

    	$updated = Carbon::parse($this->updated_at)->diffForHumans();

    	return $updated;

    }
}
