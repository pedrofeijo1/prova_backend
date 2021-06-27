<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Covenant;
use App\Http\Services\Auth;
use App\Http\Resources\CovenantCollection;

class CovenantTest extends TestCase
{
    public function test_covenants()
    {
        $response = $this->get('/api/covenants', [
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
                "key": "INSS",
                "value": "INSS"
            },
            {
                "key": "FEDERAL",
                "value": "Federal"
            },
            {
                "key": "SIAPE",
                "value": "Siape"
            }
        ]', true);
    }
}
