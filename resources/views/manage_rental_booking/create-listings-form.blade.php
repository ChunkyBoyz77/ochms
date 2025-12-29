@extends('layouts.landlord')

@section('title', 'Create Listing')

@section('content')

<!-- ================= PAGE HEADER ================= -->
<div class="mb-10">
    <div class="flex items-center gap-4 mb-3">
        <a href="{{ route('landlord.listings') }}"
           class="text-gray-600 hover:text-gray-900 text-xl">
            <i class="fa-solid fa-arrow-left"></i>
        </a>

        <h1 class="text-2xl sm:text-3xl font-semibold">
            Create New Property Listing
        </h1>
    </div>

    <p class="text-gray-600 max-w-4xl">
        Provide detailed and accurate information. Students rely on this page
        to decide where they will live for months or years.
    </p>
</div>

<!-- ================= PROGRESS BAR ================= -->
<div class="bg-white border rounded-2xl shadow-sm p-6 mb-10">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-gray-800">
            Listing Completion
        </h2>
        <p class="text-xl font-bold text-red-500">
            <span id="listingProgress">0</span>%
        </p>
    </div>

    <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden">
        <div id="listingProgressBar"
             class="h-full bg-red-500 rounded-full transition-all duration-500"
             style="width: 0%">
        </div>
    </div>
</div>


<form method="POST"
      action="{{ route('landlord.listings.store') }}"
      enctype="multipart/form-data"
      class="space-y-6">

@csrf

<!-- ================================================= -->
<!-- SECTION TEMPLATE -->
<!-- ================================================= -->
@php
function sectionHeader($id, $icon, $title, $subtitle, $highlight = false) {
    $bg = $highlight ? 'hover:bg-red-50' : 'hover:bg-gray-50';
    $iconBg = $highlight ? 'bg-red-500 text-white' : 'bg-red-100 text-red-500';
    return <<<HTML
<button type="button"
    onclick="toggleSection('$id')"
    class="w-full px-6 flex items-center justify-between
           min-h-[96px] $bg transition">

    <div class="flex items-center gap-4 h-full">
        <div class="w-10 h-10 rounded-full $iconBg flex items-center justify-center">
            <i id="$id-icon" class="fa-solid fa-$icon"></i>
        </div>

        <div class="text-left leading-tight">
            <h3 class="font-semibold text-lg">$title</h3>
            <p class="text-sm text-gray-600 mt-1">$subtitle</p>
        </div>
    </div>

    <i id="$id-chevron"
       class="fa-solid fa-chevron-down text-gray-400 transition-transform"></i>
</button>
HTML;
}
@endphp

