<?php

namespace Tests\Feature;

use App\Http\Services\Auth;
use App\Models\Covenant;
use Tests\TestCase;

class CreditSimulationTest extends TestCase
{
    public function test_method_not_allowed()
    {
        $response = $this->get('/api/credit-simulation', [
            'accept' => 'application/json',
            'authorization' => 'Bearer ' . Auth::token(),
        ]);

        $response->assertStatus(400);
        $this->assertEquals(
            'The GET method is not supported for this route. Supported methods: POST.',
            $response->json('message')
        );
    }

    public function test_validation_required_error()
    {
        $response = $this->post('/api/credit-simulation', [], [
            'accept' => 'application/json',
            'authorization' => 'Bearer ' . Auth::token(),
        ]);

        $response->assertStatus(422);
        $this->validationError($response, 'loan_amount');
    }

    public function test_loan_amount_not_numeric()
    {
        $response = $this->post('/api/credit-simulation', [
            'loan_amount' => 'R$ 11.750,00'
        ], [
            'accept' => 'application/json',
            'authorization' => 'Bearer ' . Auth::token(),
        ]);

        $response->assertStatus(422);
        $this->validationError($response, 'loan_amount');
    }

    public function test_credit_simulation()
    {
        $response = $this->post('/api/credit-simulation', [
            'loan_amount' => 11750
        ], [
            'accept' => 'application/json',
            'authorization' => 'Bearer ' . Auth::token(),
        ]);

        $response->assertSuccessful();
        $response->assertExactJson($this->responseSuccessWithoutFilter());
    }

    public function test_institution_filter()
    {
        $response = $this->post('/api/credit-simulation', [
            'loan_amount' => 11750,
            'institutions' => [
                'BMG'
            ],
        ], [
            'accept' => 'application/json',
            'authorization' => 'Bearer ' . Auth::token(),
        ]);

        $response->assertSuccessful();
        $response->assertExactJson($this->responseSuccessInstitutionBMG());
    }

    public function test_institution_filter_invalid_option()
    {
        $response = $this->post('/api/credit-simulation', [
            'loan_amount' => 11750,
            'institutions' => [
                'BMG2'
            ],
        ], [
            'accept' => 'application/json',
            'authorization' => 'Bearer ' . Auth::token(),
        ]);

        $response->assertStatus(422);
        $this->validationError($response, 'institutions');
    }

    public function test_covenant_filter()
    {
        $response = $this->post('/api/credit-simulation', [
            'loan_amount' => 11750,
            'covenants' => [
                'INSS'
            ],
        ], [
            'accept' => 'application/json',
            'authorization' => 'Bearer ' . Auth::token(),
        ]);

        $response->assertSuccessful();
        $response->assertExactJson($this->responseSuccessCovenantINSS());
    }

    public function test_covenant_filter_invalid_option()
    {
        $response = $this->post('/api/credit-simulation', [
            'loan_amount' => 11750,
            'covenants' => [
                'INSS32'
            ],
        ], [
            'accept' => 'application/json',
            'authorization' => 'Bearer ' . Auth::token(),
        ]);

        $response->assertStatus(422);
        $this->validationError($response, 'covenants');
    }

    public function test_installment_filter()
    {
        $response = $this->post('/api/credit-simulation', [
            'loan_amount' => 11750,
            'installments' => 36,
        ], [
            'accept' => 'application/json',
            'authorization' => 'Bearer ' . Auth::token(),
        ]);

        $response->assertSuccessful();
        $response->assertExactJson($this->responseSuccessInstallment36());
    }

    public function test_installment_filter_invalid_option()
    {
        $response = $this->post('/api/credit-simulation', [
            'loan_amount' => 11750,
            'installments' => 360,
        ], [
            'accept' => 'application/json',
            'authorization' => 'Bearer ' . Auth::token(),
        ]);

        $response->assertStatus(422);
        $this->validationError($response, 'installments');
    }

    public function test_all_filter()
    {
        $response = $this->post('/api/credit-simulation', [
            'loan_amount' => 11750,
            'institutions' => [
                'BMG'
            ],
            'covenants' => [
                'INSS'
            ],
            'installments' => 36,
        ], [
            'accept' => 'application/json',
            'authorization' => 'Bearer ' . Auth::token(),
        ]);

        $response->assertSuccessful();
        $response->assertExactJson(
            $this->responseSuccessInstitutionBMGCovenantINSSInstallment36()
        );
    }

    public function validationError($response, $key)
    {
        $this->assertArrayHasKey('errors', $response->json());
        $this->assertArrayHasKey($key, $response->json('errors'));
    }

