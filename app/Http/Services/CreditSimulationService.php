<?php

namespace App\Http\Services;

use App\Models\Institution;
use App\Models\InstitutionFee;

class CreditSimulationService extends Service
{
    private $fees;
    private $institutions;
    private $loanAmount;
    private $response = [];

    public function setVars($params)
    {
        $this->loanAmount = $params['loan_amount'];

        $this->fees = InstitutionFee::all();
        $this->institutions = Institution::all()->sort();

        $this->filter($params);
    }

    public function filter($params)
    {
        if (isset($params['institutions'])) {
            $this->fees = $this->fees->whereIn(__('key.institution'), $params['institutions']);
            $this->institutions = $this->institutions->whereIn(__('key.key'), $params['institutions']);
        }

        if (isset($params['covenants'])) {
            $this->fees = $this->fees->whereIn(__('key.covenant'), $params['covenants']);
        }

        if (isset($params['installments'])) {
            $this->fees = $this->fees->where(__('key.installments'), $params['installments']);
        }
    }

    public function index($params)
    {
        $this->setVars($params);

        $this->institutions->map(function($data) {
            $this->appendResponse($data[__('key.key')]);
        });

        return $this->response;
    }

    public function appendResponse($key)
    {
        $fee = $this->getFeeByKey($key);

        $this->response[$key] = [];
        $fee->map(function($fee) use ($key) {
            $this->response[$key][] = [
                'fee' => $fee[__('key.interest_rate')],
                'installments' => $fee[__('key.installments')],
                'installment_value' => $this->installmentValue($fee[__('key.coefficient')]),
                'covenant' => $fee[__('key.covenant')]
            ];
        });
    }

    public function installmentValue($coefficient)
    {
        return round($this->loanAmount * $coefficient, 2);
    }

    public function getFeeByKey($key)
    {
        return $this->fees->where(__('key.institution'), $key);
    }
}
