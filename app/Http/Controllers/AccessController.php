<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Auth\Access;
use Illuminate\Support\Facades\Gate;
use App\User;
use App\Module;
use App\Method;

class AccessController extends Controller
{

    protected $view_access = 6;
    protected $update_access = 7;

    public function index(){
         
        if (Gate::allows('access', $this->view_access)) {

            $modules = Module::all();
            $users = User::all();

            return view('admin.access.index', compact('modules', 'users'));

        }

        return redirect('/admincontrol');

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

        if (Gate::allows('access', $this->update_access)) {

            $user = User::findOrFail($request->user_id);

            $user->methods()->attach($request->method_id);
        }

    }

    public function removeMethod(Request $request){

        if (Gate::allows('access', $this->update_access)) {

            $user = User::findOrFail($request->user_id);

            $user->methods()->detach($request->method_id);
        }

    }
     

}