<!-- ================================================= -->
<!-- SECTION 1: BASIC PROPERTY INFO -->
<!-- ================================================= -->
<div class="bg-white rounded-2xl shadow-md overflow-hidden">
    {!! sectionHeader('basic','house','Basic Information','Title & overview') !!}
    <div id="basic-content" class="px-6 pb-6">
        <div class="pt-4 space-y-4">
            <input type="text" name="title"
                   placeholder="Listing title (e.g. Modern Apartment Near UMPSA)"
                   class="w-full px-4 py-3 rounded-lg
                            border 
                            focus:outline-none
                            focus:border-red-500
                            focus:ring-2 focus:ring-red-500
                            focus:ring-offset-0">

            <!-- PROPERTY TYPE (Custom Dropdown) -->
            <div 
                x-data="{
                    open: false,
                    selected: '',
                    select(value) {
                        this.selected = value;
                        this.open = false;
                    }
                }"
                class="relative"
            >
                <!-- Hidden input for form + saveSection -->
                <input type="hidden" name="property_type" :value="selected">

                <!-- Trigger (looks like input field) -->
                <button
                    type="button"
                    @click="open = !open"
                    class="w-full px-4 py-3 border border-gray-500 rounded-lg bg-white
                    text-left text-gray-700
                    flex items-center justify-between
                    focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent
                    transition">

                    <span
                        x-text="selected || 'Property Type'"
                        :class="selected ? 'text-gray-800' : 'text-gray-500'">
                    </span>

                    <i class="fa-solid fa-chevron-down text-xs text-gray-400 transition-transform"
                    :class="open ? 'rotate-180' : ''"></i>
                </button>

                <!-- Dropdown -->
                <div
                    x-show="open"
                    @click.away="open = false"
                    x-transition
                    class="absolute z-50 mt-2 w-full
                        bg-white rounded-lg shadow-lg
                        border border-gray-200 overflow-hidden">

                    <button
                        type="button"
                        @click="select('Room')"
                        class="w-full text-left px-4 py-3 text-sm
                            hover:bg-red-50 transition">
                        Room
                    </button>

                    <button
                        type="button"
                        @click="select('Apartment')"
                        class="w-full text-left px-4 py-3 text-sm
                            hover:bg-red-50 transition">
                        Apartment
                    </button>

                    <button
                        type="button"
                        @click="select('House')"
                        class="w-full text-left px-4 py-3 text-sm
                            hover:bg-red-50 transition">
                        House
                    </button>
                </div>
            </div>


            <textarea name="description" rows="5"
                placeholder="Describe the property, neighborhood, and why it's suitable for students"
                class="w-full px-4 py-3 border rounded-lg resize-none"></textarea>
            
            <button type="button"
                onclick="saveSection('basic', this)"
                class="save-btn bg-red-500 hover:bg-red-600 text-white
                    px-6 py-3 rounded-lg font-semibold flex items-center gap-2">
                <span class="btn-text">Save & Continue</span>
            </button>

        </div>
    </div>
</div>

<!-- ================================================= -->
<!-- SECTION 2: LOCATION -->
<!-- ================================================= -->
<div class="bg-white rounded-2xl shadow-md overflow-hidden">
    {!! sectionHeader('location','map-location-dot','Location & Distances','Map-based distance calculation') !!}
    <div id="location-content" class="hidden px-6 pb-6">
        <div class="pt-4 space-y-6">
            <input type="text" name="address"
                   placeholder="Full address"
                   class="w-full px-4 py-3 border rounded-lg">

            <div class="rounded-xl border border-gray-300 h-[350px]
                        flex items-center justify-center text-gray-500">
                📍 Map API Placeholder
            </div>

            <input type="hidden" name="latitude">
            <input type="hidden" name="longitude">
            <input type="hidden" name="distance_to_umpsa">

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach(['UMPSA','Convenience Store','Public Transport'] as $d)
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-xs text-gray-500">Distance to {{ $d }}</p>
                    <p class="font-semibold text-gray-800">Auto-calculated</p>
                </div>
                @endforeach
            </div>

            <button type="button"
                onclick="saveSection('location', this)"
                class="save-btn bg-red-500 hover:bg-red-600 text-white
                    px-6 py-3 rounded-lg font-semibold flex items-center gap-2">
                <span class="btn-text">Save & Continue</span>
            </button>
        </div>
    </div>
</div>

<!-- ================================================= -->
<!-- SECTION 3: PRICING -->
<!-- ================================================= -->
<div class="bg-white rounded-2xl shadow-md overflow-hidden">
    {!! sectionHeader('pricing','money-bill-wave','Pricing','Rent & deposits') !!}
    
    <div id="pricing-content" class="hidden px-6 pb-6">
        <div class="pt-4 space-y-6">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <input type="number" name="monthly_rent" id="monthly_rent"
                       placeholder="Monthly Rent (RM)"
                       class="px-4 py-3 border rounded-lg">

                <input type="number" name="deposit" id="deposit"
                       placeholder="Deposit (RM)"
                       class="px-4 py-3 border rounded-lg">
                
                <p id="deposit-error"
                class="text-sm text-red-500 hidden">
                    Deposit cannot exceed 200% of monthly rent.
                </p>

            </div>

            <!-- ACTION -->
            <div class="pt-4">
                <button type="button"
                    onclick="saveSection('pricing', this)"
                    class="save-btn bg-red-500 hover:bg-red-600 text-white
                        px-6 py-3 rounded-lg font-semibold flex items-center gap-2">
                    <span class="btn-text">Save & Continue</span>
                </button>
            </div>

        </div>
    </div>
