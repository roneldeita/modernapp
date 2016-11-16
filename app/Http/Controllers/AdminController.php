<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\Post;
use App\Module;
use App\Theme;

class AdminController extends Controller
{
    // public function __construct(){

    // 	$this->middleware('admin');

    // }

    public function index(){

    	$info = [
    		'users'=>User::count(),
    		'posts'=>Post::count(),
    		'modules'=>Module::count(),
    		'themes'=>Theme::count(),

    	];

    	return view('admin.index', compact('info'));

    }
}
