<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateMenuCategoryRequest extends FormRequest
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
        $categoryId = $this->route('category')->id; // 'category' adalah nama parameter di route resource

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('menu_categories')->ignore($categoryId),
            ],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('menu_categories')->ignore($categoryId),
            ],
            'description' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->filled('name') && !$this->filled('slug')) {
            $this->merge([
                'slug' => Str::slug($this->name),
            ]);
        } elseif ($this->filled('name') && $this->filled('slug')) {
             // Jika slug diisi manual dan nama berubah, pastikan slug tetap atau di-update juga
             // Jika ingin slug selalu mengikuti nama saat update jika slug tidak diisi manual:
            if ($this->route('category')->name !== $this->name && empty($this->slug_manually_edited)) {
                 $this->merge(['slug' => Str::slug($this->name)]);
            }
        }
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Nama kategori sudah ada.',
            'slug.unique' => 'Slug sudah ada, biarkan kosong untuk generate otomatis atau gunakan slug lain.',
        ];
    }
}
