<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'confirmation_code'=>str_random(60)
        ]);
    }
    
    protected function registered()
    {
        /*
         * Additional codes
         * Creator: Ronel Deita
         */
        //use these data for the email
        $user = Auth::user();
        $email_data = [
            'name'=>$user['name'],
            'email'=>$user['email'],
            'confirmation_code'=>$user['confirmation_code']
        ];

        //now send it to the newly registered email
        Mail::send('email.verify', $email_data, function($message) use ($email_data) {
            $message->to($email_data['email'], $email_data['name'])->subject('Verify your email address');
        });
        //add sessions
        session()->flash('alert_type', 'success' );
        session()->flash('status', 'Thanks for signing up! Please check your email.' );
        /*
         * End of additional code
         */
    }
}
