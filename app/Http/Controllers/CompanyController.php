<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;
use App\Http\Resources\CompanyResource;
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
    public function store(CompanyRequest $request)
    {
        /** @var Company $companyAlredyExist */
        $companyAlredyExist = $this->company
            ->where('name', $request->name)
            ->where('branch', $request->branch)
            ->where('city', $request->city)
            ->first();

        if($companyAlredyExist != null) {
            $resource = new CompanyResource($companyAlredyExist);

            return  $resource->response()->setStatusCode(200);
        }

        $company = $this->company->create($request->all());

        $resource = new CompanyResource($company);

        return $resource->response()->setStatusCode(201);
    }
}
