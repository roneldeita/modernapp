<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\User;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{

	protected $view     = 10;
    protected $create 	= 11;
    protected $update 	= 12;
    protected $delete 	= 13;
    
    public function index(){

    	$posts = Post::find(1);
    	$user = User::find(1);

    	if(Gate::allows('access', $this->view)){

    		return view('admin.post.index', compact('posts', 'user'));

    	}

    	return redirect('/admincontrol');

    }

    public function data(){

    	$posts = Post::all();

    	return response()->json($posts);

    }

}
