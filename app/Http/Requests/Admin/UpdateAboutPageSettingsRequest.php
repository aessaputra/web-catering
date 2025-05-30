<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAboutPageSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'settings_about' => 'required|array',
            'settings_about.about_hero_title' => 'nullable|string|max:255',
            'settings_about.about_hero_subtitle_template' => 'nullable|string|max:255',
            'settings_about.about_history_title' => 'nullable|string|max:255',
            'settings_about.about_history_content' => 'nullable|string|max:5000',
            'settings_about.about_vision_title' => 'nullable|string|max:255',
            'settings_about.about_vision_content' => 'nullable|string|max:2000',
            'settings_about.about_mission_title' => 'nullable|string|max:255',
            'settings_about.about_mission_point_1' => 'nullable|string|max:500',
            'settings_about.about_mission_point_2' => 'nullable|string|max:500',
            'settings_about.about_mission_point_3' => 'nullable|string|max:500',
            'settings_about.about_mission_point_4' => 'nullable|string|max:500',
            'settings_about.about_team_title' => 'nullable|string|max:255',
            'settings_about.about_team_content_1' => 'nullable|string|max:2000',
            'settings_about.about_team_content_2' => 'nullable|string|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'settings_about.array' => 'Data pengaturan halaman Tentang Kami tidak valid.',
        ];
    }
}
