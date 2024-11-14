<?php

namespace Kalimero\Crm\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class LEOSSCurrentViewRequest extends FormRequest
{
    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'number' => 'required|integer|digits:7',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
