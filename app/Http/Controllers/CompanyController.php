<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::all();

        return response()->json($companies->load('assessments'))->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /** @var Company $companyAlredyExist */
        $companyAlredyExist = Company::where('name', $request->name)
            ->where('branch', $request->branch)
            ->where('city', $request->city)
            ->first();
        if($companyAlredyExist){
            Assessment::create([
                'user_id' => auth()->user()->id,
                'company_id' => $companyAlredyExist->company_id,
                'note' => $request->note,
            ]);

            return response()->json($companyAlredyExist)->setStatusCode(200);
        }

        $request->validate([
            'name'=>'required|string',
            'branch'=>'required|string',
            'city'=>'required|string',
        ]);

        $company = Company::create([
            'name'=>$request->name,
            'branch'=>$request->branch,
            'city'=>$request->city,
        ]);

        $assessment = Assessment::create([
            'user_id'=>auth()->user()->id,
            'company_id'=>$company->id,
            'note'=>$request->note,
        ]);

        return response()->json($company->load('assessments'))->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        //
    }
}
