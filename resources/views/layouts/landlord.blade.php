<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>OCHMS - Landlord @yield('title')</title>

    <!-- Icons -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Fonts (optional, if you use nicer ones) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Tailwind via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 font-[Poppins]">

    {{-- Header --}}
    @include('layouts.header.landlord-header')

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

@if (session('withdraw_success'))
    <div id="flashMessage"
         class="fixed top-6 left-1/2 -translate-x-1/2 z-50
                bg-red-600 text-white
                px-6 py-3 rounded-lg shadow-lg
                flex items-center gap-3">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <span>{{ session('withdraw_success') }}</span>
    </div>
@endif

    {{-- SUCCESS BANNER --}}
@if (session('request_success'))
    <div id="flashMessage"
         class="fixed top-6 left-1/2 -translate-x-1/2 z-50
                bg-green-600 text-white
                px-6 py-3 rounded-lg shadow-lg
                flex items-center gap-3">
        <i class="fa-solid fa-circle-check"></i>
        <span>{{ session('request_success') }}</span>
    </div>
@endif

{{-- ERROR / REJECTION BANNER --}}
@if (session('request_error'))
    <div id="flashMessage"
         class="fixed top-6 left-1/2 -translate-x-1/2 z-50
                bg-red-600 text-white
                px-6 py-3 rounded-lg shadow-lg
                flex items-center gap-3">
        <i class="fa-solid fa-circle-xmark"></i>
        <span>{{ session('request_error') }}</span>
    </div>
@endif

<!-- ================= DELETE LISTING MODAL ================= -->
<div id="deleteListingModal"
     class="hidden fixed inset-0 z-50 flex items-center justify-center
            bg-black bg-opacity-50">

    <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">

        <!-- Header -->
        <h2 class="text-lg font-semibold text-gray-800 mb-2">
            Delete Listing
        </h2>

        <p class="text-sm text-gray-600 mb-6">
            Are you sure you want to delete this listing?
            This action <span class="font-semibold text-red-600">cannot be undone</span>.
        </p>

        <!-- Actions -->
        <div class="flex justify-end gap-3">

            <button
                type="button"
                onclick="closeDeleteListingModal()"
                class="px-4 py-2 rounded-lg
                       border border-gray-300
                       text-gray-700
                       hover:bg-gray-100 transition">
                Cancel
            </button>

            <button
                type="button"
                onclick="confirmDeleteListing()"
                class="px-4 py-2 rounded-lg
                       bg-red-600 hover:bg-red-700
                       text-white font-medium transition">
                Yes, Delete
            </button>

        </div>
    </div>
</div>

<!-- ========================= -->
    <!-- APPROVE MODAL -->
    <!-- ========================= -->
    <div id="approveModal"
         class="hidden fixed inset-0 z-50 flex items-center justify-center
                bg-black bg-opacity-50">

        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">
                Approve Booking Request
            </h2>

            <p class="text-sm text-gray-600 mb-6">
                Are you sure you want to approve this student's booking request?
                This action cannot be undone.
            </p>

            <form id="approveForm" method="POST">
                @csrf
                <div class="flex justify-end gap-3">
                    <button type="button"
                            onclick="closeApproveModal()"
                            class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300">
                        Cancel
                    </button>

                    <button type="submit"
                            class="px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold">
                        Approve
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================= -->
    <!-- REJECT MODAL -->
    <!-- ========================= -->
    <div id="rejectModal"
         class="hidden fixed inset-0 z-50 flex items-center justify-center
                bg-black bg-opacity-50">

        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">
                Reject Booking Request
            </h2>

            <form id="rejectForm" method="POST">
                @csrf

                <p class="text-sm text-gray-600 mb-6">
                Are you sure you want to reject this student's booking request?
                This action cannot be undone.
                </p>

                <div class="flex justify-end gap-3">
                    <button type="button"
                            onclick="closeRejectModal()"
                            class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300">
                        Cancel
                    </button>

                    <button type="submit"
                            class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white font-semibold">
                        Reject
                    </button>
                </div>
            </form>
        </div>
    </div>

        <!-- ================= TERMINATE RENT MODAL ================= -->
     <div id="terminateRentModal"
     class="hidden fixed inset-0 z-50 flex items-center justify-center
            bg-black bg-opacity-50">

        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">

            <h2 class="text-lg font-semibold text-gray-800 mb-2">
                Terminate Rent
            </h2>

            <p class="text-sm text-gray-600 mb-6">
                This will end the current tenancy and make the property
                available again.
                <span class="font-semibold text-red-600">
                    This action cannot be undone.
                </span>
            </p>

            <div class="flex justify-end gap-3">

                <button
                    onclick="closeTerminateModal()"
                    class="px-4 py-2 rounded-lg
                        border border-gray-300
                        text-gray-700 hover:bg-gray-100">
                    Cancel
                </button>

               <form id="terminateForm" method="POST">
                    @csrf
                    <button
                        class="px-4 py-2 rounded-lg
                            bg-red-600 hover:bg-red-700
                            text-white font-medium">
                        Yes, Terminate
                    </button>
                </form>


            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <main class="min-h-screen px-10 py-8">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('layouts.footer')

    <form id="deleteListingForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
    </form>


</body>

<script>
function openApproveModal(actionUrl) {
    document.getElementById('approveForm').action = actionUrl;
    document.getElementById('approveModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeApproveModal() {
    document.getElementById('approveModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function openRejectModal(actionUrl) {
    document.getElementById('rejectForm').action = actionUrl;
    document.getElementById('rejectModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close on ESC
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        closeApproveModal();
        closeRejectModal();
    }
});

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

<script>
let listingToDelete = null;

function openDeleteListingModal(listingId) {
    listingToDelete = listingId;
    document.getElementById('deleteListingModal').classList.remove('hidden');
}

function closeDeleteListingModal() {
    listingToDelete = null;
    document.getElementById('deleteListingModal').classList.add('hidden');
}

function confirmDeleteListing() {
    if (!listingToDelete) return;

    const form = document.getElementById('deleteListingForm');
    form.action = `/landlord/listings/${listingToDelete}`;
    form.submit();
}
</script>

<script>
function openTerminateModal(listingId) {
    const form = document.getElementById('terminateForm');
    form.action = `/landlord/listings/${listingId}/terminate`;

    document.getElementById('terminateRentModal')
        .classList.remove('hidden');

    document.body.style.overflow = 'hidden';
}

function closeTerminateModal() {
    document.getElementById('terminateRentModal')
        .classList.add('hidden');

    document.body.style.overflow = 'auto';
}
</script>




</html>
