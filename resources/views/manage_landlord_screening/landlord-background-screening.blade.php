@extends('layouts.landlord')

@section('title', 'Background Screening')

@section('content')

<!-- PAGE HEADER -->
<div class="mb-10">
    <h1 class="text-2xl sm:text-3xl font-semibold mb-3">
        Background Screening
    </h1>

    <p class="text-gray-600 w-full">
        Complete this background screening to finalize your landlord verification.
        All information is kept confidential and secure.
    </p>
</div>

<!-- PROGRESS BAR -->
<div class="bg-white border rounded-2xl shadow-sm p-6 sm:p-8 mb-10">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-gray-800">Screening Progress</h2>
        <p class="text-xl font-bold text-red-500">
            <span id="screeningProgress">{{ round($progress) }}</span>%
        </p>
    </div>
    
    <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden">
        <div id="screeningProgressBar" class="h-full bg-red-500 rounded-full transition-all duration-500"
             style="width: {{ round($progress) }}%">
        </div>
    </div>
</div>

<!-- SCREENING FORM -->
<div class="space-y-6">

    <!-- SECTION 1: Banking Information -->
    <div class="bg-white rounded-2xl shadow-md overflow-hidden">
        <button type="button" 
                onclick="toggleSection('banking')"
                class="w-full px-6 py-5 flex items-center justify-between hover:bg-gray-50 transition">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                    <i id="banking-icon" class="fa-solid fa-building-columns text-red-500 text-lg"></i>
                </div>
                <div class="text-left">
                    <h3 class="font-semibold text-lg text-gray-800">Banking Information</h3>
                    <p class="text-sm text-gray-600">Provide your bank account details</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span id="banking-status" class="text-sm text-yellow-600">Pending</span>
                <i id="banking-chevron" class="fa-solid fa-chevron-down text-gray-400 transition-transform"></i>
            </div>
        </button>

        <div id="banking-content" class="hidden px-6 pb-6">
            <div class="pt-4 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Bank Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="bank_name"
                           value="{{ old('bank_name', session('verification_screening.bank_name')) }}"
                           placeholder="e.g., Maybank, CIMB, Public Bank"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Bank Account Number <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="bank_account_num"
                           value="{{ old('bank_account_num', session('verification_screening.bank_account_num')) }}"
                           placeholder="Enter your account number"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">
                        This will be used for rental payments and security deposits
                    </p>
                </div>

                <button type="button"
                        onclick="saveBankingInfo()"
                        id="banking-save-btn"
                        class="w-full sm:w-auto bg-red-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-600 transition">
                    Save & Continue
                </button>
            </div>
        </div>
    </div>

    <!-- SECTION 2: Legal Background -->
    <div class="bg-white rounded-2xl shadow-md overflow-hidden" id="legal-section">
        <button type="button" 
                onclick="toggleSection('legal')"
                class="w-full px-6 py-5 flex items-center justify-between hover:bg-gray-50 transition"
                id="legal-section-btn"
                disabled>
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                    <i id="legal-icon" class="fa-solid fa-shield-halved text-gray-400 text-lg"></i>
                </div>
                <div class="text-left">
                    <h3 class="font-semibold text-lg text-gray-400">Legal Background</h3>
                    <p class="text-sm text-gray-400">Criminal record disclosure</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span id="legal-status" class="text-sm text-gray-400">Locked</span>
                <i id="legal-chevron" class="fa-solid fa-chevron-down text-gray-400 transition-transform"></i>
            </div>
        </button>

        <div id="legal-content" class="hidden px-6 pb-6">
            <div class="pt-4 space-y-4">
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex gap-3">
                        <i class="fa-solid fa-info-circle text-yellow-600 text-lg mt-0.5"></i>
                        <div class="text-sm text-yellow-800">
                            <p class="font-medium mb-1">Why we ask this:</p>
                            <p>This information helps us maintain a safe housing environment for students. All responses are confidential.</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="flex items-start gap-3 cursor-pointer group">
                        <input type="radio" 
                               name="has_criminal_record" 
                               value="0"
                               id="no_criminal_record"
                               class="mt-1 w-4 h-4 text-red-500 focus:ring-red-500"
                               onchange="handleCriminalRecordChange()">
                        <div>
                            <span class="font-medium text-gray-800 group-hover:text-red-600">
                                I do not have any criminal record
                            </span>
                            <p class="text-sm text-gray-600">I have never been convicted of any criminal offense</p>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 cursor-pointer group">
                        <input type="radio" 
                               name="has_criminal_record" 
                               value="1"
                               id="has_criminal_record_yes"
                               class="mt-1 w-4 h-4 text-red-500 focus:ring-red-500"
                               onchange="handleCriminalRecordChange()">
                        <div>
                            <span class="font-medium text-gray-800 group-hover:text-red-600">
                                I have a criminal record
                            </span>
                            <p class="text-sm text-gray-600">I will provide details below</p>
                        </div>
                    </label>
                </div>

                <div id="criminal-details-section" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Please provide details <span class="text-red-500">*</span>
                    </label>
                    <textarea id="criminal_record_details"
                              rows="4"
                              placeholder="Please explain the nature and date of the offense(s)"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent resize-none"></textarea>
                    <p class="text-xs text-gray-500 mt-1">
                        Honesty is appreciated. Having a record doesn't automatically disqualify you.
                    </p>
                </div>

                <button type="button"
                        onclick="saveLegalInfo()"
                        id="legal-save-btn"
                        disabled
                        class="w-full sm:w-auto bg-gray-300 text-gray-500 px-6 py-3 rounded-lg font-semibold cursor-not-allowed">
                    Save & Continue
                </button>
            </div>
        </div>
    </div>

    <!-- SECTION 3: Terms & Agreement -->
    <div class="bg-white rounded-2xl shadow-md overflow-hidden" id="agreement-section">
        <button type="button" 
                onclick="toggleSection('agreement')"
                class="w-full px-6 py-5 flex items-center justify-between hover:bg-gray-50 transition"
                id="agreement-section-btn"
                disabled>
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                    <i id="agreement-icon" class="fa-solid fa-file-contract text-gray-400 text-lg"></i>
                </div>
                <div class="text-left">
                    <h3 class="font-semibold text-lg text-gray-400">Terms & Agreement</h3>
                    <p class="text-sm text-gray-400">Review and accept our terms</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span id="agreement-status" class="text-sm text-gray-400">Locked</span>
                <i id="agreement-chevron" class="fa-solid fa-chevron-down text-gray-400 transition-transform"></i>
            </div>
        </button>

        <div id="agreement-content" class="hidden px-6 pb-6">
            <div class="pt-4 space-y-4">
                <div class="border border-gray-300 rounded-lg p-6 max-h-96 overflow-y-auto bg-gray-50">
                    <h4 class="font-semibold text-lg mb-4">Landlord Terms & Conditions</h4>
                    
                    <div class="space-y-4 text-sm text-gray-700">
                        <section>
                            <h5 class="font-semibold mb-2">1. Account Responsibilities</h5>
                            <p>As a landlord on OCHMS, you agree to maintain accurate property listings, respond promptly to student inquiries, and provide safe, habitable housing that meets all local regulations and building codes.</p>
                        </section>

                        <section>
                            <h5 class="font-semibold mb-2">2. Property Verification</h5>
                            <p>You certify that you are the legal owner or authorized representative of the properties you list, and that all documentation provided is accurate and current.</p>
                        </section>

                        <section>
                            <h5 class="font-semibold mb-2">3. Fair Housing Compliance</h5>
                            <p>You agree to comply with all fair housing laws and will not discriminate against tenants based on race, religion, gender, nationality, or any other protected characteristic.</p>
                        </section>

                        <section>
                            <h5 class="font-semibold mb-2">4. Financial Transactions</h5>
                            <p>All rental payments and deposits must be processed through approved channels. You agree to maintain transparent financial records and provide receipts for all transactions.</p>
                        </section>

                        <section>
                            <h5 class="font-semibold mb-2">5. Privacy & Data Protection</h5>
                            <p>You will protect tenant privacy and handle personal information in accordance with applicable data protection laws. Tenant data must not be shared with third parties without consent.</p>
                        </section>

                        <section>
                            <h5 class="font-semibold mb-2">6. Platform Usage</h5>
                            <p>You agree to use the platform responsibly, not engage in fraudulent activities, and maintain professional communication with all users.</p>
                        </section>

                        <section>
                            <h5 class="font-semibold mb-2">7. Termination</h5>
                            <p>OCHMS reserves the right to suspend or terminate accounts that violate these terms or engage in activities harmful to students or the platform community.</p>
                        </section>
                    </div>
                </div>

                <label class="flex items-start gap-3 cursor-pointer group">
                    <input type="checkbox" 
                           id="agreement_accepted"
                           class="mt-1 w-5 h-5 text-red-500 rounded focus:ring-red-500"
                           onchange="handleAgreementChange()">
                    <span class="text-sm text-gray-700">
                        I have read and agree to the <strong>Terms & Conditions</strong>. I understand that providing false information may result in account termination.
                    </span>
                </label>

                <button type="button"
                        onclick="saveAgreement()"
                        id="agreement-save-btn"
                        disabled
                        class="w-full sm:w-auto bg-gray-300 text-gray-500 px-6 py-3 rounded-lg font-semibold cursor-not-allowed">
                    Complete Screening
                </button>
            </div>
        </div>
    </div>

