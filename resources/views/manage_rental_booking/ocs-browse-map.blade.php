@extends('layouts.ocs')

@section('content')

<style>
/* ===== PRICE PIN ===== */
.price-pin {
    position: absolute;
    transform: translate(-50%, -100%);
    background: #374151;
    color: white;
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 700;
    white-space: nowrap;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    transition: all 0.2s ease;
    border: 2px solid white;
}

.price-pin:hover {
    transform: translate(-50%, -105%) scale(1.08);
    box-shadow: 0 6px 16px rgba(0,0,0,0.4);
}

.price-pin::after {
    content: "";
    position: absolute;
    left: 50%;
    bottom: -8px;
    transform: translateX(-50%);
    width: 0;
    height: 0;
    border-left: 8px solid transparent;
    border-right: 8px solid transparent;
    border-top: 8px solid #374151;
    transition: border-top-color 0.2s ease;
}

.price-pin.active {
    background: #1f2937;
    transform: translate(-50%, -110%) scale(1.15);
    box-shadow: 0 8px 20px rgba(0,0,0,0.5);
}

.price-pin.active::after {
    border-top-color: #1f2937;
}

/* ===== CUSTOM SCROLLBAR ===== */
aside::-webkit-scrollbar {
    width: 6px;
}

aside::-webkit-scrollbar-track {
    background: transparent;
}

aside::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 3px;
}

aside::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}
</style>

<div class="h-[calc(100vh-72px)] flex overflow-hidden relative bg-white rounded-2xl">

    {{-- MAP --}}
    <div class="flex-1 relative">
        <div id="map" class="w-full h-full"></div>

        {{-- Top Controls Bar --}}
        <div class="absolute top-6 left-6 right-6 flex items-center justify-between gap-4 z-10">
            {{-- Back Button --}}
            <a href="{{ route('ocs.listings.browse', request()->query()) }}"
               class="bg-gray-900 hover:bg-gray-800 text-white shadow-lg  px-4 py-2.5 rounded-lg text-sm font-semibold text-gray-900 transition-all flex items-center gap-2 group">
                <i class="fa-solid fa-arrow-left text-white"></i>
                <span>Back to List</span>
            </a>
        </div>

        {{-- Map Info Badge --}}
        <div class="absolute bottom-6 left-6 bg-white shadow-lg rounded-lg px-4 py-3 text-sm z-10">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 bg-gray-900 rounded-full animate-pulse"></div>
                <span class="font-semibold text-gray-900">{{ count($listings) }} properties</span>
            </div>
        </div>
    </div>

    {{-- LIST SIDEBAR --}}
    <aside class="w-[420px] overflow-y-auto bg-white border-l border-gray-200">

        {{-- Header --}}
        <div class="sticky top-0 bg-white z-20 border-b border-gray-200 px-6 py-4">
            <h2 class="text-lg font-bold text-gray-900">{{ count($listings) }} properties</h2>
            <p class="text-sm text-gray-500 mt-0.5">Showing available listings</p>
        </div>

        {{-- Listings --}}
        <div class="p-4 space-y-4">
            @foreach($listings as $listing)
                <div
                    class="bg-white rounded-xl border border-gray-200 overflow-hidden
                           cursor-pointer transition-all duration-200
                           hover:border-gray-900 hover:shadow-lg group"
                    data-listing-id="{{ $listing->id }}"
                    data-url="{{ route('ocs.listings.show', $listing) }}">

                    {{-- Image --}}
                    <div class="relative h-52 overflow-hidden bg-gray-100">
                        <img
                            src="{{ $listing->images->first()?->image_path
                                ? asset('storage/'.$listing->images->first()->image_path)
                                : asset('images/ocs-taman-placeholder.jpg') }}"
                            class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                        
                        {{-- Verified Badge --}}
                        <div class="absolute top-3 left-3 bg-white/95 backdrop-blur-sm px-2.5 py-1 rounded-md text-xs font-semibold text-gray-900 flex items-center gap-1 shadow-sm">
                            <i class="fa-solid fa-circle-check text-green-500 text-xs"></i>
                            Verified
                        </div>

                         {{-- RATING BADGE --}}
                        @if($listing->reviews_count > 0)
                            <div
                                class="absolute top-3 right-3
                                    bg-black/80 backdrop-blur
                                    text-white text-xs
                                    px-3 py-1.5 rounded-lg
                                    flex items-center gap-1.5
                                    shadow-md">

                                <i class="fa-solid fa-star text-yellow-400"></i>

                                <span class="font-semibold">
                                    {{ number_format($listing->reviews_avg_rating, 1) }}
                                </span>

                                <span class="text-gray-300">
                                    ({{ $listing->reviews_count }})
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="p-4">
                        {{-- Price --}}
                        <div class="mb-2">
                            <span class="text-2xl font-bold text-gray-900">RM {{ number_format($listing->monthly_rent) }}</span>
                            <span class="text-sm text-gray-500 ml-1">/ month</span>
                        </div>

                        {{-- Title --}}
                        <h3 class="font-semibold text-base text-gray-900 line-clamp-2 mb-2 group-hover:text-gray-700 transition-colors">
                            {{ $listing->title }}
                        </h3>

                        {{-- Address --}}
                        <div class="flex items-start gap-2 text-sm text-gray-600">
                            <i class="fa-solid fa-location-dot text-gray-400 mt-0.5 flex-shrink-0"></i>
                            <p class="line-clamp-1">{{ $listing->address }}</p>
                        </div>

                        {{-- Divider --}}
                        <div class="h-px bg-gray-200 my-3"></div>

                        @php
                            $visibleBadges = collect($listing->badges)->take(2);
                            $extraCount = collect($listing->badges)->count() - $visibleBadges->count();
                        @endphp

                        {{-- Footer Info --}}
                        <div class="flex items-center gap-2 text-xs">
                            <div class="flex items-center gap-2 flex-wrap flex-1">
                                @foreach($visibleBadges as $badge)
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium border
                                        {{ $badge['style'] === 'green'
                                            ? 'bg-green-50 text-green-700 border-green-200'
                                            : 'bg-gray-100 text-gray-700 border-gray-200' }}">
                                        {{ $badge['label'] }}
                                    </span>
                                @endforeach

                                @if($extraCount > 0)
                                    <span class="px-2.5 py-1 rounded-full bg-gray-100 text-gray-600 border border-gray-200 text-xs font-medium">
                                        +{{ $extraCount }} more
                                    </span>
                                @endif
                            </div>

                            @if($listing->distance_to_umpsa)
                                <div class="flex items-center gap-1.5 text-gray-500 flex-shrink-0">
                                    <span class="text-gray-400">•</span>
                                    <span class="font-medium">{{ number_format($listing->distance_to_umpsa, 1) }} km</span>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        {{-- Bottom Padding --}}
        <div class="h-4"></div>

    </aside>

