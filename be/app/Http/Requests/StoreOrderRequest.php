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
            'order_items' => 'required|array|min:1',
            'order_items.*.menu_id' => 'required|exists:menus,menu_id',
            'order_items.*.qty' => 'required|integer|min:1',
            'order_items.*.order_item_attributes' => 'nullable|array',
            'order_items.*.order_item_attributes.*.attribute_id' => 'required|exists:attributes,attribute_id',
            'order_items.*.order_item_attributes.*.value' => 'required|boolean',
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

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $errors = collect($validator->errors())->map(function ($messages, $field) {
            return [
                'field' => $field,
                'message' => $messages[0]
            ];
        })->values();

        throw new \Illuminate\Http\Exceptions\HttpResponseException(
            response()->json([
                'message' => 'Order gagal dibuat.',
                'errors' => $errors
            ], 400)
        );
    }
}
