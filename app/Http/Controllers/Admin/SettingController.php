<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Http\Requests\Admin\UpdateSettingsRequest;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    // Definisikan key settings yang akan dikelola
    private $settingKeys = [
        'site_name',
        'site_description',
        'contact_email',
        'contact_whatsapp',
        'address',
        'instagram_url',
        'facebook_url',
        'Maps_url',
        'homepage_promotion_message',
        'site_logo',
        'hero_image_homepage'
    ];

    public function index()
    {
        $settingsFromDB = Setting::whereIn('key', $this->settingKeys)
            ->pluck('value', 'key');
        $settings = [];
        foreach ($this->settingKeys as $key) {
            $settings[$key] = $settingsFromDB->get($key, '');
        }
        return view('admin.settings.index', compact('settings'));
    }

    public function store(UpdateSettingsRequest $request)
    {
        $validatedInputs = $request->validated();
        $textSettings = $validatedInputs['settings'] ?? []; // Ambil array 'settings' jika ada

        DB::beginTransaction(); // Mulai transaksi (opsional, tapi baik untuk beberapa operasi)
        try {
            // Handle File Upload untuk Logo
            if ($request->hasFile('site_logo_file')) {
                // Hapus logo lama jika ada
                $oldLogoPath = Setting::where('key', 'site_logo')->first()?->value;
                if ($oldLogoPath && Storage::disk('public')->exists($oldLogoPath)) {
                    Storage::disk('public')->delete($oldLogoPath);
                }

                // Simpan logo baru ke storage/app/public/logos
                // Gunakan nama file unik untuk menghindari konflik
                $file = $request->file('site_logo_file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('logos', $fileName, 'public'); // Simpan di storage/app/public/logos

                Setting::updateOrCreate(
                    ['key' => 'site_logo'],
                    ['value' => $path]
                );
            } elseif ($request->boolean('remove_current_logo')) {
                // Hapus logo jika checkbox remove_current_logo dicentang dan tidak ada file baru yang diupload
                $oldLogoPath = Setting::where('key', 'site_logo')->first()?->value;
                if ($oldLogoPath && Storage::disk('public')->exists($oldLogoPath)) {
                    Storage::disk('public')->delete($oldLogoPath);
                }
                Setting::updateOrCreate(
                    ['key' => 'site_logo'],
                    ['value' => ''] // Set path logo menjadi kosong
                );
            }

            // Handle File Upload untuk Hero Image Homepage
            if ($request->hasFile('hero_image_homepage_file')) {
                $oldHeroImagePath = Setting::where('key', 'hero_image_homepage')->first()?->value;
                if ($oldHeroImagePath && Storage::disk('public')->exists($oldHeroImagePath)) {
                    Storage::disk('public')->delete($oldHeroImagePath);
                }
                $file = $request->file('hero_image_homepage_file');
                $fileName = 'hero_home_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('settings', $fileName, 'public');
                Setting::updateOrCreate(['key' => 'hero_image_homepage'], ['value' => $path]);
            } elseif ($request->boolean('remove_current_hero_image')) {
                $oldHeroImagePath = Setting::where('key', 'hero_image_homepage')->first()?->value;
                if ($oldHeroImagePath && Storage::disk('public')->exists($oldHeroImagePath)) {
                    Storage::disk('public')->delete($oldHeroImagePath);
                }
                Setting::updateOrCreate(['key' => 'hero_image_homepage'], ['value' => '']);
            }

            // Simpan settings teks lainnya
            if (!empty($textSettings)) {
                foreach ($textSettings as $key => $value) {
                    // Pastikan key ada di definedSettingKeys dan bukan 'site_logo' (karena sudah dihandle)
                    if (in_array($key, $this->settingKeys) && $key !== 'site_logo') {
                        Setting::updateOrCreate(
                            ['key' => $key],
                            ['value' => $value ?? '']
                        );
                    }
                }
            }

            DB::commit();

            Cache::forget('site_global_settings');

            Alert::success('Berhasil!', 'Pengaturan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan pengaturan: ' . $e->getMessage(), ['exception' => $e]);
            Alert::error('Gagal!', 'Terjadi kesalahan saat menyimpan pengaturan. Silakan coba lagi.');
        }

        return redirect()->route('admin.settings.index');
    }

    /**
     * Display a listing of the contact messages.
     */
    public function contactMessagesIndex(Request $request)
    {
        $query = ContactMessage::orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                    ->orWhere('email', 'like', $searchTerm)
                    ->orWhere('message', 'like', $searchTerm);
            });
        }

        if ($request->filled('status')) {
            if ($request->status == 'read') {
                $query->where('is_read', true);
            } elseif ($request->status == 'unread') {
                $query->where('is_read', false);
            }
        }


        $contactMessages = $query->paginate(15)->withQueryString();
        return view('admin.settings.contact_messages_index', compact('contactMessages'));
    }

    /**
     * Display the specified contact message.
     */
    public function showContactMessage(ContactMessage $message) // Route Model Binding
    {
        // Tandai sebagai sudah dibaca jika belum
        if (!$message->is_read) {
            $message->is_read = true;
            $message->save();
        }
        return view('admin.settings.contact_messages_show', compact('message'));
    }

    /**
     * Remove the specified contact message from storage.
     */
    public function destroyContactMessage(ContactMessage $message) // Route Model Binding
    {
        $message->delete();
        Alert::success('Pesan Dihapus!', 'Pesan kontak telah berhasil dihapus.');
        return redirect()->route('admin.contact-messages.index');
    }
}