</div>


<!-- ================================================= -->
<!-- SECTION 4: POLICIES & AMENITIES -->
<!-- ================================================= -->
<div class="bg-white rounded-2xl shadow-md overflow-hidden">
    {!! sectionHeader(
        'rules',
        'file-contract',
        'Policies & Amenities',
        'Important terms students should know'
    ) !!}

    <div id="rules-content" class="hidden px-6 pb-6">
        <div class="pt-6 space-y-8">

            <!-- AMENITIES -->
            <div>
                <h4 class="font-semibold text-lg mb-3">Amenities</h4>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach(['WiFi','Parking','Air Conditioning','Washing Machine','Security','Furnished'] as $a)
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="amenities[]" value="{{ $a }}" class="
                                w-4 h-4
                                rounded-full
                                border-gray-300
                                appearance-auto
                                accent-red-500
                                focus:ring-red-500
                            ">
                            <span>{{ $a }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- POLICIES -->
            <div class="space-y-6">
                <textarea name="policy_cancellation" rows="3"
                    placeholder="Cancellation Policy"
                    class="w-full px-4 py-3 border rounded-lg resize-none"></textarea>

                <textarea name="policy_refund" rows="3"
                    placeholder="Refund Policy"
                    class="w-full px-4 py-3 border rounded-lg resize-none"></textarea>

                <textarea name="policy_early_movein" rows="3"
                    placeholder="Early Move-In Policy"
                    class="w-full px-4 py-3 border rounded-lg resize-none"></textarea>

                <textarea name="policy_late_payment" rows="3"
                    placeholder="Late Payment Policy"
                    class="w-full px-4 py-3 border rounded-lg resize-none"></textarea>

                <textarea name="policy_additional" rows="4"
                    placeholder="Additional House Rules (Optional)"
                    class="w-full px-4 py-3 border rounded-lg resize-none"></textarea>
            </div>

            <!-- ACTION -->
            <div class="pt-4">
                <button type="button"
                    onclick="saveSection('rules', this)"
                    class="save-btn bg-red-500 hover:bg-red-600 text-white
                        px-6 py-3 rounded-lg font-semibold flex items-center gap-2">
                    <span class="btn-text">Save & Continue</span>
                </button>
            </div>

        </div>
    </div>
</div>



<!-- ================================================= -->
<!-- SECTION 5: MEDIA & DOCUMENTS -->
<!-- ================================================= -->
<div class="bg-white rounded-2xl shadow-md overflow-hidden border-2 border-red-200">
    {!! sectionHeader('media','camera','Photos & Documents','Upload media & verification documents', true) !!}

    <div id="media-content" class="hidden px-6 pb-6">
        <div class="pt-6 space-y-8">

            <!-- ================= PROPERTY MEDIA ================= -->
            <div>
                <h4 class="font-semibold text-lg mb-2">
                    Property Media
                </h4>
                <p class="text-sm text-gray-600 mb-4">
                    Upload clear photos or videos of the property (rooms, kitchen, exterior).
                </p>

                <!-- Dropzone -->
                <label
                    class="block w-full cursor-pointer
                           border-2 border-dashed border-gray-300
                           rounded-2xl p-8 text-center
                           hover:border-red-400 hover:bg-red-50
                           transition">

                    <div class="flex flex-col items-center gap-3">
                        <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center">
                            <i class="fa-solid fa-cloud-arrow-up text-red-500 text-xl"></i>
                        </div>

                        <div>
                            <p class="font-medium text-gray-800">
                                Drag & drop photos or videos here
                            </p>
                            <p class="text-sm text-gray-500">
                                or click to browse files
                            </p>
                        </div>

                        <p class="text-xs text-gray-400 mt-2">
                            Accepted: JPG, PNG, MP4, MOV (Max 20MB each)
                        </p>
                    </div>

                    <input
                        type="file"
                        accept="image/*,video/*"
                        multiple
                        class="hidden"
                        name="media"
                        id="media-input">
                </label>
                <div id="media-preview"
                 class="grid grid-cols-2 sm:grid-cols-3 gap-4 mt-6"></div>

            </div>

            <!-- ================= DIVIDER ================= -->
            <div class="border-t border-gray-200"></div>

            <!-- ================= DOCUMENTS ================= -->
            <div>
                <h4 class="font-semibold text-lg mb-2">
                    Ownership / Authorization Document
                </h4>
                <p class="text-sm text-gray-600 mb-4">
                    Upload proof that you are authorized to rent this property.
                </p>

                <label
                    class="flex items-center gap-4
                           border border-gray-300 rounded-xl
                           px-5 py-4 cursor-pointer
                           hover:border-red-400 hover:bg-red-50
                           transition">

                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                        <i class="fa-solid fa-file-contract text-red-500"></i>
                    </div>

                    <div class="flex-1">
                        <p class="font-medium text-gray-800">
                            Upload document
                        </p>
                        <p class="text-xs text-gray-500">
                            PDF or image format
                        </p>
                    </div>

                    <span class="text-sm text-red-500 font-medium">
                        Browse
                    </span>

                    <input
                        type="file"
                        id="grant-input"
                        name="grant"
                        accept="application/pdf,image/*"
                        class="hidden">

                </label>
                <div id="document-preview" class="mt-4"></div>

            </div>

            <!-- ================= ACTION ================= -->
            <div class="pt-4">
                <button type="button"
                    onclick="saveSection('media', this)"
                    class="save-btn bg-red-500 hover:bg-red-600 text-white
                           px-6 py-3 rounded-lg font-semibold flex items-center gap-2">
                    <span class="btn-text">Save & Continue</span>
                </button>
            </div>

        </div>
    </div>
</div>



<div class="text-center pt-10">
    <button type="submit"
        class="bg-red-500 hover:bg-red-600 text-white
               px-10 py-4 rounded-xl font-semibold text-lg shadow-lg">
        Publish Listing
    </button>
</div>

</form>

<script>
/* ================= CONFIG ================= */
const sectionOrder = ['basic','location','pricing','rules','media'];

let sectionsCompleted = {
    basic: false,
    location: false,
    pricing: false,
    rules: false,
    media: false
};

let sectionsDirty = {
    basic: false,
    location: false,
    pricing: false,
    rules: false,
    media: false
};

/* ================= MEDIA STATE ================= */
let mediaFiles = [];

/* ================= INIT ================= */
document.addEventListener('DOMContentLoaded', () => {
    sectionOrder.forEach(section => {
        watchSectionChanges(section);
        updateSaveButton(section);
    });
});

/* ================= TOGGLE ================= */
function toggleSection(section) {
    const content = document.getElementById(section + '-content');
    const chevron = document.getElementById(section + '-chevron');

    content.classList.toggle('hidden');
    chevron.style.transform = content.classList.contains('hidden')
        ? 'rotate(0deg)'
        : 'rotate(180deg)';

    if (!content.classList.contains('hidden')) {
        updateSaveButton(section);
    }
}

/* ================= UI STATE ================= */
function markSectionComplete(section) {
    const icon = document.getElementById(section + '-icon');
    if (!icon) return;

    icon.className = 'fa-solid fa-circle-check text-green-500';
    icon.parentElement.classList.remove('bg-red-100','bg-red-500');
    icon.parentElement.classList.add('bg-green-100');

    sectionsCompleted[section] = true;
}

/* ================= DIRTY TRACKING ================= */
function watchSectionChanges(section) {
    const sectionEl = document.getElementById(section + '-content');
    if (!sectionEl) return;

    sectionEl.addEventListener('input', () => {
        if (sectionsCompleted[section]) {
            sectionsDirty[section] = true;
            updateSaveButton(section);
        }
    });

    sectionEl.addEventListener('change', () => {
        if (sectionsCompleted[section]) {
            sectionsDirty[section] = true;
            updateSaveButton(section);
        }
    });
}

/* ================= PRICING VALIDATION ================= */
const rentInput = document.getElementById('monthly_rent');
const depositInput = document.getElementById('deposit');
const depositError = document.getElementById('deposit-error');

function validateDeposit() {
    const rent = parseFloat(rentInput.value);
    const deposit = parseFloat(depositInput.value);

    if (!rent || !deposit) {
        depositError.classList.add('hidden');
        depositInput.classList.remove('border-red-500');
        return true;
    }

    if (deposit > rent * 2) {
        depositError.classList.remove('hidden');
        depositInput.classList.add('border-red-500');
        return false;
    }

    depositError.classList.add('hidden');
    depositInput.classList.remove('border-red-500');
    return true;
}

depositInput?.addEventListener('input', validateDeposit);
rentInput?.addEventListener('input', validateDeposit);

/* ================= SAVE SECTION ================= */
async function saveSection(section, btn) {
    console.group(`📦 Saving section: ${section}`);

    if (section === 'pricing' && !validateDeposit()) {
        alert('Deposit cannot exceed 200% of the monthly rent.');
        return;
    }

    const sectionEl = document.getElementById(section + '-content');
    const inputs = sectionEl.querySelectorAll('input, textarea, select');

    const data = new FormData();
    data.append('section', section);
    data.append('_token', '{{ csrf_token() }}');

    inputs.forEach(input => {
        if (!input.name) return;

        if (input.type === 'file') {
            [...input.files].forEach(file => {
                data.append(input.name, file);
            });
        }
        else if (input.type === 'checkbox') {
            if (input.checked) {
                data.append(input.name + '[]', input.value);
            }
        }
        else {
            data.append(input.name, input.value);
        }
    });

    /* ✅ MEDIA FILES COME FROM BUFFER */
    if (section === 'media') {
    mediaFiles.forEach(file => {
        data.append('images[]', file);
    });

    if (grantFile) {
        data.append('grant', grantFile);
    }
}


    const originalText = btn.textContent;
    btn.disabled = true;
    btn.textContent = 'Saving...';

    try {
        const res = await fetch(
            "{{ route('landlord.listings.saveDraft') }}",
            { method: 'POST', body: data }
        );

        const result = await res.json();

        if (!res.ok) {
            throw new Error(result.message || 'Save failed');
        }

        markSectionComplete(section);
        sectionsDirty[section] = false;

        updateSaveButton(section);
        updateProgress();

        toggleSection(section);
        openNextSection(section);

    } catch (e) {
        alert('Failed to save section. Please try again.');
        console.error(e);
    } finally {
        btn.disabled = false;
        btn.textContent = originalText;
        console.groupEnd();
    }
}

/* ================= BUTTON STATE ================= */
function updateSaveButton(section) {
    const sectionEl = document.getElementById(section + '-content');
    if (!sectionEl) return;

    const btn = sectionEl.querySelector('.save-btn');
    if (!btn) return;

    if (sectionsCompleted[section] && sectionsDirty[section]) {
        btn.disabled = false;
        btn.textContent = 'Save Changes';
        btn.classList.remove('opacity-70','cursor-not-allowed');
        return;
    }

    if (sectionsCompleted[section]) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-check mr-2"></i>Saved';
        btn.classList.add('opacity-70','cursor-not-allowed');
        return;
    }

    btn.disabled = false;
    btn.textContent = 'Save & Continue';
    btn.classList.remove('opacity-70','cursor-not-allowed');
}

