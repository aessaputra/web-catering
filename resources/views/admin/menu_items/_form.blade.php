@csrf
<div class="row">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">Detail Item Menu</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label required" for="name">Nama Item Menu</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                        id="name" value="{{ old('name', $menuItem->name ?? '') }}"
                        placeholder="Contoh: Ayam Bakar Madu">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label required" for="menu_category_id">Kategori Menu</label>
                    <select class="form-select @error('menu_category_id') is-invalid @enderror" name="menu_category_id"
                        id="menu_category_id">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('menu_category_id', $menuItem->menu_category_id ?? '') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('menu_category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="description">Deskripsi</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description"
                        rows="5" placeholder="Deskripsi singkat mengenai item menu ini...">{{ old('description', $menuItem->description ?? '') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label required" for="price">Harga (Rp)</label>
                    <input type="number" class="form-control @error('price') is-invalid @enderror" name="price"
                        id="price" value="{{ old('price', $menuItem->price ?? '') }}" placeholder="Contoh: 25000"
                        min="0" step="100">
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured"
                            value="1" {{ old('is_featured', $menuItem->is_featured ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_featured">
                            Jadikan Menu Unggulan?
                        </label>
                    </div>
                    @error('is_featured')
                        <div class="text-danger text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Gambar Item Menu</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label" for="image">Upload Gambar</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror" name="image"
                        id="image" onchange="previewImage(event)">
                    <small class="form-hint">Kosongkan jika tidak ingin mengubah gambar. Maks 2MB (jpg, png, gif, svg,
                        webp).</small>
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                @php
                    $currentImage = $menuItem->image_path ?? null;
                @endphp
                <div class="mt-2">
                    <img id="imagePreview"
                        src="{{ $currentImage ? asset('storage/' . $currentImage) : 'https://via.placeholder.com/300x200.png?text=Preview' }}"
                        alt="Preview Gambar" class="img-fluid rounded"
                        style="max-height: 200px; display: block; margin-left: auto; margin-right: auto;">
                </div>
                @if ($currentImage)
                    <div class="mt-2 text-center">
                        <small>Gambar saat ini: {{ $currentImage }}</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>


<div class="card-footer text-end mt-3">
    <a href="{{ route('admin.menu-items.index') }}" class="btn btn-link">Batal</a>
    <button type="submit" class="btn btn-primary ms-auto">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-device-floppy" width="24"
            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
            stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
            <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
            <path d="M14 4l0 4l-6 0l0 -4" />
        </svg>
        Simpan Item Menu
    </button>
</div>

@push('scripts')
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('imagePreview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        // Jika menggunakan TomSelect untuk dropdown kategori (opsional, tapi bagus)
        // document.addEventListener("DOMContentLoaded", function () {
        //     var el;
        //     window.TomSelect && (new TomSelect(el = document.getElementById('menu_category_id'), {
        //         copyClassesToDropdown: false,
        //         dropdownParent: 'body',
        //         controlInput: '<input>',
        //         render:{
        //             item: function(data,escape) {
        //                 if( data.customProperties ){
        //                     return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
        //                 }
        //                 return '<div>' + escape(data.text) + '</div>';
        //             },
        //             option: function(data,escape){
        //                 if( data.customProperties ){
        //                     return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
        //                 }
        //                 return '<div>' + escape(data.text) + '</div>';
        //             },
        //         }
        //     }));
        // });
    </script>
@endpush
