@extends('layouts.landlord')

@section('title', 'My Verification Application')

@section('content')


<!-- HERO -->
<section class="relative bg-gray-900 rounded-2xl overflow-hidden mb-10 h-[30vh] sm:h-[40vh] lg:h-[60vh] min-h-[300px]">
    
    <img src="{{ asset('images/landlord-pending-dashboard-hero.jpg') }}"
         class="absolute inset-0 w-full h-full object-cover opacity-65">

    <div class="relative z-10 h-full flex items-center justify-center text-center px-6">
        <div>
            <h1 class="text-white text-lg
           sm:text-lg
           md:text-3xl
           lg:text-4xl
           xl:text-5xl font-bold">
                Track Your Landlord Verification
            </h1>

            <p class="text-gray-200 mt-4 max-w-2xl mx-auto
                        text-sm
                        sm:text-base
                        md:text-xl">

                Before you can list properties or receive bookings,
                we need to verify your identity and documents.
            </p>

        </div>
    </div>
</section>


<!-- STATUS CARD -->
<div class="relative overflow-hidden bg-yellow-400 
            rounded-2xl shadow-lg p-6 mb-10 text-gray-900">
    
<!-- Decorative clock icons in background -->
    <div class="absolute top-0 right-0 w-40 h-40 bg-yellow-400 opacity-50 rounded-full blur-2xl"></div>
    <div class="absolute -bottom-8 -right-8 opacity-30">
        <i class="fa-solid fa-clock text-orange-500 text-8xl"></i>
    </div>
    <div class="absolute top-8 right-12 yellow-400 opacity-25">
        <i class="fa-solid fa-hourglass-half text-amber-600 text-6xl -rotate-12"></i>
    </div>
    
    <div class="relative flex flex-col sm:flex-row items-start sm:items-start gap-4">
        
        <!-- Icon with timer aesthetic -->
        <div class="flex-shrink-0">
            <div class="flex items-center justify-center w-12 h-12 bg-white/30 backdrop-blur-sm rounded-xl border border-white/40">
                <i class="fa-solid fa-clock text-gray-900 text-xl"></i>
            </div>
        </div>

        <!-- Content -->
        <div class="flex-1 min-w-0 w-full sm:w-auto">
            <div class="flex items-center gap-3 mb-2">
                <h2 class="font-bold text-gray-900 text-xl">
                    Account verification in progress
                </h2>
            </div>

            <p class="text-gray-800 text-sm leading-relaxed mb-3 max-w-2xl">
                Your landlord account is under review. Full access will be granted once verification is complete.
            </p>

            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-white/30 backdrop-blur-sm rounded-lg border border-white/40">
                <i class="fa-solid fa-hourglass-half text-gray-900 text-xs"></i>
                <span class="text-gray-900 text-sm font-medium">Typically takes 1-2 business days</span>
            </div>
        </div>
    </div>
</div>
<div class="bg-white rounded-2xl shadow-sm p-6">

    <h1 class="text-xl font-semibold mb-1">My Applications</h1>
    <p class="text-sm text-gray-600 mb-6">
        Track the status of your landlord verification.
    </p>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b">
                    <th class="text-center py-3 px-4 font-medium text-gray-500">Application</th>
                    <th class="text-center py-3 px-4 font-medium text-gray-500">Status</th>
                    <th class="text-center py-3 px-4 font-medium text-gray-500">Date Submitted</th>
                    <th class="text-center py-3 px-4 font-medium text-gray-500">Actions</th>
                </tr>
            </thead>

            <tbody>
                <tr class="border-b hover:bg-gray-50 transition">
                    
                    <!-- Application -->
                    <td class="py-4 px-4 align-middle text-center">
                        <div class="flex flex-col items-center">
                            <span class="font-medium text-gray-800">
                                APP-{{ $landlord->user_id }}-01
                            </span>
                        </div>
                    </td>

                    <!-- Status -->
                    <td class="py-4 px-4 align-middle text-center">
                        @if ($landlord->screening_status === 'pending')
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full
                                bg-yellow-100 text-yellow-700 text-xs font-semibold whitespace-nowrap">
                                <i class="fa-solid fa-hourglass-half"></i>
                                Under Review
                            </span>
                        @elseif ($landlord->screening_status === 'approved')
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full
                                bg-green-100 text-green-700 text-xs font-semibold whitespace-nowrap">
                                <i class="fa-solid fa-circle-check"></i>
                                Approved
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full
                                bg-red-100 text-red-700 text-xs font-semibold whitespace-nowrap">
                                <i class="fa-solid fa-circle-xmark"></i>
                                Rejected
                            </span>
                        @endif
                    </td>

                    <!-- Date -->
                    <td class="py-4 px-4 align-middle text-center text-gray-600">
                        {{ $landlord->screening_submitted_at->format('d M Y') }}
                    </td>

                    <!-- Actions -->
                    <td class="py-4 px-4 align-middle">
                        <div class="flex items-center justify-center gap-2">
                            
                            <!-- View Button -->
                            <button type="button"
                                    onclick="openViewModal()"
                                    class="w-8 h-8 flex items-center justify-center rounded-full
                                           hover:bg-blue-50 text-blue-600 transition-colors"
                                    title="View Application">
                                <i class="fa-solid fa-eye"></i>
                            </button>

                            <!-- Edit Button (only show if not approved) -->
                            @if ($landlord->screening_status !== 'approved')
                                <button type="button"
                                        onclick="openEditModal()"
                                        class="w-8 h-8 flex items-center justify-center rounded-full
                                               hover:bg-gray-100 text-gray-600 transition-colors"
                                        title="Edit Application">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                            @endif

                            <!-- Withdraw Button -->
                            <button type="button"
                                    onclick="openWithdrawModal()"
                                    class="w-8 h-8 flex items-center justify-center rounded-full
                                           hover:bg-red-50 text-red-600 transition-colors"
                                    title="Withdraw Application">
                                <i class="fa-solid fa-trash"></i>
                            </button>

                        </div>
                    </td>

                </tr>
            </tbody>
        </table>
    </div>

