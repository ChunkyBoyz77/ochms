@extends('layouts.admin')

@section('title', ' | Properties Map')

@section('content')

<style>
/* ===== STATUS MARKERS ===== */
.status-marker {
    position: absolute;
    transform: translate(-50%, -50%);
    width: 28px;
    height: 28px;
    border-radius: 50%;
    border: 3px solid white;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.status-marker:hover {
    transform: translate(-50%, -50%) scale(1.2);
    box-shadow: 0 6px 16px rgba(0,0,0,0.4);
}

.status-marker.published {
    background: #10b981;
}

.status-marker.occupied {
    background: #3b82f6;
}

.status-marker.requested {
    background: #f59e0b;
}

.status-marker.pending {
    background: #6b7280;
}

.status-marker i {
    color: white;
    font-size: 12px;
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
            {{-- Legend --}}
            <div class="bg-white shadow-lg rounded-lg px-5 py-3">
                <div class="flex items-center gap-6 text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded-full bg-green-500 border-2 border-white shadow"></div>
                        <span class="font-medium text-gray-700">Available</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded-full bg-blue-500 border-2 border-white shadow"></div>
                        <span class="font-medium text-gray-700">Occupied</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded-full bg-amber-500 border-2 border-white shadow"></div>
                        <span class="font-medium text-gray-700">Requested</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded-full bg-gray-500 border-2 border-white shadow"></div>
                        <span class="font-medium text-gray-700">Pending</span>
                    </div>
                </div>
            </div>

            {{-- Stats Badge --}}
            <div class="bg-white shadow-lg rounded-lg px-5 py-3">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 bg-purple-500 rounded-full animate-pulse"></div>
                    <span class="font-semibold text-gray-900">{{ count($listings) }} total properties</span>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTER & LIST SIDEBAR --}}
    <aside class="w-[420px] overflow-y-auto bg-white border-l border-gray-200">

        {{-- Header --}}
        <div class="sticky top-0 bg-white z-20 border-b border-gray-200 px-6 py-4">
            <h2 class="text-lg font-bold text-gray-900">Properties Overview</h2>
            <p class="text-sm text-gray-500 mt-0.5">{{ count($listings) }} listings found</p>
        </div>

        {{-- Filter Section --}}
        <div class="p-4 bg-gray-50 border-b border-gray-200">
            <form method="GET" action="{{ route('admin.properties.map') }}" class="space-y-3">
                
                {{-- Status Filter --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Filter by Status</label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="status[]" value="published"
                                   class="w-4 h-4 rounded border-gray-300 text-purple-500 focus:ring-purple-500"
                                   {{ in_array('published', request('status', [])) ? 'checked' : '' }}>
                            <span>Available</span>
                        </label>
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="status[]" value="occupied"
                                   class="w-4 h-4 rounded border-gray-300 text-purple-500 focus:ring-purple-500"
                                   {{ in_array('occupied', request('status', [])) ? 'checked' : '' }}>
                            <span>Occupied</span>
                        </label>
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="status[]" value="requested"
                                   class="w-4 h-4 rounded border-gray-300 text-purple-500 focus:ring-purple-500"
                                   {{ in_array('requested', request('status', [])) ? 'checked' : '' }}>
                            <span>Requested</span>
                        </label>
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="status[]" value="pending"
                                   class="w-4 h-4 rounded border-gray-300 text-purple-500 focus:ring-purple-500"
                                   {{ in_array('pending', request('status', [])) ? 'checked' : '' }}>
                            <span>Pending</span>
                        </label>
                    </div>
                </div>

                {{-- Property Type Filter --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Property Type</label>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach(['Room', 'House', 'Apartment'] as $type)
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="type[]" value="{{ $type }}"
                                       class="w-4 h-4 rounded border-gray-300 text-purple-500 focus:ring-purple-500"
                                       {{ in_array($type, request('type', [])) ? 'checked' : '' }}>
                                <span>{{ $type }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Apply Button --}}
                <button type="submit"
                        class="w-full bg-purple-500 hover:bg-purple-600 text-white py-2.5 rounded-lg text-sm font-semibold transition">
                    Apply Filters
                </button>

                @if(request()->hasAny(['status', 'type']))
                    <a href="{{ route('admin.properties.map') }}"
                       class="w-full block text-center bg-gray-200 hover:bg-gray-300 text-gray-700 py-2.5 rounded-lg text-sm font-semibold transition">
                        Clear Filters
                    </a>
                @endif
            </form>
        </div>

        {{-- Listings --}}
        <div class="p-4 space-y-4">
            @forelse($listings as $listing)
                <div
                    class="bg-white rounded-xl border border-gray-200 overflow-hidden
                           cursor-pointer transition-all duration-200
                           hover:border-purple-500 hover:shadow-lg group"
                    data-listing-id="{{ $listing->id }}"
                    >

                    {{-- Image --}}
                    <div class="relative h-40 overflow-hidden bg-gray-100">
                        <img
                            src="{{ $listing->images->first()?->image_path
                                ? asset('storage/'.$listing->images->first()->image_path)
                                : asset('images/ocs-taman-placeholder.jpg') }}"
                            class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                        
                        {{-- Status Badge --}}
                        <div class="absolute top-3 left-3 px-3 py-1.5 rounded-lg text-xs font-semibold shadow-md
                            {{ $listing->status === 'published' ? 'bg-green-500 text-white' : '' }}
                            {{ $listing->status === 'occupied' ? 'bg-blue-500 text-white' : '' }}
                            {{ $listing->status === 'requested' ? 'bg-amber-500 text-white' : '' }}
                            {{ $listing->status === 'pending' ? 'bg-gray-500 text-white' : '' }}">
                            {{ ucfirst($listing->status) }}
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="p-4">
                        {{-- Title --}}
                        <h3 class="font-semibold text-base text-gray-900 line-clamp-2 mb-3 group-hover:text-purple-600 transition-colors">
                            {{ $listing->title }}
                        </h3>

                        {{-- Landlord Info --}}
                        <div class="space-y-2 mb-3">
                            <div class="flex items-center gap-2 text-sm">
                                <div class="w-6 h-6 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0">
                                    <i class="fa-solid fa-user text-purple-600 text-xs"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-gray-500 text-xs">Landlord</p>
                                    <p class="font-medium text-gray-900 truncate">{{ $listing->landlord->user->name }}</p>
                                </div>
                            </div>

                            {{-- OCS Tenant Info (if occupied) --}}
                            @if($listing->status === 'occupied' && $listing->ocs)
                                <div class="flex items-center gap-2 text-sm">
                                    <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                        <i class="fa-solid fa-user-graduate text-blue-600 text-xs"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-gray-500 text-xs">Tenant (OCS)</p>
                                        <p class="font-medium text-gray-900 truncate">{{ $listing->ocs->user->name }}</p>
                                        <p class="font-mono text-xs text-gray-600">{{ $listing->ocs->matric_id }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Address --}}
                        <div class="flex items-start gap-2 text-sm text-gray-600 mb-3">
                            <i class="fa-solid fa-location-dot text-gray-400 mt-0.5 flex-shrink-0"></i>
                            <p class="line-clamp-2">{{ $listing->address }}</p>
                        </div>

                        {{-- Divider --}}
                        <div class="h-px bg-gray-200 my-3"></div>

                        {{-- Footer Info --}}
                        <div class="flex items-center justify-between text-sm">
                            <div>
                                <span class="text-gray-500">Rent:</span>
                                <span class="font-bold text-gray-900 ml-1">RM {{ number_format($listing->monthly_rent) }}</span>
                            </div>
                            <span class="text-xs text-gray-500">{{ $listing->property_type }}</span>
                        </div>
                    </div>

                </div>
            @empty
                <div class="text-center py-10 text-gray-500">
                    <i class="fa-solid fa-filter-circle-xmark text-4xl mb-3"></i>
                    <p class="font-medium">No properties match your filters</p>
                </div>
            @endforelse
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
    'status' => $l->status,
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
    const markers = {};

    // ===== Custom Status Marker =====
    class StatusMarker extends google.maps.OverlayView {
        constructor(listing) {
            super();
            this.listing = listing;
            this.div = document.createElement('div');
            this.div.className = `status-marker ${listing.status}`;
            
            // Add icon based on status
            const icon = document.createElement('i');
            icon.className = this.getIconClass(listing.status);
            this.div.appendChild(icon);
        }

        getIconClass(status) {
            const icons = {
                'published': 'fa-solid fa-check',
                'occupied': 'fa-solid fa-home',
                'requested': 'fa-solid fa-clock',
                'pending': 'fa-solid fa-hourglass'
            };
            return icons[status] || 'fa-solid fa-circle';
        }

        onAdd() {
            this.getPanes().overlayMouseTarget.appendChild(this.div);

            this.div.addEventListener('click', () => {
                const card = document.querySelector(
                    `[data-listing-id="${this.listing.id}"]`
                );
                card?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                card?.classList.add('ring-2', 'ring-purple-500');
                setTimeout(() => {
                    card?.classList.remove('ring-2', 'ring-purple-500');
                }, 2000);
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
    }

    window.mapListings.forEach(listing => {
        const marker = new StatusMarker(listing);
        marker.setMap(map);
        markers[listing.id] = marker;

        bounds.extend({ lat: listing.lat, lng: listing.lng });
    });

    map.fitBounds(bounds);

});
</script>

<script>
document.querySelectorAll('[data-url]').forEach(card => {
    card.addEventListener('click', (e) => {
        e.preventDefault();
        window.location.href = card.dataset.url;
    });
});
</script>

@endsection