<?php

namespace App\Http\Controllers;

use App\Http\Services\InstitutionService;
use App\Http\Resources\InstitutionCollection;

class InstitutionController extends Controller
{
    private $institutionService;

    public function __construct(InstitutionService $institutionService)
    {
        $this->institutionService = $institutionService;
    }

    public function index()
    {
        return InstitutionCollection::collection($this->institutionService->index());
    }
}
