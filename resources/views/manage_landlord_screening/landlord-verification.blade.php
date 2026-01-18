@extends('layouts.landlord')

@section('title', 'Landlord Verification')

@section('content')

<!-- PAGE HEADER -->
<div class="mb-10">
    <h1 class="text-2xl sm:text-3xl font-semibold mb-3">
        Landlord Verification
    </h1>

    <p class="text-gray-600 w-full">
        To protect students and maintain a trusted housing platform,
        all landlords must complete verification before accessing full features.
    </p>
</div>

<!-- VERIFICATION PROGRESS -->
<div class="bg-white border rounded-2xl shadow-sm p-6 sm:p-8 mb-10">

    <!-- Header -->
    <div class="flex items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-lg sm:text-xl font-semibold text-gray-800">
                Verification Progress
            </h2>
            <p class="text-sm text-gray-600">
                Complete all steps to unlock full landlord features.
            </p>
        </div>

        <!-- Percentage -->
        <div class="text-right">
            <p class="text-sm text-gray-500">Progress</p>
            <p class="text-xl font-bold text-red-500">
                <span id="progressPercent">0</span>%
            </p>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden mb-6">
        <div id="progressBar" class="h-full bg-red-500 rounded-full transition-all duration-500"
             style="width: 0%">
        </div>
    </div>

    <!-- Step Indicators -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm mb-6">

        <!-- STEP 1 -->
        <div class="flex items-center gap-3">
            <i id="step1Icon" class="fa-solid fa-clock text-yellow-500 text-lg"></i>
            <div>
                <p class="font-medium text-gray-800">Identity Documents</p>
                <p id="step1Status" class="text-gray-500">Pending</p>
            </div>
        </div>

        <!-- STEP 2 -->
        <div class="flex items-center gap-3">
            <i id="step2Icon" class="fa-solid fa-clock text-yellow-500 text-lg"></i>
            <div>
                <p class="font-medium text-gray-800">Supporting Documents</p>
                <p id="step2Status" class="text-gray-500">Pending</p>
            </div>
        </div>

        <!-- STEP 3 -->
        <div class="flex items-center gap-3">
            <i class="fa-solid fa-lock text-gray-400 text-lg"></i>
            <div>
                <p class="font-medium text-gray-800">Background Screening</p>
                <p class="text-gray-500">Locked</p>
            </div>
        </div>

    </div>

    <!-- Submit Button (shows at 100%) -->
    <div id="submitButtonContainer"
     class="mt-6 {{ $progress === 100 ? '' : 'hidden' }}">

        <button type="button"
                onclick="submitForReview()"
                id="submitReviewBtn"
                class="w-full sm:w-auto bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700 transition flex items-center justify-center gap-2">
            <i class="fa-solid fa-paper-plane"></i>
            Submit for Review
        </button>
        <p class="text-sm text-gray-600 mt-2">
            All documents ready! Click to submit your verification for admin review.
        </p>
    </div>

</div>


