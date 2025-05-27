@extends('public.layouts.app')

@section('title', 'Tentang Kami')

@section('content')
    <div class="bg-white py-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-800">ℹ️ Tentang Kami</h1>
                <p class="mt-2 text-lg text-gray-600">Mengenal lebih dekat
                    {{ app(\App\Models\Setting::class)->where('key', 'site_name')->first()?->value ?? 'Catering Lezat' }}
                </p>
            </div>

            <div class="max-w-3xl mx-auto text-gray-700 leading-relaxed">
                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-orange-600 mb-4">Sejarah Kami</h2>
                    <p class="mb-4">
                        Selamat datang di
                        {{ app(\App\Models\Setting::class)->where('key', 'site_name')->first()?->value ?? 'Catering Lezat' }}!
                        Kami memulai perjalanan kuliner kami pada tahun YYYY dengan misi sederhana: menyajikan makanan
                        berkualitas tinggi dengan cita rasa otentik untuk setiap acara spesial Anda. Dari dapur rumahan
                        kecil, kami telah berkembang menjadi penyedia layanan catering terpercaya yang melayani berbagai
                        acara, mulai dari pertemuan keluarga hingga event korporat besar.
                    </p>
                    <p>
                        Dedikasi kami terhadap kualitas bahan baku segar, resep tradisional yang dijaga keasliannya, serta
                        inovasi dalam penyajian telah membawa kami hingga sejauh ini.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-orange-600 mb-4">Visi & Misi</h2>
                    <h3 class="text-xl font-medium text-gray-800 mb-2">Visi</h3>
                    <p class="mb-4">
                        Menjadi pilihan utama layanan catering yang dikenal karena kualitas, inovasi, dan pelayanan yang tak
                        terlupakan di [Kota/Wilayah Anda].
                    </p>
                    <h3 class="text-xl font-medium text-gray-800 mb-2">Misi</h3>
                    <ul class="list-disc list-inside ml-4 space-y-2">
                        <li>Menyajikan hidangan lezat dan higienis menggunakan bahan-bahan segar berkualitas terbaik.</li>
                        <li>Memberikan pelayanan yang ramah, profesional, dan responsif terhadap kebutuhan pelanggan.</li>
                        <li>Terus berinovasi dalam menu dan konsep penyajian untuk memberikan pengalaman kuliner yang unik.
                        </li>
                        <li>Membangun hubungan jangka panjang yang baik dengan pelanggan, pemasok, dan tim kami.</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-orange-600 mb-4">Tim Kami</h2>
                    <p>
                        Tim kami terdiri dari koki berpengalaman, staf pelayanan yang handal, dan tim manajemen yang
                        berdedikasi untuk memastikan setiap detail acara Anda berjalan sempurna. Kami percaya bahwa
                        kehangatan dan profesionalisme tim kami adalah salah satu kunci kepuasan pelanggan.
                    </p>
                    {{-- Anda bisa menambahkan foto tim atau detail lebih lanjut di sini --}}
                </section>
            </div>
        </div>
    </div>
@endsection
