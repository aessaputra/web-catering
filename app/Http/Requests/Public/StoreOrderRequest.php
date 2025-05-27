<?php

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Izinkan semua orang membuat pesanan
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'delivery_address' => 'required|string|max:1000',
            'event_date' => 'required|date|after_or_equal:today',
            'notes' => 'nullable|string|max:2000',
        ];

        // Jika pengguna belum login, field nama, email, dan telepon wajib diisi
        if (!Auth::check()) {
            $rules['customer_name'] = 'required|string|max:255';
            $rules['customer_email'] = 'required|email|max:255';
            $rules['customer_phone'] = 'required|string|max:20';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Anda harus memilih setidaknya satu item menu.',
            'items.min' => 'Anda harus memilih setidaknya satu item menu.',
            'items.*.id.required' => 'Item menu tidak valid.',
            'items.*.id.exists' => 'Item menu yang dipilih tidak ditemukan.',
            'items.*.quantity.required' => 'Jumlah untuk setiap item menu wajib diisi.',
            'items.*.quantity.min' => 'Jumlah untuk setiap item menu minimal 1.',
            'customer_name.required' => 'Nama lengkap wajib diisi.',
            'customer_email.required' => 'Alamat email wajib diisi.',
            'customer_email.email' => 'Format alamat email tidak valid.',
            'customer_phone.required' => 'Nomor telepon wajib diisi.',
            'delivery_address.required' => 'Alamat pengiriman wajib diisi.',
            'event_date.required' => 'Tanggal acara wajib diisi.',
            'event_date.date' => 'Format tanggal acara tidak valid.',
            'event_date.after_or_equal' => 'Tanggal acara tidak boleh kurang dari hari ini.',
        ];
    }
}
