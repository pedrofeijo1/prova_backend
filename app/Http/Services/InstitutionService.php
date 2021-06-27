<?php

namespace App\Http\Services;

use App\Models\Institution;

class InstitutionService extends Service
{
    public function index()
    {
        return Institution::all();
    }
}
