<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Gate;
use App\User;

class UserController extends Controller
{
    protected $view     = 4;
    protected $create 	= 5;
    protected $update 	= 6;
    protected $delete 	= 7;

    public function index(){

    	if(Gate::allows('access', $this->view)){

    		$users = User::all();

    		return view('admin.user.index', compact('users'));

    	}

    	return redirect('/admincontrol');
    	
    }

}
