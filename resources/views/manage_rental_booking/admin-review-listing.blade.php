@extends('layouts.admin')

@section('title', 'Listing Verification Review')

@section('content')

<h1 class="text-xl font-semibold mb-6">Listing Verification Review</h1>

<div class="w-full bg-white rounded-xl shadow p-6 space-y-6">

    <!-- LISTING HEADER -->
    <div class="flex items-start gap-5">

        {{-- COVER IMAGE --}}
        <img
            src="{{ $listing->images->first()
                    ? asset('storage/'.$listing->images->first()->image_path)
                    : asset('images/ocs-taman-placeholder.jpg') }}"
            class="w-32 h-24 rounded-lg object-cover border">

        <div class="flex-1">
            <h2 class="text-lg font-semibold text-gray-800">
                {{ $listing->title }}
            </h2>

            <p class="text-sm text-gray-500">
                {{ $listing->address }}
            </p>

            <div class="flex items-center gap-3 mt-2 text-sm">
                <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 font-medium">
                    <i class="fa-solid fa-hourglass-half mr-1"></i>
                    Pending
                </span>

                @if($listing->distance_to_umpsa)
                    <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700">
                        {{ number_format($listing->distance_to_umpsa, 2) }} km from UMPSA
                    </span>
                @endif
            </div>
        </div>
    </div>

    <hr>

    <!-- LANDLORD INFO -->
    <h3 class="font-semibold text-gray-800">Landlord</h3>

    <div class="flex items-center gap-4">
        @php
            $name = $listing->landlord->user->name;
            $initials = collect(explode(' ', $name))
                ->map(fn ($n) => strtoupper(substr($n, 0, 1)))
                ->take(2)
                ->implode('');
        @endphp

        @if ($listing->landlord->user->profile_photo_path)
            <img src="{{ Storage::url($listing->landlord->user->profile_photo_path) }}"
                 class="w-12 h-12 rounded-full object-cover border">
        @else
            <div class="w-12 h-12 rounded-full bg-purple-100
                        flex items-center justify-center
                        text-purple-700 font-semibold">
                {{ $initials }}
            </div>
        @endif

        <div>
            <p class="font-medium text-gray-900">
                {{ $listing->landlord->user->name }}
            </p>
            <p class="text-sm text-gray-500">
                {{ $listing->landlord->user->email }}
            </p>
        </div>
    </div>

    <hr>

    <!-- PROPERTY DETAILS -->
    <h3 class="font-semibold text-gray-800">Property Details</h3>

    <div class="grid grid-cols-2 gap-4 ">
        <div>
            <p class="text-gray-500 mb-2">Title</p>
            <p class="font-medium">{{ $listing->title }}</p>
        </div>

        <div>
            <p class="text-gray-500 mb-2">Property Type</p>
            <p class="font-medium">{{ $listing->property_type }}</p>
        </div>
    </div>

    <hr>

    <!-- PRICING DETAILS -->
    <h3 class="font-semibold text-gray-800">Pricing</h3>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <p class="text-gray-500">Monthly Rent</p>
            <p class="font-medium">
                RM {{ number_format($listing->monthly_rent) }}
            </p>
        </div>

        <div>
            <p class="text-gray-500">Deposit</p>
            <p class="font-medium">
                {{ $listing->deposit ? 'RM '.number_format($listing->deposit) : '—' }}
            </p>
        </div>
    </div>

    <hr>

    <!-- DESCRIPTION -->
    <h3 class="font-semibold text-gray-800">Description</h3>

    <div class="prose prose-sm max-w-none text-gray-700">
        {!! $listing->description !!}
    </div>

    <hr>

    <!-- LOCATION DETAILS -->
    <h3 class="font-semibold text-gray-800">Location</h3>

    <div class="grid grid-cols-2 gap-4 ">
        <div>
            <p class="text-gray-500">Address</p>
            <p class="font-medium">{{ $listing->address }}</p>
        </div>

        @if($listing->distance_to_umpsa)
            <div>
                <p class="text-gray-500">Distance to UMPSA</p>
                <p class="font-medium">
                    {{ number_format($listing->distance_to_umpsa, 2) }} km
                </p>
            </div>
        @endif
    </div>

    <div class="mt-4 rounded-xl overflow-hidden border h-[300px]">
        <div id="admin-listing-map" class="w-full h-full"></div>
    </div>

    <hr>


    <!-- AMENITIES -->
    <h3 class="font-semibold text-gray-800">Amenities</h3>

    @if(count($listing->amenities))
        <div class="flex flex-wrap gap-2 ">
            @foreach($listing->amenities as $amenity)
                <span class="px-3 py-2 rounded-lg bg-gray-100 text-gray-700">
                    {{ $amenity }}
                </span>
            @endforeach
        </div>
    @else
        <p class="text-sm text-gray-500">No amenities listed.</p>
    @endif

    <hr>
    
    <!-- POLICIES -->
    <h3 class="font-semibold text-gray-800">Policies</h3>
    <div class="space-y-6 text-sm">

        @if($listing->policy_cancellation)
            <div>
                <p class="font-medium text-gray-700 mb-1">Cancellation Policy</p>
                <div class="prose prose-sm max-w-none text-gray-700">
                    {!! $listing->policy_cancellation !!}
                </div>
            </div>
        @endif

        @if($listing->policy_refund)
            <div>
                <p class="font-medium text-gray-700 mb-1">Refund Policy</p>
                <div class="prose prose-sm max-w-none text-gray-700">
                    {!! $listing->policy_refund !!}
                </div>
            </div>
        @endif

        @if($listing->policy_early_movein)
            <div>
                <p class="font-medium text-gray-700 mb-1">Early Move-In Policy</p>
                <div class="prose prose-sm max-w-none text-gray-700">
                    {!! $listing->policy_early_movein !!}
                </div>
            </div>
        @endif

        @if($listing->policy_late_payment)
            <div>
                <p class="font-medium text-gray-700 mb-1">Late Payment Policy</p>
                <div class="prose prose-sm max-w-none text-gray-700">
                    {!! $listing->policy_late_payment !!}
                </div>
            </div>
        @endif

        @if($listing->policy_additional)
            <div>
                <p class="font-medium text-gray-700 mb-1">Additional Rules</p>
                <div class="prose prose-sm max-w-none text-gray-700">
                    {!! $listing->policy_additional !!}
                </div>
            </div>
        @endif

    </div>

    <hr>

    <!-- MEDIA GALLERY -->
    <h3 class="font-semibold text-gray-800">Property Media</h3>

    @if($listing->images->count())

    <div class="relative w-full">

        <!-- IMAGE DISPLAY -->
        <div class="rounded-xl overflow-hidden border bg-gray-100">
            <img
                id="mediaViewerImage"
                src="{{ asset('storage/'.$listing->images->first()->image_path) }}"
                class="w-full h-[500px] object-cover">
        </div>

        <!-- COUNTER -->
        <div class="mt-2 text-sm text-gray-500 text-center">
            <span id="mediaIndex">1</span> / {{ $listing->images->count() }}
        </div>

        <!-- CONTROLS -->
        <div class="flex justify-between mt-3">

            <button
                type="button"
                onclick="prevMedia()"
                class="px-4 py-2 rounded-lg border
                    bg-white text-gray-700
                    hover:bg-gray-100 transition">
                <i class="fa-solid fa-chevron-left mr-2"></i>Previous
            </button>

            <button
                type="button"
                onclick="nextMedia()"
                class="px-4 py-2 rounded-lg border
                    bg-white text-gray-700
                    hover:bg-gray-100 transition">
                Next <i class="fa-solid fa-chevron-right"></i>
            </button>

        </div>

    </div>

    <script>
        const mediaImages = @json(
            $listing->images->map(fn($img) => asset('storage/'.$img->image_path))
        );

        let currentMediaIndex = 0;

        const mediaImgEl = document.getElementById('mediaViewerImage');
        const mediaIndexEl = document.getElementById('mediaIndex');

        function updateMedia() {
            mediaImgEl.src = mediaImages[currentMediaIndex];
            mediaIndexEl.textContent = currentMediaIndex + 1;
        }

        function nextMedia() {
            if (currentMediaIndex < mediaImages.length - 1) {
                currentMediaIndex++;
                updateMedia();
            }
        }

        function prevMedia() {
            if (currentMediaIndex > 0) {
                currentMediaIndex--;
                updateMedia();
            }
        }
    </script>

    @else
        <p class="text-sm text-gray-500">No images uploaded.</p>
    @endif

    <hr>


    <!-- DOCUMENT -->
    <h3 class="font-semibold text-gray-800">Authorization Document</h3>

    @if($listing->grant_document_path)
        <a href="{{ asset('storage/'.$listing->grant_document_path) }}"
           target="_blank"
           class="flex items-center gap-4
                  border border-gray-300 rounded-xl
                  px-4 py-3 bg-gray-50
                  hover:bg-purple-50 transition">

            <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                <i class="fa-solid fa-file-pdf text-purple-600"></i>
            </div>

            <div class="flex-1">
                <p class="text-sm font-medium text-gray-800">
                    Authorization Document
                </p>
                <p class="text-xs text-gray-500">
                    Click to open
                </p>
            </div>

            <i class="fa-solid fa-arrow-up-right-from-square text-gray-400"></i>
        </a>
    @else
        <p class="text-sm text-gray-500">No document submitted.</p>
    @endif

    <hr>

    <!-- ADMIN ACTIONS -->
    <div class="flex items-center justify-end gap-4 pt-4">

        <button
            onclick="openRejectModal('{{ route('admin.listings.reject', $listing->id) }}')"
            class="flex items-center gap-2
                   bg-red-600 hover:bg-red-700
                   text-white px-6 py-3 rounded-lg font-semibold">
            <i class="fa-solid fa-circle-xmark"></i>
            Reject
        </button>

        <button
            onclick="openApproveModal('{{ route('admin.listings.approve', $listing->id) }}')"
            class="flex items-center gap-2
                   bg-green-600 hover:bg-green-700
                   text-white px-6 py-3 rounded-lg font-semibold">
            <i class="fa-solid fa-circle-check"></i>
            Approve
        </button>

    </div>

</div>

<script>
function initAdminMap() {
    const location = {
        lat: {{ $listing->latitude ?? 3.5449 }},
        lng: {{ $listing->longitude ?? 103.4281 }}
    };

    const map = new google.maps.Map(
        document.getElementById('admin-listing-map'),
        {
            center: location,
            zoom: 15,
            disableDefaultUI: true
        }
    );

    new google.maps.Marker({
        position: location,
        map,
        title: 'Property Location'
    });
}
</script>

<script
    src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&callback=initAdminMap"
    async defer>
</script>

@endsection
