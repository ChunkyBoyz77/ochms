@extends('layouts.landlord')

@section('title', 'Create Listing')

@section('content')

<!-- Quill CSS -->
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">

<style>
/* Quill placeholder override */
.ql-editor.ql-blank::before {
    font-style: normal;      /* remove italic */
    font-size: 1rem;         /* Tailwind text-base (16px) */
    color: #9ca3af;          /* Tailwind text-gray-400 */
}
</style>


<!-- Quill JS -->
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>


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

@if ($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl mb-6">
        <p class="font-semibold mb-2">
            Please fix the following issues before publishing:
        </p>
        <ul class="text-sm list-disc pl-5 space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

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
                        applyRoomRules(value);
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

            {{-- PROPERTY LAYOUT --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">

                <div>
                    <input
                        type="number"
                        name="bedrooms"
                        id="bedrooms"
                        min="0"
                        placeholder="Number of Bedrooms e.g. 3"
                        class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>

                <div>
                    <input
                        type="number"
                        name="bathrooms"
                        id="bathrooms"
                        min="0"
                        placeholder="Number of Bathrooms e.g. 2"
                        class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>

                <div>
                    <input
                        type="number"
                        name="beds"
                        id="beds"
                        min="0"
                        placeholder="Number of Beds e.g. 4"
                        class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>

                <div>
                    <input
                        type="number"
                        name="max_occupants"
                        id="max_occupants"
                        min="1"
                        placeholder="Max Occupant e.g. 5"
                        class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>

            </div>



           <div class="relative">
            <!-- BORDER WRAPPER (Tailwind controls everything) -->
            <div
                class="rounded-lg border border-gray-400 bg-white
                    focus-within:border-red-500
                    focus-within:ring-2 focus-within:ring-red-500
                    transition overflow-hidden"
            >
                <!-- Quill Editor -->
                <div id="description-editor" class="min-h-[140px]"></div>
            </div>

            <!-- Hidden input -->
            <input type="hidden" name="description" id="description">
        </div>


            
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
            <input type="text"
                id="address-input"
                name="address"
                placeholder="Full address"
                class="w-full px-4 py-3 border rounded-lg">

            <div id="map"
                class="rounded-xl border border-gray-300 h-[350px]">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-xs text-gray-500">Latitude</p>
                    <p class="font-semibold text-gray-800" id="lat-display">—</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-xs text-gray-500">Longitude</p>
                    <p class="font-semibold text-gray-800" id="lng-display">—</p>
                </div>
            </div>



            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">
            <input type="hidden" id="distance_to_umpsa" name="distance_to_umpsa">


            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-xs text-gray-500">Distance to UMPSA</p>
                    <p class="font-semibold text-gray-800">
                        <span id="distance-umpsa">Auto-calculated</span> km
                    </p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg opacity-50">
                    <p class="text-xs text-gray-500">Distance to Convenience Store</p>
                    <p class="font-semibold text-gray-800">
                        Auto-calculated km
                    </p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg opacity-50">
                    <p class="text-xs text-gray-500">Distance to Public Transport</p>
                    <p class="font-semibold text-gray-800">
                        Auto-calculated km
                    </p>
                </div>

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

            <!-- Cancellation Policy -->
            <div>

                <div class="rounded-lg border border-gray-500 bg-white
                            focus-within:border-red-500
                            focus-within:ring-2 focus-within:ring-red-500
                            transition overflow-hidden">
                    <div id="policy_cancellation-editor" class="min-h-[120px]"></div>
                </div>

                <input type="hidden" name="policy_cancellation" id="policy_cancellation">
            </div>

            <!-- Refund Policy -->
            <div>

                <div class="rounded-lg border border-gray-500 bg-white
                            focus-within:border-red-500
                            focus-within:ring-2 focus-within:ring-red-500
                            transition overflow-hidden">
                    <div id="policy_refund-editor" class="min-h-[120px]"></div>
                </div>

                <input type="hidden" name="policy_refund" id="policy_refund">
            </div>

            <!-- Early Move-In Policy -->
            <div>

                <div class="rounded-lg border border-gray-500 bg-white
                            focus-within:border-red-500
                            focus-within:ring-2 focus-within:ring-red-500
                            transition overflow-hidden">
                    <div id="policy_early_movein-editor" class="min-h-[120px]"></div>
                </div>

                <input type="hidden" name="policy_early_movein" id="policy_early_movein">
            </div>

            <!-- Late Payment Policy -->
            <div>

                <div class="rounded-lg border border-gray-500 bg-white
                            focus-within:border-red-500
                            focus-within:ring-2 focus-within:ring-red-500
                            transition overflow-hidden">
                    <div id="policy_late_payment-editor" class="min-h-[120px]"></div>
                </div>

                <input type="hidden" name="policy_late_payment" id="policy_late_payment">
            </div>

            <!-- Additional Rules -->
            <div>

                <div class="rounded-lg border border-gray-500 bg-white
                            focus-within:border-red-500
                            focus-within:ring-2 focus-within:ring-red-500
                            transition overflow-hidden">
                    <div id="policy_additional-editor" class="min-h-[140px]"></div>
                </div>

                <input type="hidden" name="policy_additional" id="policy_additional">
            </div>

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

<script
  src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&libraries=places"
  defer>
</script>


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

    if (section === 'basic') {
        const type = document.querySelector('[name="property_type"]')?.value;

        if (type === 'Room') {
            document.getElementById('bedrooms').value = 1;
            document.getElementById('beds').value = 1;
            document.getElementById('max_occupants').value = 1;
        }
    }


    if (section === 'pricing' && !validateDeposit()) {
        alert('Deposit cannot exceed 200% of the monthly rent.');
        return;
    }

    const sectionEl = document.getElementById(section + '-content');
    syncQuillEditors();
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

const UMPSA = {
    lat: 3.5449,
    lng: 103.4281
};

let map;
let marker;
let autocomplete;

function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        center: UMPSA,
        zoom: 14,
        mapTypeControl: false,
        streetViewControl: false,
    });

    marker = new google.maps.Marker({
        map,
        position: UMPSA,
        draggable: true
    });

    marker.addListener('dragend', updateLatLngFromMarker);

    initAutocomplete();
}

