<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'action_id' => 'required|exists:actions,id',
            'title' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:activities,code,' . ($this->activity ? $this->activity->id : ''),
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'budget' => 'nullable|numeric|min:0',
            'status' => 'required|string',
            'progress_rate' => 'numeric|min:0|max:100',
            'direction_id' => 'nullable|exists:directions,id',
        ];
    }
}
