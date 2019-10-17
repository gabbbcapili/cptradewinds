<?php

namespace App\Http\Controllers;

use App\Source;
use Illuminate\Http\Request;
use Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Utilities;

class SourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
       $source = Source::where('is_deleted', false)
           ->orderBy('updated_at', 'desc');
            return Datatables::eloquent($source)
            ->addColumn('action', function(Source $source) {
                            $html = Utilities::editButton(action('SourceController@edit', [$source->id]));
                            $html .= Utilities::deleteButton(action('SourceController@delete', [$source->id]));
                            return $html;
                        })
            ->addColumn('quotations', function(Source $source) {
                           $route =  route('quotationcreate') .'?source='. $source->name;
                           return '<button class="btn btn-warning copy" data-copy="'. $route .'"> '. $source->quotations->count()  .' </button>';
                        })
            ->addColumn('shipments', function(Source $source) {
                            $route =  route('shipmentcreate') .'?source='. $source->name;
                           return '<button class="btn btn-success copy" data-copy="'. $route .'"> '. $source->shipments->count()  .' </button>';
                        })
            ->rawColumns(['shipments', 'quotations', 'action'])
            ->make(true);
        }
        return view('source.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('source.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $validator = Validator::make($request->all(), ['name' => ['required', 'unique:source,name']]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
            }
        try {
            DB::beginTransaction();
            Source::create($request->all());
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Source added successfully!',
                        'redirect' => action('SourceController@index')
                    ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). " Line:" . $e->getLine(). " Message:" . $e->getMessage());
            $output = ['success' => 0,
                        'msg' => env('APP_DEBUG') ? $e->getMessage() : 'Sorry something went wrong, please try again later.'
                    ];
             DB::rollBack();
        }
        return response()->json($output);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Source  $source
     * @return \Illuminate\Http\Response
     */
    public function show(Source $source)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Source  $source
     * @return \Illuminate\Http\Response
     */
    public function edit(Source $source)
    {
        return view('source.edit', compact('source'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Source  $source
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Source $source)
    {
        $validator = Validator::make($request->all(), ['name' => ['required', 'unique:source,name,' . $source->id]]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
            }
        try {
            DB::beginTransaction();
            $data = $request->all();
            $source->touch();
            $source = $source->update($data);
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Source updated successfully!'
                    ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). " Line:" . $e->getLine(). " Message:" . $e->getMessage());
            $output = ['success' => 0,
                        'msg' => env('APP_DEBUG') ? $e->getMessage() : 'Sorry something went wrong, please try again later.'
                    ];
             DB::rollBack();
        }
        return response()->json($output);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Source  $source
     * @return \Illuminate\Http\Response
     */
    public function destroy(Source $source)
    {
        try {
            DB::beginTransaction();
            $source->update(['is_deleted' => true]);
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Source successfully deleted!'
                    ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). " Line:" . $e->getLine(). " Message:" . $e->getMessage());
            $output = ['success' => 0,
                        'msg' => env('APP_DEBUG') ? $e->getMessage() : 'Sorry something went wrong, please try again later.'
                    ];
             DB::rollBack();
        }
        return response()->json($output);
    }

    public function delete(Source $source){
        $action = action('SourceController@destroy', $source->id);
        $title = 'source ' . $source->name;
        return view('layouts.delete', compact('action' , 'title'));
    }
}
