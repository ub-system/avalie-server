<?php

namespace App\Http\Controllers;

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

        return response()->json($assessment->load(['company', 'user']));
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
    public function update($id, Request $request)
    {
        $request->validate([
            'note'=>'required|numeric',
        ]);

        $assessment = Assessment::find($id)->update([
            'note'=>$request->note,
        ]);

        return response()->json($assessment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(assessment $assessment)
    {
        //
    }
}