window.addEventListener('load', initMap);

function initAutocomplete() {
    const input = document.getElementById('address-input');

    autocomplete = new google.maps.places.Autocomplete(input, {
        fields: ['geometry', 'formatted_address'],
        componentRestrictions: { country: 'my' }
    });

    autocomplete.addListener('place_changed', () => {
        const place = autocomplete.getPlace();

        if (!place.geometry) return;

        const location = place.geometry.location;

        map.setCenter(location);
        marker.setPosition(location);

        updateHiddenLatLng(location.lat(), location.lng());
        calculateDistanceToUMPSA(location.lat(), location.lng());
    });
}

function updateLatLngFromMarker() {
    const pos = marker.getPosition();
    updateHiddenLatLng(pos.lat(), pos.lng());
    calculateDistanceToUMPSA(pos.lat(), pos.lng());
}

function updateHiddenLatLng(lat, lng) {
    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lng;
}

function calculateDistanceToUMPSA(lat, lng) {
    const R = 6371; // km
    const dLat = deg2rad(UMPSA.lat - lat);
    const dLng = deg2rad(UMPSA.lng - lng);

    const a =
        Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(deg2rad(lat)) *
        Math.cos(deg2rad(UMPSA.lat)) *
        Math.sin(dLng / 2) *
        Math.sin(dLng / 2);

    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    const distance = R * c;

    document.getElementById('distance_to_umpsa').value =
        distance.toFixed(2);

    updateDistanceUI(distance);
}

function deg2rad(deg) {
    return deg * (Math.PI / 180);
}

function updateDistanceUI(distance) {
    document.getElementById('distance-umpsa').textContent =
        distance.toFixed(2);
}

function updateHiddenLatLng(lat, lng) {
    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lng;

    document.getElementById('lat-display').textContent = lat.toFixed(6);
    document.getElementById('lng-display').textContent = lng.toFixed(6);
}


const quillOptions = {
    theme: 'snow',
    placeholder: 'Write here…',
    modules: {
        toolbar: [
            [{ header: [1, 2, 3, false] }],
            ['bold', 'italic', 'underline'],
            [{ list: 'ordered' }, { list: 'bullet' }],
            ['link'],
            ['clean']
        ]
    }
};

const editors = {};

