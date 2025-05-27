<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class AboutController extends Controller
{
    /**
     * Display the about us page.
     */
    public function index()
    {
        // Contoh jika ingin mengambil data dari settings
        // $aboutTitle = Setting::where('key', 'about_us_title')->first()?->value ?? 'Tentang Kami';
        // $aboutContent = Setting::where('key', 'about_us_content')->first()?->value ?? 'Deskripsi tentang catering Anda...';
        // return view('public.about', compact('aboutTitle', 'aboutContent'));

        // Untuk saat ini, kita biarkan view yang mengatur kontennya
        return view('public.about');
    }
}
