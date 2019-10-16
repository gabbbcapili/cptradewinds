<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

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
     * Where to redirect users after registration.
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
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone_no' => ['sometimes','required', 'string', 'max:255', 'regex:/^(09|\+639)\d{9}$/'],
            // 'province' => ['sometimes','required', 'string', 'max:255'],
            'city' => ['sometimes','required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'regex:/^(?:[0-9]+[a-z]|[a-z]+[0-9])[a-z0-9]*$/i', 'min:6' , 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        if(!in_array(request()->input('role'), ['supplier', 'customer'])){
            abort(403);
        }
        return User::create([
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone_no' => array_key_exists("phone_no", $data) ? $data['phone_no'] : null ,
            // 'province' => array_key_exists("province", $data) ? $data['province'] : null ,
            'city' => array_key_exists("city", $data) ? $data['city'] : null ,
            'password' => Hash::make($data['password']),
            'role' => request()->input('role')
        ]);
        
    }
}
