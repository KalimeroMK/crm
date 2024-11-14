<?php

namespace Kalimero\Crm\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class AAListingRequest extends FormRequest
{
    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'number' => 'required|integer|digits:7',
            'date' => 'nullable|integer|digits:4',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
