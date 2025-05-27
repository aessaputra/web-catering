<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Hanya admin yang bisa akses route ini
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'settings' => 'required|array',
            'settings.site_name' => 'required|string|max:255',
            'settings.site_description' => 'nullable|string|max:1000',
            'settings.contact_email' => 'required|email|max:255',
            'settings.contact_phone' => 'required|string|max:50',
            'settings.address' => 'nullable|string|max:500',
            'settings.instagram_url' => 'nullable|url|max:255',
            'settings.facebook_url' => 'nullable|url|max:255',
            'settings.Maps_url' => 'nullable|url|max:1000',
            'settings.homepage_promotion_message' => 'nullable|string|max:500',
            // Tambahkan validasi untuk key setting lain jika ada
        ];
    }

    public function messages(): array
    {
        return [
            'settings.site_name.required' => 'Nama website wajib diisi.',
            'settings.contact_email.required' => 'Email kontak wajib diisi.',
            'settings.contact_email.email' => 'Format email kontak tidak valid.',
            'settings.contact_phone.required' => 'Nomor telepon kontak wajib diisi.',
            'settings.*.url' => 'Format URL tidak valid.',
        ];
    }
}
