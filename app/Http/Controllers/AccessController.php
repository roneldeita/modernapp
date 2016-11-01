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

    protected $view     = 2;
    protected $update   = 3;

    public function index(){
         
        if (Gate::allows('access', $this->view)) {

            $modules = Module::orderBy('name','asc')->get();
            $users = User::orderBy('name','asc')->get();

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

            if(Gate::allows('access', $this->update)) {

                return $this->json(array($access->methods, [ 'disable'=>false ]));

            }else{

                return $this->json(array($access->methods, [ 'disable'=>true ]));
                
            }

        }

        return $this->json(array(null, [ 'disable'=>true ]));
     	
     }

    public function addMethod(Request $request){

        if (Gate::allows('access', $this->update)) {

            $user = User::findOrFail($request->user_id);

            $attach = $user->methods()->attach($request->method_id);

        }


    }

    public function removeMethod(Request $request){

        if (Gate::allows('access', $this->update)) {

            $user = User::findOrFail($request->user_id);

            $detach=$user->methods()->detach($request->method_id);

        }


    }
     

}
