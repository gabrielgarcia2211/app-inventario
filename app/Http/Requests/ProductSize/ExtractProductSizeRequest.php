<?php

namespace App\Http\Requests\ProductSize;

use Illuminate\Foundation\Http\FormRequest;

class ExtractProductSizeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'client_id' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'client_id.required' => 'El campo cliente es obligatorio.',
        ];
    }
}
