<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssessmentRequest;
use App\Http\Requests\CompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Assessment;
use App\Models\Company;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    private $company;
    private $assessment;

    /**
     * Class constructor
     *
     * @param Company $company dependence injection
     */
    public function __construct(Company $company, Assessment $assessment)
    {
        $this->company = $company;
        $this->assessment = $assessment;
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
    public function store(CompanyRequest $request)
    {
        /** @var Company $companyAlredyExist */
        $companyAlredyExist = $this->company
            ->where('name', $request->name)
            ->where('branch', $request->branch)
            ->where('city', $request->city)
            ->first();

        if($companyAlredyExist){
             /** @var Assessment $assessmentAlredyExist */
            // $assessmentAlredyExist = $this->assessment
            //     ->where('company_id', $companyAlredyExist->id)
            //     ->where('user_id', auth()->user()->id)
            //     ->first();

            // if($assessmentAlredyExist){
            //     $assessmentAlredyExist->update(['note'=>$request->note]);

            //     return response()->json($assessmentAlredyExist)->setStatusCode(200);
            // }

            $assessmentRequest = new AssessmentRequest([
                'user_id' => auth()->user()->id,
                'company_id' => $companyAlredyExist->id,
                'note' => $request->note,
            ]);

            $this->assessment->updateOrCreate($assessmentRequest->all());

            $resource = new CompanyResource($companyAlredyExist);

            return  $resource->response()->setStatusCode(200);
        }

        $company = $this->company->create($request->all());

        $assessmentRequest = new AssessmentRequest([
            'user_id'=>auth()->user()->id,
            'company_id'=>$company->id,
            'note'=>$request->note,
        ]);

        $this->assessment->create($assessmentRequest->all());

        $resource = new CompanyResource($company);

        return $resource->response()->setStatusCode(201);
    }
}