    public function responseSuccessWithoutFilter()
    {
        return json_decode('{
            "PAN": [
                {
                    "fee": 2.05,
                    "installments": 48,
                    "installment_value": 402.91,
                    "covenant": "INSS"
                },
                {
                    "fee": 2.08,
                    "installments": 72,
                    "installment_value": 334.05,
                    "covenant": "INSS"
                },
                {
                    "fee": 2.1,
                    "installments": 36,
                    "installment_value": 367.19,
                    "covenant": "FEDERAL"
                }
            ],
            "OLE": [
                {
                    "fee": 2.05,
                    "installments": 60,
                    "installment_value": 356.61,
                    "covenant": "INSS"
                },
                {
                    "fee": 2.08,
                    "installments": 72,
                    "installment_value": 334.05,
                    "covenant": "INSS"
                }
            ],
            "BMG": [
                {
                    "fee": 2.05,
                    "installments": 72,
                    "installment_value": 305.97,
                    "covenant": "INSS"
                },
                {
                    "fee": 2.05,
                    "installments": 60,
                    "installment_value": 354.26,
                    "covenant": "INSS"
                },
                {
                    "fee": 2.05,
                    "installments": 48,
                    "installment_value": 414.66,
                    "covenant": "INSS"
                },
                {
                    "fee": 2.05,
                    "installments": 36,
                    "installment_value": 554.48,
                    "covenant": "INSS"
                },
                {
                    "fee": 1.9,
                    "installments": 84,
                    "installment_value": 286.51,
                    "covenant": "INSS"
                }
            ]
        }', true);
    }

    public function responseSuccessInstitutionBMG()
    {
        return json_decode('{
            "BMG": [
                {
                    "fee": 2.05,
                    "installments": 72,
                    "installment_value": 305.97,
                    "covenant": "INSS"
                },
                {
                    "fee": 2.05,
                    "installments": 60,
                    "installment_value": 354.26,
                    "covenant": "INSS"
                },
                {
                    "fee": 2.05,
                    "installments": 48,
                    "installment_value": 414.66,
                    "covenant": "INSS"
                },
                {
                    "fee": 2.05,
                    "installments": 36,
                    "installment_value": 554.48,
                    "covenant": "INSS"
                },
                {
                    "fee": 1.9,
                    "installments": 84,
                    "installment_value": 286.51,
                    "covenant": "INSS"
                }
            ]
        }', true);
    }

    public function responseSuccessCovenantINSS()
    {
        return json_decode('{
            "PAN": [
                {
                    "fee": 2.05,
                    "installments": 48,
                    "installment_value": 402.91,
                    "covenant": "INSS"
                },
                {
                    "fee": 2.08,
                    "installments": 72,
                    "installment_value": 334.05,
                    "covenant": "INSS"
                }
            ],
            "OLE": [
                {
                    "fee": 2.05,
                    "installments": 60,
                    "installment_value": 356.61,
                    "covenant": "INSS"
                },
                {
                    "fee": 2.08,
                    "installments": 72,
                    "installment_value": 334.05,
                    "covenant": "INSS"
                }
            ],
            "BMG": [
                {
                    "fee": 2.05,
                    "installments": 72,
                    "installment_value": 305.97,
                    "covenant": "INSS"
                },
                {
                    "fee": 2.05,
                    "installments": 60,
                    "installment_value": 354.26,
                    "covenant": "INSS"
                },
                {
                    "fee": 2.05,
                    "installments": 48,
                    "installment_value": 414.66,
                    "covenant": "INSS"
                },
                {
                    "fee": 2.05,
                    "installments": 36,
                    "installment_value": 554.48,
                    "covenant": "INSS"
                },
                {
                    "fee": 1.9,
                    "installments": 84,
                    "installment_value": 286.51,
                    "covenant": "INSS"
                }
            ]
        }', true);
    }

    public function responseSuccessInstallment36()
    {
        return json_decode('{
            "PAN": [
                {
                    "fee": 2.1,
                    "installments": 36,
                    "installment_value": 367.19,
                    "covenant": "FEDERAL"
                }
            ],
            "OLE": [],
            "BMG": [
                {
                    "fee": 2.05,
                    "installments": 36,
                    "installment_value": 554.48,
                    "covenant": "INSS"
                }
            ]
        }', true);
    }

    public function responseSuccessInstitutionBMGCovenantINSSInstallment36()
    {
        return json_decode('{
            "BMG": [
                {
                    "fee": 2.05,
                    "installments": 36,
                    "installment_value": 554.48,
                    "covenant": "INSS"
                }
            ]
        }', true);
    }
}
