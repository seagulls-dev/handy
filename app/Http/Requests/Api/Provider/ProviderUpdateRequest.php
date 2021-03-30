<?php

namespace App\Http\Requests\Api\Provider;

use Illuminate\Foundation\Http\FormRequest;

class ProviderUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'primary_category_id' => 'required|exists:categories,id',
            'document_url' => 'required|string',
            'about' => 'required|string',
            'price' => 'required|numeric',
            'price_type' => 'required|in:visit,hour',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'sub_categories' => 'required|array|exists:categories,id',
        ];
    }
}
