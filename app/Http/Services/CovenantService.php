<?php

namespace App\Http\Services;

use App\Models\Covenant;

class CovenantService extends Service
{
    public function index()
    {
        return Covenant::all();
    }
}
