@extends('layouts.admin')

@section('content')

<div class="max-w-5xl mx-auto space-y-10">

<!-- ================= HERO ================= -->
<div class="bg-white rounded-2xl shadow overflow-hidden">

    @if($resource->image_path)
        <div class="relative h-72">
            <img src="{{ asset('storage/'.$resource->image_path) }}"
                 class="absolute inset-0 w-full h-full object-cover">

            <div class="absolute inset-0 bg-black/40"></div>

            <a href="{{ route('admin.resources.index') }}"
               class="absolute top-5 left-5
                      bg-white/90 hover:bg-white
                      text-gray-800
                      px-4 py-2 rounded-full
                      text-sm font-semibold
                      flex items-center gap-2
                      shadow transition">
                <i class="fa-solid fa-arrow-left"></i>
                Back
            </a>
        </div>
    @else
        <div class="p-6 border-b">
            <a href="{{ route('admin.resources.index') }}"
               class="text-gray-600 hover:text-gray-900 flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Resources
            </a>
        </div>
    @endif


    <!-- ================= HEADER ================= -->
    <div class="p-8">

        @if($resource->category)
            <span
                class="inline-block mb-4
                       bg-purple-100 text-purple-700
                       px-4 py-1.5 rounded-full
                       text-xs font-semibold">
                {{ $resource->category }}
            </span>
        @endif

        <h1 class="text-3xl font-bold mb-3">
            {{ $resource->title }}
        </h1>

        <p class="text-sm text-gray-500">
            Published by UMPSA Administration
        </p>

    </div>
</div>


<!-- ================= CONTENT ================= -->
<div class="bg-white rounded-2xl shadow p-10">

    <div class="prose max-w-none">
        {!! $resource->description !!}
    </div>

</div>


<!-- ================= EXTERNAL LINK CTA ================= -->
@if($resource->external_link)

@php
    $host = parse_url($resource->external_link, PHP_URL_HOST);
@endphp

<div class="bg-purple-500
            text-white rounded-2xl shadow-lg p-8">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">

        <div>
            <h3 class="text-xl font-semibold mb-1">
                Additional Information Available
            </h3>

            <p class="text-purple-100 text-sm">
                This service is managed by an external organization.
                Click below to learn more or access their platform.
            </p>

            @if($host)
                <p class="text-xs text-purple-200 mt-2">
                    Source: {{ $host }}
                </p>
            @endif
        </div>

        <a href="{{ $resource->external_link }}"
           target="_blank"
           rel="noopener noreferrer"
           class="inline-flex items-center gap-3
                  bg-white text-purple-700
                  px-8 py-4 rounded-xl
                  font-semibold shadow
                  hover:bg-purple-50 transition">

            Open Website
            <i class="fa-solid fa-arrow-up-right-from-square"></i>
        </a>

    </div>

</div>

@endif



<!-- ================= ACTION BAR ================= -->
<div class="flex justify-end gap-4 pt-6">

    <a href="{{ route('admin.resources.edit', $resource) }}"
       class="px-6 py-3 rounded-xl
              bg-yellow-500 hover:bg-yellow-600
              text-white font-semibold shadow">
        Edit Resource
    </a>

    <form method="POST"
          action="{{ route('admin.resources.destroy', $resource) }}"
          onsubmit="return confirm('Delete this resource?')">
        @csrf
        @method('DELETE')

        <button
            class="px-6 py-3 rounded-xl
                   bg-red-600 hover:bg-red-700
                   text-white font-semibold shadow">
            Delete
        </button>
    </form>

</div>

</div>

@endsection
