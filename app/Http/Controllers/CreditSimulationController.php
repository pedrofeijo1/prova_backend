<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreditSimulationRequest;
use App\Http\Resources\CreditSimulationCollection;
use App\Http\Services\CreditSimulationService;
use Illuminate\Http\Request;

class CreditSimulationController extends Controller
{
    private $creditSimulationService;

    public function __construct(CreditSimulationService $creditSimulationService)
    {
        $this->creditSimulationService = $creditSimulationService;
    }

    public function index(CreditSimulationRequest $request)
    {
        return CreditSimulationCollection::collection($this->creditSimulationService->index($request->validated()));
    }
}
