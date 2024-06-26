<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'client_id'=>'required|exists:clients,id',
            'name' => 'required|unique:projects,name|max:255',
            'hourly_rate' => 'required|numeric',
            'hourly_rate_two' => 'nullable|numeric',
            'notes' => 'required|max:255',

        ];
    }
}
