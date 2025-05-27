<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MenuItem;
use App\Models\Setting;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index()
    {
        $featuredItems = MenuItem::where('is_featured', true)
            ->with('menuCategory') // Eager load category
            ->take(6) // Ambil 6 item unggulan
            ->get();

        // Ambil data promosi dari settings atau cara lain
        // Untuk sementara, kita bisa hardcode atau ambil dari settings jika sudah ada seeder-nya
        $promotionSetting = Setting::where('key', 'homepage_promotion_message')->first();
        $promotionMessage = $promotionSetting ? $promotionSetting->value : 'Selamat datang di layanan catering kami! Nikmati berbagai menu lezat dengan harga spesial.';

        return view('public.home', compact('featuredItems', 'promotionMessage'));
    }
}