/* ================= PROGRESS ================= */
function updateProgress() {
    const completed = Object.values(sectionsCompleted).filter(Boolean).length;
    const progress = Math.round((completed / sectionOrder.length) * 100);

    document.getElementById('listingProgress').textContent = progress;
    document.getElementById('listingProgressBar').style.width = progress + '%';
}

/* ================= FLOW ================= */
function openNextSection(current) {
    const idx = sectionOrder.indexOf(current);
    if (idx !== -1 && sectionOrder[idx + 1]) {
        toggleSection(sectionOrder[idx + 1]);
    }
}

/* ================= MEDIA INPUT + DRAG ================= */
const mediaInput = document.getElementById('media-input');
const mediaPreview = document.getElementById('media-preview');
const mediaDropzone = mediaInput?.closest('label');

function addMediaFiles(files) {
    [...files].forEach(file => {
        if (!file.type.startsWith('image/') && !file.type.startsWith('video/')) return;
        mediaFiles.push(file);
    });
    renderMediaPreview();
}

mediaInput?.addEventListener('change', () => {
    addMediaFiles(mediaInput.files);
    mediaInput.value = '';
});

mediaDropzone?.addEventListener('dragover', e => {
    e.preventDefault();
    mediaDropzone.classList.add('border-red-500','bg-red-50');
});

