<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;


class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0.01',
            'category_id' => 'required|exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Название товара обязательно',
            'price.required' => 'Цена обязательна',
            'price.min' => 'Цена должна быть больше 0',
            'category_id.exists' => 'Выбранная категория не существует',
        ];
    }
}
