<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompanyResource;
use App\Models\Assessment;
use App\Models\Company;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    private $company;

    /**
     * Class constructor
     *
     * @param Company $company dependence injection
     */
    public function __construct(Company $company)
    {
        $this->company = $company;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request the request fom page
     *
     * @return Collection
     */
    public function index(Request $request)
    {

        return CompanyResource::collection(
            $this->company->getAll($request->filter)
        );
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
             /** @var Assessment $assessmentAlredyExist */
            $assessmentAlredyExist = Assessment::where('company_id', $companyAlredyExist->id)
            ->where('user_id', auth()->user()->id)
            ->first();

            if($assessmentAlredyExist){
                $assessmentAlredyExist->update(['note'=>$request->note]);

                return response()->json($assessmentAlredyExist)->setStatusCode(200);
            }

            Assessment::create([
                'user_id' => auth()->user()->id,
                'company_id' => $companyAlredyExist->id,
                'note' => $request->note,
            ]);

            $resource = new CompanyResource($companyAlredyExist);

            return  $resource->response()->setStatusCode(200);
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

        Assessment::create([
            'user_id'=>auth()->user()->id,
            'company_id'=>$company->id,
            'note'=>$request->note,
        ]);

        $resource = new CompanyResource($company);

        return $resource->response()->setStatusCode(201);
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
