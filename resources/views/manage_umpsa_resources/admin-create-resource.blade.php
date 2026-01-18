@extends('layouts.admin')

@section('content')

<!-- Quill CSS -->
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">

<style>
.ql-editor.ql-blank::before {
    font-style: normal;
    font-size: 1rem;
    color: #9ca3af;
}
</style>

<!-- Quill JS -->
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>


<!-- ================= PAGE HEADER ================= -->
<div class="mb-10">
    <div class="flex items-center gap-4 mb-3">
        <a href="{{ route('admin.resources.index') }}"
           class="text-gray-600 hover:text-gray-900 text-xl">
            <i class="fa-solid fa-arrow-left"></i>
        </a>

        <h1 class="text-2xl sm:text-3xl font-semibold">
            Add UMPSA Resource
        </h1>
    </div>

    <p class="text-gray-600 max-w-4xl">
        Publish official student support services such as welfare assistance,
        emergency contacts, and academic facilities.
    </p>
</div>


@if ($errors->any())
<div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl mb-6">
    <ul class="text-sm list-disc pl-5">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif


<form id="resourceForm"
      method="POST"
      action="{{ route('admin.resources.store') }}"
      enctype="multipart/form-data"
      class="space-y-8">

@csrf

<!-- ================= BASIC INFO ================= -->
<div class="bg-white rounded-2xl shadow-md overflow-hidden">

    <div class="px-6 py-5 border-b">
        <h3 class="font-semibold text-lg flex items-center gap-2">
            <i class="fa-solid fa-circle-info text-purple-500"></i>
            Resource Information
        </h3>
    </div>

    <div class="p-6 space-y-6">

        <div>
            <label class="text-sm font-medium">Title</label>
            <input name="title"
                   placeholder="e.g. UMPSA Health Centre Hotline"
                   class="w-full px-4 py-3 rounded-lg border
                          focus:ring-2 focus:ring-purple-500 focus:border-transparent">
        </div>

        <div>
            <label class="text-sm font-medium">Category</label>
            <select name="category"
                class="w-full px-4 py-3 rounded-lg border
                       focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                <option value="">None</option>
                <option>Welfare</option>
                <option>Emergency</option>
                <option>Academic</option>
                <option>Transportation</option>
            </select>
        </div>

        <div>
            <label class="text-sm font-medium">External Link</label>
            <input name="external_link"
                   placeholder="https://official-umpsa-site.my"
                   class="w-full px-4 py-3 rounded-lg border
                          focus:ring-2 focus:ring-purple-500 focus:border-transparent">
        </div>

    </div>
</div>


<!-- ================= DESCRIPTION ================= -->
<div class="bg-white rounded-2xl shadow-md overflow-hidden">

    <div class="px-6 py-5 border-b">
        <h3 class="font-semibold text-lg flex items-center gap-2">
            <i class="fa-solid fa-pen-to-square text-purple-500"></i>
            Description
        </h3>
    </div>

    <div class="p-6">

        <div
            class="rounded-lg border border-gray-400 bg-white
                   focus-within:border-purple-500
                   focus-within:ring-2 focus-within:ring-purple-500
                   transition overflow-hidden">

            <div id="editor" class="min-h-[160px]"></div>
        </div>

        <input type="hidden" name="description" id="description">

    </div>
</div>


<!-- ================= IMAGE ================= -->
<div class="bg-white rounded-2xl shadow-md overflow-hidden">

    <div class="px-6 py-5 border-b">
        <h3 class="font-semibold text-lg flex items-center gap-2">
            <i class="fa-solid fa-image text-purple-500"></i>
            Cover Image (Optional)
        </h3>
    </div>

    <div class="p-6 space-y-4">

        <label
            class="block w-full cursor-pointer
                   border-2 border-dashed border-gray-300
                   rounded-2xl p-8 text-center
                   hover:border-purple-400 hover:bg-purple-50
                   transition">

            <div class="flex flex-col items-center gap-3">
                <div class="w-14 h-14 rounded-full bg-purple-100 flex items-center justify-center">
                    <i class="fa-solid fa-cloud-arrow-up text-purple-500 text-xl"></i>
                </div>

                <p class="font-medium text-gray-800">
                    Upload resource image
                </p>

                <p class="text-sm text-gray-500">
                    JPG or PNG (max 4MB)
                </p>
            </div>

            <input type="file" name="image" id="imageInput" class="hidden">
        </label>

        <div id="imagePreview" class="mt-4 hidden">
            <p class="text-sm text-gray-500 mb-2">Preview:</p>
            <img class="h-40 rounded-xl object-cover border">
        </div>

    </div>
</div>


<!-- ================= ACTION ================= -->
<div class="flex justify-end gap-4">
    <a href="{{ route('admin.resources.index') }}"
       class="px-6 py-3 border rounded-xl hover:bg-gray-50">
        Cancel
    </a>

    <button type="submit"
        class="bg-purple-600 hover:bg-purple-700 text-white
               px-10 py-3 rounded-xl font-semibold shadow-lg">
        Publish Resource
    </button>
</div>

</form>


<!-- ================= SCRIPTS ================= -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    const quill = new Quill('#editor', {
        theme: 'snow',
        placeholder: 'Describe what this service provides, who it is for, and how students can access it.',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline'],
                [{ list: 'ordered' }, { list: 'bullet' }],
                ['link'],
                ['clean']
            ]
        }
    });

    const form = document.getElementById('resourceForm');

    form.addEventListener('submit', function () {
        document.getElementById('description').value = quill.root.innerHTML;
    });


    // ===== IMAGE PREVIEW =====
    const input = document.getElementById('imageInput');
    const previewBox = document.getElementById('imagePreview');
    const previewImg = previewBox.querySelector('img');

    input.addEventListener('change', () => {
        if (!input.files || !input.files[0]) return;

        const url = URL.createObjectURL(input.files[0]);
        previewImg.src = url;
        previewBox.classList.remove('hidden');
    });

});


</script>

@endsection
