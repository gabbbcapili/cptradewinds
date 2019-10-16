<?php

namespace App\Http\Controllers;

use App\Dollar;
use Illuminate\Http\Request;
use Validator;
class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dollar = Dollar::first();
        return view('settings.index', compact('dollar'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Dollar  $dollar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dollar $dollar)
    {
        $validator = Validator::make($request->all(), ['rate' => 'required|regex:/^\d{0,3}(\.\d{1,2})?$/'], ['rate.regex' => 'The rate field format should be ###.##']);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
            }


        if ($validator->passes()){
            $dollar->update([
                'rate' => $request->input('rate')
            ]);
            request()->session()->flash('status', 'Successfully updated dollar rate!');
            return response()->json(['success' =>  'success']);
        }
    }
}
