<footer class="footer footer-transparent d-print-none">
  <div class="container-xl">
    <div class="row text-center align-items-center flex-row-reverse">
      <!-- Dokumentasi & Source Code -->
      <div class="col-lg-auto ms-lg-auto">
        <ul class="list-inline list-inline-dots mb-0">
          <li class="list-inline-item">
            <a href="https://tabler.io/docs " target="_blank" class="link-secondary" rel="noopener">Dokumentasi Tabler</a>
          </li>
          <li class="list-inline-item">
            <a href="https://github.com/tabler/tabler " target="_blank" class="link-secondary" rel="noopener">Source Code Tabler</a>
          </li>
        </ul>
      </div>

      <!-- Copyright & Versi -->
      <div class="col-12 col-lg-auto mt-3 mt-lg-0">
        <ul class="list-inline list-inline-dots mb-0">
          <li class="list-inline-item">
            Copyright &copy; {{ date('Y') }}
            <a href="{{ route('home') }}" class="link-secondary" target="_blank" rel="noopener">
              {{ app(\App\Models\Setting::class)->where('key', 'site_name')->first()?->value ?? config('app.name', 'Catering Lezat') }}
            </a>.
            All rights reserved.
          </li>
          <li class="list-inline-item">
            <span class="link-secondary">Versi 1.0.0</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</footer>