</div>

<!-- SUCCESS MESSAGE (hidden initially) -->
<div id="success-message" class="hidden mt-10 bg-green-50 border border-green-200 rounded-2xl p-8 text-center">
    <i class="fa-solid fa-circle-check text-green-500 text-5xl mb-4"></i>
    <h3 class="text-2xl font-semibold text-gray-800 mb-2">Verification Complete!</h3>
    <p class="text-gray-600 mb-6">
        Your verification has been submitted for admin review. 
        You'll be notified once your account is approved.
    </p>
    <a href="{{ route('landlord.verification') }}" 
       class="inline-block bg-red-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-red-600 transition">
        Go to Verification Process
    </a>
</div>

<script>
console.log('🎯 Screening page loaded');

// Track section completion
let sectionsCompleted = {
    banking: {{ session('verification_screening.bank_name') ? 'true' : 'false' }},
    legal: {{ session()->has('verification_screening.has_criminal_record') ? 'true' : 'false' }},
    agreement: {{ session('verification_screening.agreement_accepted') ? 'true' : 'false' }}
};


// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initial sections completed:', sectionsCompleted);
    
    // Open first incomplete section
    if (!sectionsCompleted.banking) {
        toggleSection('banking');
    } else if (!sectionsCompleted.legal) {
        unlockSection('legal');
        toggleSection('legal');
    } else if (!sectionsCompleted.agreement) {
        unlockSection('agreement');
        toggleSection('agreement');
    }
    
    // Update UI for completed sections
    updateSectionUI();
    updateProgress();
    
    // Check if has criminal record to show details field
    @if(session()->has('verification_screening.has_criminal_record'))
        document.getElementById(
            "{{ session('verification_screening.has_criminal_record') ? 'has_criminal_record_yes' : 'no_criminal_record' }}"
        ).checked = true;

        @if(session('verification_screening.has_criminal_record'))
            document.getElementById('criminal-details-section').classList.remove('hidden');
            document.getElementById('criminal_record_details').value =
                "{{ session('verification_screening.criminal_record_details') }}";
        @endif
    @endif

    
    // Check agreement if already accepted
    @if(session('verification_screening.agreement_accepted'))
        document.getElementById('agreement_accepted').checked = true;
    @endif

});

