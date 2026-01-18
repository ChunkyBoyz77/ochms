<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>OCHMS - Admin @yield('title')</title>

    <!-- Icons -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>


    <!-- Tailwind via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 font-[Poppins]">

    {{-- Header --}}
    @include('layouts.header.admin-header')

    {{-- SUCCESS BANNER --}}
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


    {{-- Main Content --}}
    <main class="min-h-screen px-10 py-8">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('layouts.footer')

    <!-- ========================= -->
    <!-- APPROVE MODAL -->
    <!-- ========================= -->
    <div id="approveModal"
         class="hidden fixed inset-0 z-50 flex items-center justify-center
                bg-black bg-opacity-50">

        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">
                Approve Verification
            </h2>

            <p class="text-sm text-gray-600 mb-6">
                Are you sure you want to approve this landlord verification?
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
                Reject Verification
            </h2>

            <p class="text-sm text-gray-600 mb-4">
                Please provide a reason for rejection.
            </p>

            <form id="rejectForm" method="POST">
                @csrf

                <textarea name="reason"
                          required
                          class="w-full border rounded-lg p-3 text-sm mb-4"
                          placeholder="Reason for rejection"></textarea>

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

</body>

<script
src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}"
async>
</script>
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
</script>
<script>
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


</html>
