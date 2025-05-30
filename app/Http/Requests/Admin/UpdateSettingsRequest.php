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
            'settings.address' => 'nullable|string|max:500',
            'settings.instagram_url' => 'nullable|url:http,https|max:255',
            'settings.facebook_url' => 'nullable|url:http,https|max:255',
            'settings.Maps_url' => 'nullable|url:http,https|max:2048',
            'settings.operating_hours' => 'nullable|string|max:500',
            'site_logo_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'remove_current_logo' => 'nullable|boolean',
            'hero_image_homepage_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'remove_current_hero_image' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'settings.site_name.required_with' => 'Nama website wajib diisi.',
            'settings.contact_email.required_with' => 'Email kontak wajib diisi.',
            'settings.contact_whatsapp.required_with' => 'Nomor WhatsApp wajib diisi.',
            'settings.contact_whatsapp.regex' => 'Nomor WhatsApp hanya boleh berisi angka.',
            'settings.Maps_url.url' => 'Format URL untuk Peta Google tidak valid. Harap masukkan hanya URL dari atribut src iframe.',
            'site_logo_file.image' => 'File logo harus berupa gambar.',
            'site_logo_file.mimes' => 'Format logo yang diperbolehkan: jpeg, png, jpg, gif, svg, webp.',
            'site_logo_file.max' => 'Ukuran logo maksimal 2MB.',
            'hero_image_homepage_file.image' => 'File gambar hero harus berupa gambar.',
            'hero_image_homepage_file.mimes' => 'Format gambar hero yang diperbolehkan: jpeg, png, jpg, webp.',
            'hero_image_homepage_file.max' => 'Ukuran gambar hero maksimal 3MB.',
        ];
    }
}
