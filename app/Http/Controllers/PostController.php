<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

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

    	$posts = Post::orderBy('id','desc')->get();

    	return response()->json($posts);

    }

    public function create(Request $request){

        $this->validate($request, [
            'postcategory_id' => 'required',
            'body' => 'required'
            ]);

        $user = Auth::user();
        
        $user->posts()->create($request->all());

        $response = [ 'msg' => 'New Category for Post created successfully' ];

        return response()->json($response);

    }

}
