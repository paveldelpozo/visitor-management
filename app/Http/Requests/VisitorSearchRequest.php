<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VisitorSearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'nullable|string',
            'surname' => 'nullable|string',
            'company' => 'nullable|string',
            'identity_id' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|string',
        ];
    }

    public function filters(): array
    {
        return array_filter($this->only([
            'name', 'surname', 'company', 'identity_id', 'phone', 'email'
        ]));
    }
}
