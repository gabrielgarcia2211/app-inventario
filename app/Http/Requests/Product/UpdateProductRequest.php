<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'is_total' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.string' => 'El campo nombre debe ser una cadena de texto.',
            'name.max' => 'El campo nombre no puede tener más de 255 caracteres.',
            'description.nullable' => 'El campo descripción es opcional.',
            'description.string' => 'El campo descripción debe ser una cadena de texto.',
            'price.required' => 'El campo precio es obligatorio.',
            'price.numeric' => 'El campo precio debe ser un número.',
            'price.min' => 'El campo precio debe ser al menos 0.',
            'category.required' => 'El campo categoría es obligatorio.',
            'category.string' => 'El campo categoría debe ser una cadena de texto.',
            'category.max' => 'El campo categoría no puede tener más de 255 caracteres.',
            'is_total.required' => 'El campo is_total es obligatorio.',
            'photo.required' => 'El campo foto es obligatorio.',
        ];
    }
}
