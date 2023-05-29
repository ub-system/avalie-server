<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssessmentRequest;
use App\Http\Resources\AssessmentResource;
use App\Models\Assessment;

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
        $request['user_id'] = auth()->user()->id;

        $assessment = $this->assessment->updateOrCreate($request->all());

        $resource = new AssessmentResource($assessment);

        return $resource->response()->setStatusCode(201);
    }
}