</div>

<!-- View Application Modal -->
<div id="viewModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800">View Application Details</h2>
                <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
        </div>
        
        <div class="p-6 space-y-4">
            <div>
                <label class="text-sm font-medium text-gray-600">Application ID</label>
                <p class="text-gray-800 mt-1">APP-{{ $landlord->user_id }}-01</p>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-600">Status</label>
                <p class="mt-1">
                    @if ($landlord->screening_status === 'pending')
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full
                            bg-yellow-100 text-yellow-700 text-xs font-semibold">
                            <i class="fa-solid fa-hourglass-half"></i>
                            Under Review
                        </span>
                    @elseif ($landlord->screening_status === 'approved')
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full
                            bg-green-100 text-green-700 text-xs font-semibold">
                            <i class="fa-solid fa-circle-check"></i>
                            Approved
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full
                            bg-red-100 text-red-700 text-xs font-semibold">
                            <i class="fa-solid fa-circle-xmark"></i>
                            Rejected
                        </span>
                    @endif
                </p>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-600">Date Submitted</label>
                <p class="text-gray-800 mt-1">{{ $landlord->screening_submitted_at->format('d M Y, h:i A') }}</p>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-600">Bank Name</label>
                <p class="text-gray-800 mt-1">{{ $landlord->bank_name ?? 'N/A' }}</p>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-600">Bank Account Number</label>
                <p class="text-gray-800 mt-1">{{ $landlord->bank_account_num ?? 'N/A' }}</p>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-600">Criminal Record</label>
                <p class="text-gray-800 mt-1">{{ $landlord->has_criminal_record ? 'Yes' : 'No' }}</p>
                @if($landlord->has_criminal_record && $landlord->criminal_record_details)
                    <p class="text-sm text-gray-600 mt-2">{{ $landlord->criminal_record_details }}</p>
                @endif
            </div>

            <div>
                <label class="text-sm font-medium text-gray-600">Documents</label>
                <div class="mt-2 space-y-2">
                    <a href="{{ Storage::url($landlord->ic_pic) }}" target="_blank" 
                       class="flex items-center gap-2 text-blue-600 hover:underline text-sm">
                        <i class="fa-solid fa-file"></i> IC/Passport Document
                    </a>
                    <a href="{{ asset('storage/' . $landlord->proof_of_address) }}" target="_blank" 
                       class="flex items-center gap-2 text-blue-600 hover:underline text-sm">
                        <i class="fa-solid fa-file"></i> Proof of Address
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6 border-t bg-gray-50 flex justify-end">
            <button onclick="closeViewModal()" 
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition">
                Close
            </button>
        </div>
    </div>
</div>

