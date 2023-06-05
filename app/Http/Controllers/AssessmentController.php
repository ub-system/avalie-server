<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssessmentRequest;
use App\Http\Resources\AssessmentResource;
use App\Models\assessment;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    private $assessment;

    public function __construct(Assessment $assessment)
    {
        $this->assessment = $assessment;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AssessmentRequest $request)
    {
        $assessmentAlreadyExist = $this->assessment
            ->where('user_id', $request->user_id)
            ->where('company_id', $request->company_id)
            ->first();

        if($assessmentAlreadyExist != null){
            $assessmentAlreadyExist->update($request->all());

            $resource = new AssessmentResource($assessmentAlreadyExist);

            return $resource->response()->setStatusCode(201);
        }

        $assessment = $this->assessment->create([
            'user_id'=>$request->user_id,
            'company_id'=>$request->company_id,
            'note'=>$request->note,
        ]);

        $resource = new AssessmentResource($assessment);

        return $resource->response()->setStatusCode(201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AssessmentRequest $request)
    {
        $assessment = $this->assessment
            ->where('user_id', $request->user_id)
            ->where('company_id', $request->company_id)
            ->first();

        if($assessment != null){
            $assessment->update($request->all());

            $resource = new AssessmentResource($assessment);

            return $resource->response()->setStatusCode(200);
        }

        return response(['error'=>'Não foi possível atualizar a avaliação'], 400);
    }
}
