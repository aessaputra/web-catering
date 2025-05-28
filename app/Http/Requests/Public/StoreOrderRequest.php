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
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'items' => 'required|array', // Array 'items' harus ada dan tidak kosong
            // 'items.*.id' hanya divalidasi jika ada dan kuantitasnya > 0 (di handle di withValidator)
            // 'items.*.id' => ['sometimes', 'required', 'integer', 'exists:menu_items,id'],
            // Kita tetap validasi semua ID yang dikirim ada di database
            'items.*.id' => 'required|integer|exists:menu_items,id',

            // Kuantitas wajib ada untuk setiap entri item, dan minimal 0
            // Pengecekan "minimal satu item dengan quantity > 0" dilakukan di withValidator
            'items.*.quantity' => 'required|integer|min:0',
            'delivery_address' => 'required|string|max:1000',
            'event_date' => 'required|date|after_or_equal:today',
            'notes' => 'nullable|string|max:2000',
        ];

        if (!Auth::check()) {
            $rules['customer_name'] = 'required|string|max:255';
            $rules['customer_email'] = 'required|email|max:255';
            $rules['customer_phone'] = 'required|string|max:20';
        }

        return $rules;
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Cek hanya jika tidak ada error validasi lain pada field 'items' dari method rules()
            if ($validator->errors()->has('items') || $validator->errors()->has('items.*')) {
                return;
            }

            $items = $this->input('items', []);
            $validItemsCount = 0;
            $hasItemWithPositiveQuantityButMissingOrInvalidId = false;

            if (empty($items) && !$this->isPrecognitive()) {
                $validator->errors()->add('items', 'Anda harus memilih setidaknya satu item menu.');
                return;
            }

            foreach ($items as $index => $itemData) {
                // Pastikan quantity adalah integer dan tidak negatif (sudah ditangani min:0)
                $quantity = isset($itemData['quantity']) ? filter_var($itemData['quantity'], FILTER_VALIDATE_INT) : null;
                $id = $itemData['id'] ?? null;

                // Jika quantity tidak valid (bukan integer atau negatif), rule awal akan menangkapnya.
                // Di sini kita fokus pada logika bahwa setidaknya satu item harus memiliki quantity > 0.
                if ($quantity !== null && $quantity > 0) {
                    $validItemsCount++;
                    // Jika kuantitas > 0, ID item harus ada dan valid.
                    // Rule 'items.*.id' => 'required|integer|exists:menu_items,id' sudah menangani ini.
                    // Namun, jika 'items.*.id' diubah jadi 'sometimes', kita perlu cek di sini:
                    if (empty($id)) {
                        // $hasItemWithPositiveQuantityButMissingOrInvalidId = true; // Flag jika perlu
                        // $validator->errors()->add("items.{$index}.id", 'Item yang dipilih dengan jumlah lebih dari 0 tidak memiliki ID yang valid.');
                    }
                }
            }

            if ($validItemsCount === 0 && !$this->isPrecognitive()) {
                $validator->errors()->add('items_overall', 'Anda harus memesan setidaknya satu item menu dengan jumlah lebih dari 0.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Daftar item pesanan tidak boleh kosong.',
            'items.*.id.required' => 'ID untuk setiap item menu wajib ada.',
            'items.*.id.exists' => 'Item menu yang dipilih tidak valid atau tidak ditemukan.',
            'items.*.quantity.required' => 'Jumlah untuk setiap item menu wajib diisi.',
            'items.*.quantity.integer' => 'Jumlah harus berupa angka.',
            'items.*.quantity.min' => 'Jumlah minimal adalah 0 (nol). Item yang tidak dipesan bisa diisi 0.',
            'items_overall.required' => 'Anda harus memesan setidaknya satu item menu dengan jumlah lebih dari 0.', // Pesan dari withValidator
            // ... (pesan validasi lainnya) ...
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
