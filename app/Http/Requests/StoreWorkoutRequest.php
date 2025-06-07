<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkoutRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'trainer' => 'required|string|max:255',
            'date' => 'required|date|after:now',
            'slots' => 'required|integer|min:1|max:100',
            'is_active' => 'boolean',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'The title field is required.',
            'description.required' => 'The description field is required.',
            'trainer.required' => 'The trainer field is required.',
            'date.required' => 'The date field is required.',
            'date.after' => 'The workout date must be in the future.',
            'slots.required' => 'The slots field is required.',
            'slots.min' => 'There must be at least 1 slot available.',
        ];
    }
}
