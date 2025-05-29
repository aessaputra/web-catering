<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'settings' => 'required|array',
            'settings.site_name' => 'required|string|max:255',
            'settings.site_description' => 'nullable|string|max:1000',
            'settings.contact_email' => 'required|email|max:255',
            // 'settings.contact_phone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9\s\-\+\(\)]*$/'], // Jika masih dipakai
            'settings.contact_whatsapp' => ['required', 'string', 'max:20', 'regex:/^[0-9]+$/'], // Hanya angka, dan required
            'settings.address' => 'nullable|string|max:500',
            'settings.instagram_url' => 'nullable|url:http,https|max:255',
            'settings.facebook_url' => 'nullable|url:http,https|max:255',
            'settings.Maps_url' => 'nullable|url:http,https|max:2048', // Validasi sebagai URL
            'settings.homepage_promotion_message' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'settings.site_name.required' => 'Nama website wajib diisi.',
            'settings.contact_email.required' => 'Email kontak wajib diisi.',
            'settings.contact_email.email' => 'Format email kontak tidak valid.',
            'settings.contact_whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
            'settings.contact_whatsapp.regex' => 'Nomor WhatsApp hanya boleh berisi angka.',
            'settings.Maps_url.url' => 'Format URL untuk Peta Google tidak valid. Harap masukkan hanya URL dari atribut src.',
            'settings.*.url' => 'Format URL tidak valid (harus diawali http:// atau https://).',
        ];
    }
}