// Description
editors.description = new Quill('#description-editor', {
    theme: 'snow',
    placeholder: 'Describe the property, neighborhood, and why it\'s suitable for students',
    modules: {
        toolbar: [
            ['bold', 'italic', 'underline'],
            [{ list: 'ordered' }, { list: 'bullet' }],
            ['link'],
            ['clean']
        ]
    }
});

// Cancellation Policy
editors.policy_cancellation = new Quill('#policy_cancellation-editor', {
    theme: 'snow',
    placeholder: 'Cancellation Policy',
    modules: {
        toolbar: [
            ['bold', 'italic', 'underline'],
            [{ list: 'ordered' }, { list: 'bullet' }],
            ['link'],
            ['clean']
        ]
    }
});

// Refund Policy
editors.policy_refund = new Quill('#policy_refund-editor', {
    theme: 'snow',
    placeholder: 'Refund Policy',
    modules: {
        toolbar: [
            ['bold', 'italic', 'underline'],
            [{ list: 'ordered' }, { list: 'bullet' }],
            ['link'],
            ['clean']
        ]
    }
});

// Early Move-In Policy
editors.policy_early_movein = new Quill('#policy_early_movein-editor', {
    theme: 'snow',
    placeholder: 'Early Move-In Policy',
    modules: {
        toolbar: [
            ['bold', 'italic', 'underline'],
            [{ list: 'ordered' }, { list: 'bullet' }],
            ['link'],
            ['clean']
        ]
    }
});

// Early Move-In Policy
editors.policy_late_payment = new Quill('#policy_late_payment-editor', {
    theme: 'snow',
    placeholder: 'Late Payment Policy',
    modules: {
        toolbar: [
            ['bold', 'italic', 'underline'],
            [{ list: 'ordered' }, { list: 'bullet' }],
            ['link'],
            ['clean']
        ]
    }
});

// Early Move-In Policy
editors.policy_additional = new Quill('#policy_additional-editor', {
    theme: 'snow',
    placeholder: 'Additional House Rules (Optional)',
    modules: {
        toolbar: [
            ['bold', 'italic', 'underline'],
            [{ list: 'ordered' }, { list: 'bullet' }],
            ['link'],
            ['clean']
        ]
    }
});


function syncQuillEditors() {
    Object.keys(editors).forEach(key => {
        const input = document.getElementById(key);
        if (input) {
            input.value = editors[key].root.innerHTML;
        }
    });
}

Object.keys(editors).forEach(key => {
    editors[key].on('text-change', () => {
        sectionsDirty.basic = true;
        updateSaveButton('basic');
    });
});

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.ql-toolbar, .ql-container').forEach(el => {
        el.classList.remove('border');
    });
});

function validateLayout() {
    const beds = +document.querySelector('[name="beds"]')?.value || 0;
    const bedrooms = +document.querySelector('[name="bedrooms"]')?.value || 0;
    const occupants = +document.querySelector('[name="max_occupants"]')?.value || 0;

    if (beds && bedrooms && beds < bedrooms) {
        alert('Beds cannot be fewer than bedrooms.');
        return false;
    }

    if (occupants && beds && occupants < beds) {
        alert('Max occupants cannot be fewer than beds.');
        return false;
    }

    return true;
}

function applyRoomRules(propertyType) {
    const bedrooms = document.getElementById('bedrooms');
    const beds = document.getElementById('beds');
    const occupants = document.getElementById('max_occupants');

    if (!bedrooms || !beds || !occupants) return;

    if (propertyType === 'Room') {
        // Force values
        bedrooms.value = 1;
        beds.value = 1;
        occupants.value = 1;

        // Lock fields
        bedrooms.readOnly = true;
        beds.readOnly = true;
        occupants.readOnly = true;

        bedrooms.classList.add('bg-gray-100');
        beds.classList.add('bg-gray-100');
        occupants.classList.add('bg-gray-100');
    } else {
        // Unlock for Apartment / House
        bedrooms.readOnly = false;
        beds.readOnly = false;
        occupants.readOnly = false;

        bedrooms.classList.remove('bg-gray-100');
        beds.classList.remove('bg-gray-100');
        occupants.classList.remove('bg-gray-100');
    }
}
</script>

@if ($errors->any())
<script>
    window.scrollTo({ top: 0, behavior: 'smooth' });
</script>
@endif







@endsection
