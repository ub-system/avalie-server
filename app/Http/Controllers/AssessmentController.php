<?php

namespace App\Http\Controllers;

use App\Http\Resources\AssessmentResource;
use App\Models\assessment;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'note'=>'required|numeric'
        ]);

        $assessment = assessment::create([
            'user_id'=>auth()->user()->id,
            'company_id'=>$request->company_id,
            'note'=>$request->note,
        ]);

        $resource = new AssessmentResource($assessment);

        return $resource->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(assessment $assessment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'note'=>'required|numeric',
        ]);

        $assessment = Assessment::find($request->id)->update([
            'note'=>$request->note,
        ]);

        return response()->json($assessment)->setStatusCode(201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(assessment $assessment)
    {
        //
    }
}
