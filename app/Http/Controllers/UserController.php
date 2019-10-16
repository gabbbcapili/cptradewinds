<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Hash;
use App\User;
use Auth;
use App\Utils\ValidatorUtil;
use Illuminate\Mail\Mailer;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;

use App\Order;
use App\OrderDetails;
use App\OrderKey;
use App\OrderLogs;
//utils

class UserController extends Controller
{
    //
	public function changePasswordForm(){
		return vieW('user.changePassword');
	}

	public function changePasswordUpdate(Request $request){
		if (!(Hash::check($request->get('CurrentPassword'), Auth::user()->password))) {
			return response()->json(['error' => ['CurrentPassword' => 'Your entry does not match your current password.']]);
		}
		$validator = Validator::make($request->all(), [
			'CurrentPassword' => 'required',
			'NewPassword' => 'required|min:6',
			'ConfirmPassword' => 'required|same:NewPassword',
		]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()]);
        }
        $user = request()->user();
        $user->password = Hash::make($request->NewPassword);
        $user->save();
        return response()->json(['success' => 'Successfully changed password!']);

	}

	public function edit(Request $request){
		$cities = User::cities();
		$user = $request->user();
		return view('user.edit', compact('user', 'cities'));
	}

	public function update(Request $request){

		$validator = Validator::make($request->all(), [
            // 'name' => ['required', 'string', 'max:255'],
            // 'last_name' => ['required', 'string', 'max:255'],
            'phone_no' => ['sometimes','required', 'max:255', 'regex:/^(09|\+639)\d{9}$/'],
            'city' => ['sometimes','required', 'string', 'max:255'],
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        $request->user()->update($request->all());

        $request->session()->flash('status', 'Successfully updated profile!');
        
        return response()->json(['success' => 'success']);
	}
	public function buyers()
    {
                $buyers = User::where('role', 'customer')->get();
        return view('/user/buyers',[
                'buyers'=>$buyers]);
    }
    public function suppliers()
    {
       
		$suppliers = User::where('role' , 'supplier')->get();

        return view('/user/suppliers',['suppliers' =>$suppliers]);
    }
}

