<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>OCHMS @yield('title')</title>

    <!-- Icons -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Fonts used by OCS -->
    <link href="https://fonts.googleapis.com/css2?family=Barrio&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Barriecito&display=swap" rel="stylesheet">

    <!-- Tailwind via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<style>
.price-pin {
    position: relative;
    background: #111827;
    color: white;
    padding: 6px 12px;
    border-radius: 999px;
    font-size: 13px;
    font-weight: 600;
    white-space: nowrap;
    transform: translateY(-6px);
}

.price-pin::after {
    content: "";
    position: absolute;
    left: 50%;
    bottom: -6px;
    transform: translateX(-50%);
    width: 0;
    height: 0;
    border-left: 6px solid transparent;
    border-right: 6px solid transparent;
    border-top: 6px solid #111827;
}


</style>


<body class="bg-gray-100 overflow-x-hidden">

    {{-- Header --}}
    @include('layouts.header.ocs-header')
    @if (session('success'))
        <div id="flashMessage"
            class="fixed top-6 left-1/2 -translate-x-1/2 z-50
                    bg-green-600 text-white
                    px-6 py-3 rounded-lg shadow-lg
                    flex items-center gap-3">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- ERROR / REJECTION BANNER --}}
    @if (session('error'))
        <div id="flashMessage"
            class="fixed top-6 left-1/2 -translate-x-1/2 z-50
                    bg-red-600 text-white
                    px-6 py-3 rounded-lg shadow-lg
                    flex items-center gap-3">
            <i class="fa-solid fa-circle-xmark"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif


    {{-- Flash / Error Slot (optional future use) --}}
    @yield('alerts')

    <!-- ================= REQUEST BOOKING MODAL ================= -->
    <div id="requestBookingModal"
        class="hidden fixed inset-0 z-50 flex items-center justify-center
                bg-black bg-opacity-50">

        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">

            <!-- Header -->
            <h2 class="text-lg font-semibold text-gray-800 mb-2">
                Confirm Booking Request
            </h2>

            <p class="text-sm text-gray-600 mb-6">
                Are you sure you want to request booking for this property?
                The landlord will review your request before approval.
            </p>

            <!-- Actions -->
            <div class="flex justify-end gap-3">

                <button
                    type="button"
                    onclick="closeRequestBookingModal()"
                    class="px-4 py-2 rounded-lg
                        border border-gray-300
                        text-gray-700
                        hover:bg-gray-100 transition">
                    Cancel
                </button>

                <button
                    type="button"
                    onclick="confirmRequestBooking()"
                    class="px-4 py-2 rounded-lg
                        bg-gray-900 hover:bg-gray-800
                        text-white font-medium transition">
                    Yes, Request
                </button>

            </div>
        </div>
    </div>

    <form id="requestBookingForm" method="POST" style="display:none;">
        @csrf
    </form>


    {{-- Main Content --}}
    <main class="min-h-screen px-10 py-8">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('layouts.footer')

    @yield('scripts')

<script
src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}"
defer>
</script>
<script>
let listingToRequest = null;

function openRequestBookingModal(listingId) {
    listingToRequest = listingId;
    document.getElementById('requestBookingModal').classList.remove('hidden');
}

function closeRequestBookingModal() {
    listingToRequest = null;
    document.getElementById('requestBookingModal').classList.add('hidden');
}

function confirmRequestBooking() {
    if (!listingToRequest) return;

    const form = document.getElementById('requestBookingForm');
    form.action = `/ocs/listings/${listingToRequest}/request`;
    form.submit();
}

document.addEventListener('DOMContentLoaded', () => {
    const flash = document.getElementById('flashMessage');
    if (!flash) return;

    setTimeout(() => {
        flash.classList.add('opacity-0', 'transition-opacity', 'duration-500');
    }, 1500);

    setTimeout(() => {
        flash.remove();
    }, 2000);
});
</script>

</body>
</html>