<!-- STEPS GRID -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">

    <!-- STEP 1 -->
    <div class="bg-white rounded-2xl shadow-md
                p-6 sm:p-8 lg:p-12
                min-h-[300px] lg:min-h-[500px] xl:min-h-[600px]
                flex flex-col
                lg:items-center lg:justify-center
                transition hover:shadow-lg">

        <i class="fa-solid fa-id-card
                text-3xl sm:text-4xl lg:text-5xl
                text-red-500 mb-6 lg:mb-8"></i>

        <h3 class="font-semibold
                text-lg sm:text-xl lg:text-2xl
                mb-3 lg:mb-4
                lg:text-center">
            Submit Identity Documents
        </h3>

        <p class="text-gray-600
                text-sm sm:text-base lg:text-lg
                leading-relaxed
                lg:text-center max-w-md mb-6">
            Upload your IC or passport for identity verification.
            This helps us confirm your identity before approving
            your landlord account.
        </p>

        <div class="w-full flex flex-col items-center">
            <input type="file"
                name="ic_pic"
                id="icPicInput"
                accept=".pdf,.jpg,.jpeg"
                class="hidden">

            <button type="button"
                    id="icUploadBtn"
                    class="inline-block bg-red-500 text-white
                        px-6 py-3 rounded-lg font-semibold
                        hover:bg-red-600 transition">
                Upload ID
            </button>

            <div class="mt-3 text-center w-full">
                <p id="icFileName" class="text-sm text-gray-700 break-all min-h-[20px] cursor-pointer hover:underline"></p>
                <button type="button"
                        id="icDeleteBtn"
                        class="text-red-500 hover:text-red-700 mt-2 hidden">
                    <i class="fa-solid fa-trash text-sm"></i> Delete
                </button>
            </div>
        </div>

    </div>

    <!-- STEP 2 -->
    <div class="bg-white rounded-2xl shadow-md
                p-6 sm:p-8 lg:p-12
                min-h-[300px] lg:min-h-[500px] xl:min-h-[600px]
                flex flex-col
                lg:items-center lg:justify-center
                transition hover:shadow-lg">

        <i class="fa-solid fa-file-contract
                text-3xl sm:text-4xl lg:text-5xl
                text-red-500 mb-6 lg:mb-8"></i>

        <h3 class="font-semibold
                text-lg sm:text-xl lg:text-2xl
                mb-3 lg:mb-4
                lg:text-center">
            Verification Supporting Documents
        </h3>

        <p class="text-gray-600
                text-sm sm:text-base lg:text-lg
                leading-relaxed
                lg:text-center max-w-md mb-6">
            Provide supporting documents such as utility bills or proof of address.
        </p>

        <div class="w-full flex flex-col items-center">
            <input type="file"
                name="supporting_document"
                id="proofInput"
                accept=".pdf,.jpg,.jpeg"
                class="hidden">

            <button type="button"
                    id="proofUploadBtn"
                    class="inline-block bg-red-500 text-white
                        px-6 py-3 rounded-lg font-semibold
                        hover:bg-red-600 transition">
                Upload Documents
            </button>

            <div class="mt-3 text-center w-full">
                <p id="proofFileName" class="text-sm text-gray-700 break-all min-h-[20px] cursor-pointer hover:underline"></p>
                <button type="button"
                        id="proofDeleteBtn"
                        class="text-red-500 hover:text-red-700 mt-2 hidden">
                    <i class="fa-solid fa-trash text-sm"></i> Delete
                </button>
            </div>
        </div>

    </div>

    <!-- STEP 3 -->
    <div class="bg-white rounded-2xl shadow-md
                p-6 sm:p-8 lg:p-12
                min-h-[300px] lg:min-h-[500px] xl:min-h-[600px]
                flex flex-col
                lg:items-center lg:justify-center
                transition hover:shadow-lg">

        <i class="fa-solid fa-shield-halved
                text-3xl sm:text-4xl lg:text-5xl
                text-red-500 mb-6 lg:mb-8"></i>

        <h3 class="font-semibold
                text-lg sm:text-xl lg:text-2xl
                mb-3 lg:mb-4
                lg:text-center">
            Background Screening
        </h3>

        <p class="text-gray-600
                text-sm sm:text-base lg:text-lg
                leading-relaxed
                lg:text-center max-w-md mb-6">
            A short background verification conducted by OCHMS
            administrators to ensure a safe and trusted
            housing environment.
        </p>

        <a href="{{ route('landlord.verification.screening') }}"
        id="screeningBtn"
        class="inline-block bg-red-500 text-white
                px-6 py-3 rounded-lg font-semibold
                hover:bg-red-600 transition
                opacity-50 pointer-events-none">
            Start Screening
        </a>

    </div>

</div>

<!-- FOOTER NOTE -->
<div class="mt-12 text-sm text-gray-500 w-full italic">
    <p>
        Once all steps are completed, your submission will be reviewed by
        OCHMS administrators. You will be notified once verification is approved.
    </p>
</div>

<script>
window.sessionFiles = {
    ic_pic: @json(session('verification_files.ic_pic')),
    supporting_document: @json(session('verification_files.supporting_document')),
    screening_done: {{ session('verification_screening.completed') ? 'true' : 'false' }}
};
</script>

<script>
console.log('🔍 Verification JS loaded');

/* =========================
   STATE
   ========================= */

// Boolean-only state for progress
let uploadedFiles = {
    ic_pic: {{ session()->has('verification_files.ic_pic') ? 'true' : 'false' }},
    supporting_document: {{ session()->has('verification_files.supporting_document') ? 'true' : 'false' }}
};

// File data ONLY for preview
let previewFiles = {
    ic_pic: null,
    supporting_document: null
};

/* =========================
   DOM READY
   ========================= */
document.addEventListener('DOMContentLoaded', () => {
    bindUploadHandlers();
    bindDeleteHandlers();
    bindPreviewHandlers();

    // 🔁 Rehydrate filenames
    if (window.sessionFiles.ic_pic) {
        uploadedFiles.ic_pic = true;
        updateFileUI('ic_pic', window.sessionFiles.ic_pic.name, true);
    }

    if (window.sessionFiles.supporting_document) {
        uploadedFiles.supporting_document = true;
        updateFileUI('supporting_document', window.sessionFiles.supporting_document.name, true);
    }

    updateProgress();
});


/* =========================
   BINDERS
   ========================= */
function bindUploadHandlers() {
    document.getElementById('icUploadBtn')
        .addEventListener('click', () => document.getElementById('icPicInput').click());

    document.getElementById('proofUploadBtn')
        .addEventListener('click', () => document.getElementById('proofInput').click());

    document.getElementById('icPicInput')
        .addEventListener('change', e => handleFileUpload(e, 'ic_pic'));

    document.getElementById('proofInput')
        .addEventListener('change', e => handleFileUpload(e, 'supporting_document'));
}

