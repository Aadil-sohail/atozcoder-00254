@php $product = $product ?? null; @endphp

<div class="row g-3 p-3">

    <div class="col-md-6">
        <label for="name" class="form-label">{{ __('Product Name') }}</label>
        <input id="name" name="name" type="text" value="{{ old('name', $product?->name) }}" required autofocus
            class="form-control @error('name') is-invalid @enderror">
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="sku" class="form-label">{{ __('SKU / Part Number') }}</label>
        <input id="sku" name="sku" type="text" value="{{ old('sku', $product?->sku) }}"
            class="form-control @error('sku') is-invalid @enderror">
        @error('sku')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="variant" class="form-label">{{ __('Variant') }}</label>
        <input id="variant" name="variant" type="text" value="{{ old('variant', $product?->variant) }}"
            class="form-control @error('variant') is-invalid @enderror">
        @error('variant')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="category_id" class="form-label">{{ __('Category') }}</label>
        <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror">
            <option value="">{{ __('Select a category') }}</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $product?->category_id) == $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="subcategory_id" class="form-label">{{ __('Subcategory') }}</label>
        {{-- Options are populated by JS, filtered to the selected category. --}}
        <select id="subcategory_id" name="subcategory_id"
            class="form-select @error('subcategory_id') is-invalid @enderror"
            data-selected="{{ old('subcategory_id', $product?->subcategory_id) }}">
        </select>
        @error('subcategory_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="size" class="form-label">{{ __('Size') }}</label>
        <input id="size" name="size" type="text" value="{{ old('size', $product?->size) }}"
            class="form-control @error('size') is-invalid @enderror">
        @error('size')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="warranty_months" class="form-label">{{ __('Warranty') }}</label>
        <select id="warranty_months" name="warranty_months"
            class="form-select @error('warranty_months') is-invalid @enderror">
            <option value="">{{ __('No warranty') }}</option>
            @foreach ([1, 2, 3, 4, 5, 6, 12] as $months)
                <option value="{{ $months }}" @selected(old('warranty_months', $product?->warranty_months) == $months)>
                    {{ $months === 12 ? __('1 Year') : $months . ' ' . ($months === 1 ? __('Month') : __('Months')) }}
                </option>
            @endforeach
        </select>
        @error('warranty_months')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="cost_price" class="form-label">{{ __('Cost Price') }}</label>
        <input id="cost_price" name="cost_price" type="number" step="0.01" min="0"
            value="{{ old('cost_price', $product?->cost_price) }}"
            class="form-control @error('cost_price') is-invalid @enderror">
        @error('cost_price')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="selling_price" class="form-label">{{ __('Selling Price') }}</label>
        <input id="selling_price" name="selling_price" type="number" step="0.01" min="0"
            value="{{ old('selling_price', $product?->selling_price) }}"
            class="form-control @error('selling_price') is-invalid @enderror">
        @error('selling_price')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12">
        <label for="description" class="form-label">{{ __('Description') }}</label>
        <textarea id="description" name="description" rows="3"
            class="form-control @error('description') is-invalid @enderror">{{ old('description', $product?->description) }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label class="form-label">{{ __('Product Photos (optional)') }}</label>

        @if (!empty($product?->image))
            <div id="existing-images" class="row g-2 mb-2">
                @foreach ($product->image as $path)
                    <div class="col-4 col-sm-3 col-md-2 position-relative" data-image-wrap>
                        <img src="{{ asset($path) }}" class="img-thumbnail w-100" style="height:96px; object-fit:cover;">
                        <button type="button" onclick="toggleDeleteImage(this, {{ json_encode($path) }})"
                            class="btn btn-danger btn-sm position-absolute top-0 end-0 rounded-circle d-flex align-items-center justify-content-center p-0"
                            style="width:22px; height:22px; transform: translate(40%, -40%);">
                            <i class="fa-solid fa-xmark" style="font-size:10px;"></i>
                        </button>
                    </div>
                @endforeach
            </div>
            <div id="delete-inputs"></div>
            <p class="text-muted small">{{ __('Click the × on a photo to remove it when you save.') }}</p>
        @endif

        <div id="drop-zone" ondragover="event.preventDefault()" ondrop="handleDrop(event)"
            onclick="document.getElementById('images').click()"
            class="border border-2 border-dashed rounded p-5 text-center bg-light"
            style="cursor:pointer; border-style: dashed !important;">
            <i class="fa-solid fa-cloud-arrow-up fs-3 text-secondary"></i>
            <p class="mt-2 mb-1 fw-medium">{{ __('Click to upload or drag and drop') }}</p>
            <p class="text-muted small mb-0">{{ __('PNG or JPG, up to 2MB each — multiple photos allowed') }}</p>
            <input id="images" name="images[]" type="file" accept="image/*" multiple class="d-none"
                onchange="handleFilesSelected(this.files)">
        </div>

        @error('images')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
        @error('images.*')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror

        <div id="new-previews" class="row g-2 mt-1 d-none"></div>
    </div>

</div>

@push('scripts')
    {{-- Built here because @json cannot parse a multi-line argument. --}}
    @php
        $subcategoryOptions = $Subcategories
            ->map(fn ($s) => ['id' => $s->id, 'name' => $s->name, 'category_id' => $s->category_id])
            ->values();
    @endphp
    <script>
        // Subcategory options are filtered to whichever category is selected. The
        // server re-checks the pair on submit, so this is convenience, not security.
        (function () {
            const subcategories = @json($subcategoryOptions);

            const categorySelect = document.getElementById('category_id');
            const subSelect = document.getElementById('subcategory_id');

            function renderSubcategories(keepValue) {
                const categoryId = String(categorySelect.value);
                const matches = subcategories.filter(s => String(s.category_id) === categoryId);

                subSelect.innerHTML = '';

                const placeholder = document.createElement('option');
                placeholder.value = '';
                placeholder.textContent = !categoryId
                    ? @json(__('Select a category first'))
                    : (matches.length ? @json(__('Select a subcategory')) : @json(__('No subcategories in this category')));
                subSelect.appendChild(placeholder);

                matches.forEach(function (s) {
                    const option = document.createElement('option');
                    option.value = s.id;
                    option.textContent = s.name;
                    option.selected = String(s.id) === String(keepValue);
                    subSelect.appendChild(option);
                });

                // A disabled select submits nothing, which is the right value here anyway.
                subSelect.disabled = matches.length === 0;
            }

            // On first paint keep the saved/old value; changing category clears it,
            // since a subcategory from the previous category no longer applies.
            renderSubcategories(subSelect.dataset.selected);
            categorySelect.addEventListener('change', () => renderSubcategories(''));
        })();

        function handleFilesSelected(files) {
            const preview = document.getElementById('new-previews');
            preview.innerHTML = '';

            if (!files.length) {
                preview.classList.add('d-none');
                return;
            }

            preview.classList.remove('d-none');
            Array.from(files).forEach(function (file) {
                const col = document.createElement('div');
                col.className = 'col-4 col-sm-3 col-md-2';
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.className = 'img-thumbnail w-100';
                img.style.cssText = 'height:96px; object-fit:cover; outline: 2px solid #6366f1;';
                col.appendChild(img);
                preview.appendChild(col);
            });
        }

        function handleDrop(event) {
            event.preventDefault();
            const input = document.getElementById('images');
            input.files = event.dataTransfer.files;
            handleFilesSelected(input.files);
        }

        function toggleDeleteImage(button, path) {
            const wrap = button.closest('[data-image-wrap]');
            const inputId = 'del-' + path.replace(/[^a-zA-Z0-9]/g, '_');
            const container = document.getElementById('delete-inputs');
            const existing = document.getElementById(inputId);

            if (existing) {
                existing.remove();
                wrap.style.opacity = '';
            } else {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'delete_images[]';
                input.value = path;
                input.id = inputId;
                container.appendChild(input);
                wrap.style.opacity = '0.3';
            }
        }
    </script>
@endpush