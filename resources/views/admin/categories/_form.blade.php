@csrf
<div class="mb-3">
    <label class="form-label required" for="name">Nama Kategori</label>
    <input type="text" class="form-control @error('name') is-invalid @enderror"
           name="name" id="name" value="{{ old('name', $category->name ?? '') }}"
           placeholder="Contoh: Nasi Kotak">
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label" for="slug">Slug (URL Friendly)</label>
    <input type="text" class="form-control @error('slug') is-invalid @enderror"
           name="slug" id="slug" value="{{ old('slug', $category->slug ?? '') }}"
           placeholder="Contoh: nasi-kotak (otomatis jika dikosongi)">
    <small class="form-hint">Biarkan kosong untuk digenerate otomatis dari nama. Hanya berisi huruf kecil, angka, dan tanda hubung (-).</small>
    @error('slug')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label" for="description">Deskripsi (Opsional)</label>
    <textarea class="form-control @error('description') is-invalid @enderror"
              name="description" id="description" rows="4"
              placeholder="Deskripsi singkat mengenai kategori menu ini...">{{ old('description', $category->description ?? '') }}</textarea>
    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="card-footer text-end">
    <a href="{{ route('admin.categories.index') }}" class="btn btn-link">Batal</a>
    <button type="submit" class="btn btn-primary ms-auto">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-device-floppy" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
        Simpan Kategori
    </button>
</div>