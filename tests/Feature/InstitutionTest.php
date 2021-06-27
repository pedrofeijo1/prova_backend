<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Http\Services\Auth;
use App\Models\Institution;
use App\Http\Resources\InstitutionCollection;

class InstitutionTest extends TestCase
{
    public function test_institutions()
    {
        $response = $this->get('/api/institutions', [
            'accept' => 'application/json',
            'authorization' => 'Bearer ' . Auth::token(),
        ]);

        $response->assertSuccessful();
        $response->assertExactJson($this->responseSuccess());
    }

    public function responseSuccess()
    {
        return json_decode('[
            {
                "key": "PAN",
                "value": "Pan"
            },
            {
                "key": "OLE",
                "value": "Ole"
            },
            {
                "key": "BMG",
                "value": "Bmg"
            }
        ]', true);
    }
}
