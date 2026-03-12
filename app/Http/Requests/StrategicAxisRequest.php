<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StrategicAxisRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'action_plan_id' => 'required|exists:action_plans,id',
            'title' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'order' => 'integer',
            'description' => 'nullable|string',
        ];
    }
}
