<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Postcategory;
use Illuminate\Support\Facades\Gate;

class PostcategoryController extends Controller
{

    protected $view     = 14;
    protected $create 	= 15;
    protected $update 	= 16;
    protected $delete 	= 17;
    
    public function index(){

    	$access =(object) [
    			'create'=>true,
    			'update'=>true,
    			'delete'=>true
    		];

        if (!Gate::allows('access', $this->create)) {

           $access->create=false;
        }

        if (!Gate::allows('access', $this->update)) {

            $access->update=false;
        }

        if (!Gate::allows('access', $this->delete)) {

            $access->delete=false;

        }

    	if(Gate::allows('access', $this->view)){

    		return view('admin.post.category.index', compact('access'));

    	}

    	return redirect('/admincontrol');

    }

    public function data(){

    	$categories = Postcategory::all();

    	return response()->json($categories);

    }

}