</div>

@php
$mapListings = $listings->map(fn($l) => [
    'id' => $l->id,
    'title' => $l->title,
    'lat' => (float) $l->latitude,
    'lng' => (float) $l->longitude,
    'price' => number_format($l->monthly_rent),
])->values();
@endphp

<script>
window.mapListings = @json($mapListings);

document.addEventListener('DOMContentLoaded', () => {

    if (!window.mapListings.length) return;

    const map = new google.maps.Map(document.getElementById('map'), {
        center: {
            lat: window.mapListings[0].lat,
            lng: window.mapListings[0].lng
        },
        zoom: 14,
        disableDefaultUI: true,
        zoomControl: true
    });

    const bounds = new google.maps.LatLngBounds();
    const pins = {};

    // ===== Custom Overlay =====
    class PricePin extends google.maps.OverlayView {
        constructor(listing) {
            super();
            this.listing = listing;
            this.div = document.createElement('div');
            this.div.className = 'price-pin';
            this.div.innerText = `RM ${listing.price}`;
        }

        onAdd() {
            this.getPanes().overlayMouseTarget.appendChild(this.div);

            this.div.addEventListener('click', () => {
                const card = document.querySelector(
                    `[data-listing-id="${this.listing.id}"]`
                );
                card?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                card?.classList.add('ring-2', 'ring-gray-900');
            });
        }

        draw() {
            const pos = this.getProjection().fromLatLngToDivPixel(
                new google.maps.LatLng(this.listing.lat, this.listing.lng)
            );

            if (pos) {
                this.div.style.left = pos.x + 'px';
                this.div.style.top = pos.y + 'px';
            }
        }

        onRemove() {
            this.div.remove();
        }

        setActive(active) {
            this.div.classList.toggle('active', active);
        }
    }

    window.mapListings.forEach(listing => {
        const pin = new PricePin(listing);
        pin.setMap(map);
        pins[listing.id] = pin;

        bounds.extend({ lat: listing.lat, lng: listing.lng });
    });

    map.fitBounds(bounds);

    // ===== Card Hover Sync =====
    document.querySelectorAll('[data-listing-id]').forEach(card => {
        const id = card.dataset.listingId;

        card.addEventListener('mouseenter', () => {
            pins[id]?.setActive(true);
        });

        card.addEventListener('mouseleave', () => {
            pins[id]?.setActive(false);
        });
    });

});
</script>
<script>
document.querySelectorAll('[data-url]').forEach(card => {
    card.addEventListener('click', (e) => {
        // Prevent accidental text selection
        e.preventDefault();
        window.location.href = card.dataset.url;
    });
});
</script>


@endsection