// Toggle section accordion
function toggleSection(section) {
    const content = document.getElementById(`${section}-content`);
    const chevron = document.getElementById(`${section}-chevron`);
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        chevron.style.transform = 'rotate(180deg)';
    } else {
        content.classList.add('hidden');
        chevron.style.transform = 'rotate(0deg)';
    }
}

// Unlock section
function unlockSection(section) {
    const btn = document.getElementById(`${section}-section-btn`);
    const icon = document.getElementById(`${section}-icon`);
    const status = document.getElementById(`${section}-status`);
    const heading = btn.querySelector('h3');
    const subtext = btn.querySelector('p');
    
    btn.disabled = false;
    btn.classList.remove('cursor-not-allowed');
    icon.classList.remove('text-gray-400');
    icon.classList.add('text-red-500');
    icon.parentElement.classList.remove('bg-gray-100');
    icon.parentElement.classList.add('bg-red-100');
    heading.classList.remove('text-gray-400');
    heading.classList.add('text-gray-800');
    subtext.classList.remove('text-gray-400');
    subtext.classList.add('text-gray-600');
    status.textContent = 'Pending';
    status.classList.remove('text-gray-400');
    status.classList.add('text-yellow-600');
}

// Mark section as complete
function markSectionComplete(section) {
    const icon = document.getElementById(`${section}-icon`);
    const status = document.getElementById(`${section}-status`);
    
    icon.className = 'fa-solid fa-circle-check text-green-500 text-lg';
    icon.parentElement.classList.remove('bg-red-100');
    icon.parentElement.classList.add('bg-green-100');
    status.textContent = 'Completed';
    status.classList.remove('text-yellow-600');
    status.classList.add('text-green-600');
    
    sectionsCompleted[section] = true;
}

// Update all section UI based on completion
function updateSectionUI() {
    if (sectionsCompleted.banking) {
        markSectionComplete('banking');
        unlockSection('legal');
    }
    if (sectionsCompleted.legal) {
        markSectionComplete('legal');
        unlockSection('agreement');
    }
    if (sectionsCompleted.agreement) {
        markSectionComplete('agreement');
    }
}

