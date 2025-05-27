<footer class="bg-gray-800 text-white py-8 mt-auto">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p>&copy; {{ date('Y') }}
            {{ app(\App\Models\Setting::class)->where('key', 'site_name')->first()?->value ?? config('app.name', 'Catering Lezat') }}.
            All Rights Reserved.</p>
        <p class="text-sm text-gray-400">
            Powered by Laravel & Tailwind CSS
        </p>
    </div>
</footer>
