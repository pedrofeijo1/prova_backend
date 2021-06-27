<?php

namespace App\Http\Controllers;

use App\Http\Services\CovenantService;
use App\Http\Resources\CovenantCollection;

class CovenantController extends Controller
{
    private $covenantService;

    public function __construct(CovenantService $covenantService)
    {
        $this->covenantService = $covenantService;
    }

    public function index()
    {
        return CovenantCollection::collection($this->covenantService->index());
    }
}
