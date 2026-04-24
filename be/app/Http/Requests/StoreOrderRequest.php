<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'source' => 'required|in:cashier,whatsapp',
            'customer_name' => 'nullable|string',
            'customer_phone' => 'nullable|string',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->source === 'whatsapp' && empty($this->customer_name) && empty($this->customer_phone)) {
                $validator->errors()->add('customer_name', 'Minimal salah satu dari customer_name atau customer_phone wajib diisi untuk source whatsapp.');
            }
        });
    }
}
