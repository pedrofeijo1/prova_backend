<?php

namespace App\Http\Requests;

use App\Models\Covenant;
use App\Models\Institution;
use App\Models\InstitutionFee;
use Illuminate\Foundation\Http\FormRequest;

class CreditSimulationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'loan_amount' => 'required|numeric',
            'covenants' => 'nullable|array|in:' . $this->inCovenants(),
            'institutions' => 'nullable|array|in:' . $this->inInstitutions(),
            'installments' => 'nullable|int|in: ' . $this->inInstitutionFees(),
        ];
    }

    public function messages()
    {
        return [
            'covenants.in' => 'The selected covenant is invalid. Accepted keys: ' . $this->inCovenants(),
            'institutions.in' => 'The selected institution is invalid. Accepted keys: ' . $this->inInstitutions(),
            'installments.in' => 'The selected installment is invalid. Accepted keys: ' . $this->inInstitutionFees(),
        ];
    }

    private function inInstitutionFees()
    {
        return $this->toString(InstitutionFee::class, __('key.installments'));
    }

    private function inInstitutions()
    {
        return $this->toString(Institution::class,  __('key.key'));
    }

    private function inCovenants()
    {
        return $this->toString(Covenant::class,  __('key.key'));
    }

    private function toString(string $class, string $key)
    {
        return $class::all($key)
            ->pluck($key)
            ->unique()
            ->sort()
            ->implode(',');
    }
}
