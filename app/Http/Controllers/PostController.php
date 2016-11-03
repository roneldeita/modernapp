<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\User;

class PostController extends Controller
{
    
    public function index(){

    	$posts = Post::find(1);
    	$user = User::find(1);

    	return view('admin.post.index', compact('posts', 'user'));

    }

    public function data(){

    	$posts = Post::all();

    	return response()->json($posts);

    }

}