// Save banking info
async function saveBankingInfo() {
    const bankName = document.getElementById('bank_name').value.trim();
    const bankAccount = document.getElementById('bank_account_num').value.trim();
    
    if (!bankName || !bankAccount) {
        alert('Please fill in all banking information fields');
        return;
    }
    
    const btn = document.getElementById('banking-save-btn');
    btn.disabled = true;
    btn.textContent = 'Saving...';
    
    try {
        const response = await fetch("{{ route('landlord.verification.saveScreeningSession') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json",
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                bank_name: bankName,
                bank_account_num: bankAccount
            })
        });
        
        const result = await response.json();
        
        if (!response.ok) {
            throw new Error(result.message || "Save failed");
        }
        
        console.log('✅ Banking info saved');
        markSectionComplete('banking');
        unlockSection('legal');
        toggleSection('banking');
        toggleSection('legal');
        updateProgress();
        
    } catch (error) {
        console.error('❌ Error:', error);
        alert(error.message || "Failed to save. Please try again.");
        btn.disabled = false;
        btn.textContent = 'Save & Continue';
    }
}

// Handle criminal record radio change
function handleCriminalRecordChange() {
    const hasCriminalRecord = document.getElementById('has_criminal_record_yes').checked;
    const detailsSection = document.getElementById('criminal-details-section');
    const saveBtn = document.getElementById('legal-save-btn');
    
    if (hasCriminalRecord) {
        detailsSection.classList.remove('hidden');
    } else {
        detailsSection.classList.add('hidden');
        document.getElementById('criminal_record_details').value = '';
    }
    
    // Enable save button when selection is made
    saveBtn.disabled = false;
    saveBtn.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
    saveBtn.classList.add('bg-red-500', 'text-white', 'hover:bg-red-600', 'cursor-pointer');
}

// Save legal info
async function saveLegalInfo() {
    const hasCriminalRecord = document.getElementById('has_criminal_record_yes').checked;
    const criminalDetails = document.getElementById('criminal_record_details').value.trim();
    
    if (hasCriminalRecord && !criminalDetails) {
        alert('Please provide details about your criminal record');
        return;
    }
    
    const btn = document.getElementById('legal-save-btn');
    btn.disabled = true;
    btn.textContent = 'Saving...';
    
    try {
        const response = await fetch("{{ route('landlord.verification.saveScreeningSession') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json",
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                has_criminal_record: hasCriminalRecord,
                criminal_record_details: hasCriminalRecord ? criminalDetails : null
            })
        });
        
        const result = await response.json();
        
        if (!response.ok) {
            throw new Error(result.message || "Save failed");
        }
        
        console.log('✅ Legal info saved');
        markSectionComplete('legal');
        unlockSection('agreement');
        toggleSection('legal');
        toggleSection('agreement');
        updateProgress();
        
    } catch (error) {
        console.error('❌ Error:', error);
        alert(error.message || "Failed to save. Please try again.");
        btn.disabled = false;
        btn.textContent = 'Save & Continue';
    }
}

// Handle agreement checkbox change
function handleAgreementChange() {
    const isChecked = document.getElementById('agreement_accepted').checked;
    const saveBtn = document.getElementById('agreement-save-btn');
    
    if (isChecked) {
        saveBtn.disabled = false;
        saveBtn.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
        saveBtn.classList.add('bg-green-600', 'text-white', 'hover:bg-green-700', 'cursor-pointer');
    } else {
        saveBtn.disabled = true;
        saveBtn.classList.add('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
        saveBtn.classList.remove('bg-green-600', 'text-white', 'hover:bg-green-700', 'cursor-pointer');
    }
}

// Save agreement and complete screening
async function saveAgreement() {
    const isAccepted = document.getElementById('agreement_accepted').checked;
    
    if (!isAccepted) {
        alert('Please accept the terms and conditions to continue');
        return;
    }
    
    const btn = document.getElementById('agreement-save-btn');
    btn.disabled = true;
    btn.textContent = 'Completing...';
    
    try {
        const response = await fetch("{{ route('landlord.verification.saveScreeningSession') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json",
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                agreement_accepted: true
            })
        });
        
        const result = await response.json();
        
        if (!response.ok) {
            throw new Error(result.message || "Save failed");
        }
        
        console.log('✅ Screening completed!');
        markSectionComplete('agreement');
        updateProgress();
        
        // Show success message
        document.querySelector('.space-y-6').classList.add('hidden');
        document.getElementById('success-message').classList.remove('hidden');
        
    } catch (error) {
        console.error('❌ Error:', error);
        alert(error.message || "Failed to complete. Please try again.");
        btn.disabled = false;
        btn.textContent = 'Complete Screening';
    }
}

// Update progress bar
function updateProgress() {
    let completed = 0;
    let total = 3;  // IC + Proof + Banking + Legal + Agreement
    
    
    // Screening sections
    if (sectionsCompleted.banking) completed++;
    if (sectionsCompleted.legal) completed++;
    if (sectionsCompleted.agreement) completed++;
    
    const progress = Math.round((completed / total) * 100);
    
    document.getElementById('screeningProgress').textContent = progress;
    document.getElementById('screeningProgressBar').style.width = progress + '%';
    
    console.log('📊 Progress:', progress + '%', sectionsCompleted);
}
</script>

@endsection