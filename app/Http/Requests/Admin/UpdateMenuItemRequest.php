<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMenuItemRequest extends FormRequest
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
            'menu_category_id' => 'required|integer|exists:menu_categories,id',
            'description' => 'nullable|string|max:2000',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048', // Gambar opsional saat update
            'is_featured' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        // Pesan bisa sama dengan StoreRequest atau dikustomisasi
        return [
            'name.required' => 'Nama item menu wajib diisi.',
            'menu_category_id.required' => 'Kategori menu wajib dipilih.',
            'menu_category_id.exists' => 'Kategori menu tidak valid.',
            'price.required' => 'Harga wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar yang diperbolehkan: jpeg, png, jpg, gif, svg, webp.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
