<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkoutRequest extends FormRequest
{
    public function authorize()
    {
        return $this->workout->user_id === auth()->id();
    }

    public function rules()
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'trainer' => 'sometimes|required|string|max:255',
            'date' => 'sometimes|required|date|after:now',
            'slots' => 'sometimes|required|integer|min:1|max:100',
            'is_active' => 'sometimes|boolean',
        ];
    }
}
