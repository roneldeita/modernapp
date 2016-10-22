<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\User;
use App\Module;
use App\Method;

class AccessController extends Controller
{

    public function index(){
        
        $modules = Module::all();
        $users = User::all();

        return view('admin.access.index', compact('modules', 'users'));

    }

    private function json($data){

     	return response()->json($data);

    }

    public function getMethods(Request $request){

    	$methods = Method::where('module_id', '=', $request->id)->get();

    	return $this->json($methods);
    }

    public function getMethodUser(Request $request){

     	$access = User::find($request->id);

     	if($access){

     		return $this->json($access->methods);

     	}
     	
     	return $this->json(null);

     }

    public function addMethod(Request $request){

     	$user = User::findOrFail($request->user_id);

     	$user->methods()->attach($request->method_id);

    }

    public function removeMethod(Request $request){

     	$user = User::findOrFail($request->user_id);

     	$user->methods()->detach($request->method_id);

    }
     

}
