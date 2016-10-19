<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Method extends Model
{
    protected $fillable = ['module_id', 'name'];

    public function module(){

    	return $this->belongsTo('App\Module');

    }
}