mediaDropzone?.addEventListener('dragleave', () => {
    mediaDropzone.classList.remove('border-red-500','bg-red-50');
});

mediaDropzone?.addEventListener('drop', e => {
    e.preventDefault();
    mediaDropzone.classList.remove('border-red-500','bg-red-50');
    addMediaFiles(e.dataTransfer.files);
});

/* ================= MEDIA PREVIEW ================= */
function renderMediaPreview() {
    mediaPreview.innerHTML = '';

    mediaFiles.forEach((file, index) => {
        const url = URL.createObjectURL(file);

        const wrapper = document.createElement('div');
        wrapper.className = 'relative rounded-xl overflow-hidden border bg-gray-100';

        wrapper.innerHTML = file.type.startsWith('image')
            ? `<img src="${url}" class="w-full h-40 object-cover">`
            : `<video src="${url}" class="w-full h-40 object-cover" muted></video>`;

        const removeBtn = document.createElement('button');
        removeBtn.innerHTML = '&times;';
        removeBtn.className =
            'absolute top-2 right-2 w-7 h-7 rounded-full bg-black/60 text-white flex items-center justify-center';

        removeBtn.onclick = () => {
            mediaFiles.splice(index, 1);
            renderMediaPreview();
        };

        wrapper.appendChild(removeBtn);
        mediaPreview.appendChild(wrapper);
    });
}

/* ================= DOCUMENT PREVIEW ================= */
const docInput = document.getElementById('grant-input');
const docPreview = document.getElementById('document-preview');

docInput.addEventListener('change', () => {
    docPreview.innerHTML = '';

    if (!docInput.files || !docInput.files.length) return;

    grantFile = docInput.files[0]; // ✅ STORE IT

    const fileURL = URL.createObjectURL(grantFile);

    docPreview.innerHTML = `
        <a href="${fileURL}"
           target="_blank"
           class="flex items-center gap-4
                  border border-gray-300 rounded-xl
                  px-4 py-3 bg-gray-50
                  hover:bg-red-50 transition
                  cursor-pointer">

            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                <i class="fa-solid fa-file text-red-500"></i>
            </div>

            <div class="flex-1">
                <p class="text-sm font-medium text-gray-800 underline">
                    ${grantFile.name}
                </p>
                <p class="text-xs text-gray-500">
                    ${(grantFile.size / 1024 / 1024).toFixed(2)} MB
                </p>
            </div>

            <i class="fa-solid fa-arrow-up-right-from-square text-gray-400"></i>
        </a>
    `;

    docInput.value = ''; // safe now
});



</script>






@endsection