<!-- Edit Application Modal -->
<div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800">Edit Application</h2>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
        </div>
        
        <form method="POST" action="{{ route('landlord.verification.update') }}" enctype="multipart/form-data">
            @csrf
            <div class="p-6 space-y-4">
                
                <!-- Bank Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Bank Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="bank_name" 
                           value="{{ $landlord->bank_name }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Bank Account Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Bank Account Number <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="bank_account_num" 
                           value="{{ $landlord->bank_account_num }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- IC/Passport Document -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        IC/Passport Document
                    </label>
                    @if($landlord->ic_pic)
                        <div class="mb-2 text-sm text-gray-600">
                            Current: <a href="{{ Storage::url($landlord->ic_pic) }}" target="_blank" class="text-blue-600 hover:underline">View Document</a>
                        </div>
                    @endif
                    <input type="file" 
                           name="ic_pic" 
                           accept="image/*,.pdf"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Leave empty to keep current document</p>
                </div>

                <!-- Proof of Address -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Proof of Address
                    </label>
                    @if($landlord->proof_of_address)
                        <div class="mb-2 text-sm text-gray-600">
                            Current: <a href="{{ Storage::url($landlord->proof_of_address) }}" target="_blank" class="text-blue-600 hover:underline">View Document</a>
                        </div>
                    @endif
                    <input type="file" 
                           name="proof_of_address" 
                           accept="image/*,.pdf"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Leave empty to keep current document</p>
                </div>

                <!-- Criminal Record -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Do you have a criminal record? <span class="text-red-500">*</span>
                    </label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2">
                            <input type="radio" 
                                   name="has_criminal_record" 
                                   value="0" 
                                   {{ !$landlord->has_criminal_record ? 'checked' : '' }}
                                   onchange="toggleCriminalDetails()"
                                   class="text-blue-600">
                            <span class="text-sm">No</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="radio" 
                                   name="has_criminal_record" 
                                   value="1" 
                                   {{ $landlord->has_criminal_record ? 'checked' : '' }}
                                   onchange="toggleCriminalDetails()"
                                   class="text-blue-600">
                            <span class="text-sm">Yes</span>
                        </label>
                    </div>
                </div>

                <!-- Criminal Record Details -->
                <div id="criminalDetailsSection" class="{{ $landlord->has_criminal_record ? '' : 'hidden' }}">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Please provide details
                    </label>
                    <textarea name="criminal_record_details" 
                              rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ $landlord->criminal_record_details }}</textarea>
                </div>

                <!-- Agreement -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <label class="flex items-start gap-2 cursor-pointer">
                        <input type="checkbox" 
                               name="agreement_accepted" 
                               value="1"
                               required
                               class="mt-1 text-blue-600">
                        <span class="text-sm text-gray-700">
                            I confirm that all information provided is accurate and I agree to the terms and conditions.
                        </span>
                    </label>
                </div>
            </div>

            <div class="p-6 border-t bg-gray-50 flex justify-end gap-2">
                <button type="button"
                        onclick="closeEditModal()" 
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                    Update Application
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Withdraw Application Modal -->
<div id="withdrawModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6 border-b">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800">Withdraw Application</h2>
                <button onclick="closeWithdrawModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
        </div>
        
        <div class="p-6">
            <p class="text-gray-600 mb-4">
                Are you sure you want to withdraw your verification application?
            </p>
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex gap-2">
                    <i class="fa-solid fa-triangle-exclamation text-red-600 mt-0.5"></i>
                    <div class="text-sm text-red-800">
                        <strong>Warning:</strong> This action will permanently delete all your submitted information including documents, bank details, and verification status. You will need to start the verification process from scratch.
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6 border-t bg-gray-50 flex justify-end gap-2">
            <button onclick="closeWithdrawModal()" 
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition">
                Cancel
            </button>
            <form method="POST" action="{{ route('landlord.verification.withdraw') }}">
                @csrf
                <button type="submit" 
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                    Withdraw Application
                </button>
            </form>
        </div>
    </div>
</div>

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
// View Modal
function openViewModal() {
    document.getElementById('viewModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeViewModal() {
    document.getElementById('viewModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Edit Modal
function openEditModal() {
    document.getElementById('editModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Withdraw Modal
function openWithdrawModal() {
    document.getElementById('withdrawModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeWithdrawModal() {
    document.getElementById('withdrawModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Toggle Criminal Details
function toggleCriminalDetails() {
    const hasRecord = document.querySelector('input[name="has_criminal_record"]:checked').value === '1';
    const detailsSection = document.getElementById('criminalDetailsSection');
    
    if (hasRecord) {
        detailsSection.classList.remove('hidden');
    } else {
        detailsSection.classList.add('hidden');
    }
}

// Close modals on background click
document.getElementById('viewModal').addEventListener('click', function(e) {
    if (e.target === this) closeViewModal();
});

document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});

document.getElementById('withdrawModal').addEventListener('click', function(e) {
    if (e.target === this) closeWithdrawModal();
});

// Close modals on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeViewModal();
        closeEditModal();
        closeWithdrawModal();
    }
});
</script>

@endsection