function bindDeleteHandlers() {
    document.getElementById('icDeleteBtn')
        .addEventListener('click', () => deleteFile('ic_pic'));

    document.getElementById('proofDeleteBtn')
        .addEventListener('click', () => deleteFile('supporting_document'));
}

function bindPreviewHandlers() {
    document.getElementById('icFileName')
        .addEventListener('click', () => previewFile('ic_pic'));

    document.getElementById('proofFileName')
        .addEventListener('click', () => previewFile('supporting_document'));
}

/* =========================
   FILE UPLOAD
   ========================= */
async function handleFileUpload(event, field) {
    const file = event.target.files[0];
    if (!file) return;

    if (file.size > 10 * 1024 * 1024) {
        alert('File must be under 10MB');
        event.target.value = '';
        return;
    }

    if (!['application/pdf', 'image/jpeg', 'image/jpg'].includes(file.type)) {
        alert('Only PDF, JPG, JPEG allowed');
        event.target.value = '';
        return;
    }

    try {
        // Upload FIRST
        await uploadToServer(file, field);

        // Only after server success
        uploadedFiles[field] = true;

        updateFileUI(field, file.name, true);
        updateProgress();

    } catch (err) {
        alert(err.message || 'Upload failed');
        event.target.value = '';
    }
}

/* =========================
   SERVER UPLOAD
   ========================= */
async function uploadToServer(file, field) {
    const formData = new FormData();
    formData.append(field, file);

    const res = await fetch("{{ route('landlord.verification.storeSession') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json"
        },
        body: formData
    });

    const json = await res.json();

    if (!res.ok || !json.success) {
        throw new Error(json.message || 'Upload failed');
    }

    return true;
}


/* =========================
   DELETE FILE
   ========================= */
function deleteFile(field) {
    if (previewFiles[field]?.url) {
        URL.revokeObjectURL(previewFiles[field].url);
    }

    if (!confirm('Delete this document?')) return;

    uploadedFiles[field] = false;
    previewFiles[field] = null;

    updateFileUI(field, '', false);
    updateProgress();
    deleteFromServer(field);
}

async function deleteFromServer(field) {
    try {
        await fetch(`/landlord/verification/delete-session/${field}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            }
        });
    } catch (err) {
        console.error('❌ Delete error:', err);
    }
}

/* =========================
   UI HELPERS
   ========================= */
function updateFileUI(field, name, uploaded) {
    const nameEl = document.getElementById(field === 'ic_pic' ? 'icFileName' : 'proofFileName');
    const delBtn = document.getElementById(field === 'ic_pic' ? 'icDeleteBtn' : 'proofDeleteBtn');

    nameEl.textContent = uploaded ? name : '';
    nameEl.className = uploaded ? 'text-sm text-green-600 cursor-pointer' : 'text-sm text-gray-700';
    delBtn.classList.toggle('hidden', !uploaded);
}

/* =========================
   PROGRESS
   ========================= */
function updateProgress() {
    let completed = 0;

    if (uploadedFiles.ic_pic) completed++;
    if (uploadedFiles.supporting_document) completed++;
    if (window.sessionFiles.screening_done) completed++;

    const progress = Math.round((completed / 3) * 100);

    document.getElementById('progressPercent').textContent = progress;
    document.getElementById('progressBar').style.width = progress + '%';

    updateStepUI('step1', uploadedFiles.ic_pic);
    updateStepUI('step2', uploadedFiles.supporting_document);

    // Unlock screening
    const screeningBtn = document.getElementById('screeningBtn');
    screeningBtn.classList.toggle('opacity-50', completed < 2);
    screeningBtn.classList.toggle('pointer-events-none', completed < 2);

    // Show submit button at 100%
    document.getElementById('submitButtonContainer')
        ?.classList.toggle('hidden', progress !== 100);
}


function updateStepUI(step, done) {
    document.getElementById(step + 'Icon').className =
        done ? 'fa-solid fa-circle-check text-green-500 text-lg'
             : 'fa-solid fa-clock text-yellow-500 text-lg';

    document.getElementById(step + 'Status').textContent =
        done ? 'Completed' : 'Pending';
}

/* =========================
   PREVIEW
   ========================= */
function previewFile(field) {
    const ts = Date.now(); // cache buster
    const url = `{{ url('/landlord/verification/preview') }}/${field}?t=${ts}`;
    window.open(url, '_blank');
}




async function submitForReview() {
    if (!confirm('Submit verification for admin review?')) return;

    try {
        const res = await fetch("{{ route('landlord.verification.finalize') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            }
        });

        const json = await res.json();
        if (!res.ok) throw new Error(json.message || 'Submission failed');

        alert('Verification submitted successfully!');
        window.location.href = "{{ route('landlord.dashboard') }}";

    } catch (err) {
        alert(err.message);
    }
}

</script>


@endsection