@extends('layouts.admin')

@section('title', ' | UMPSA Resources')

@section('content')

<div class="px-10 mb-20 w-full mx-auto">

<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold">UMPSA Resources</h1>

    <a href="{{ route('admin.resources.create') }}"
       class="border border-gray-400 text-gray-700 px-5 py-2 rounded-lg
              transition hover:bg-purple-600 hover:text-white">
        + Add Resource
    </a>
</div>

@if($resources->isEmpty())
    <p class="text-gray-500">No resources added yet.</p>
@endif

@foreach($resources as $resource)
<div class="w-full bg-white rounded-xl shadow-sm overflow-hidden border mb-6 relative group">

<a href="{{ route('resources.show', $resource) }}"
   class="absolute inset-0 z-10"></a>

<div class="grid grid-cols-12">

{{-- IMAGE --}}
<div class="col-span-3">
    <img
        src="{{ $resource->image_path
                ? asset('storage/'.$resource->image_path)
                : asset('images/resource-placeholder.jpg') }}"
        class="w-full h-40 object-cover">
</div>

{{-- CONTENT --}}
<div class="col-span-6 px-6 py-4">

    <h2 class="font-semibold text-lg mb-1">
        {{ $resource->title }}
    </h2>

    @if($resource->category)
        <span class="text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded">
            {{ $resource->category }}
        </span>
    @endif

    <p class="text-sm text-gray-600 mt-2 line-clamp-2">
        {!! strip_tags($resource->description) !!}
    </p>

</div>

{{-- ACTIONS --}}
<div class="col-span-3 flex justify-end items-center pr-10 relative z-20">
    <div class="w-32 flex flex-col gap-3">

        <a href="{{ route('admin.resources.edit', $resource) }}"
           class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg text-center">
            Edit
        </a>

        <form method="POST" action="{{ route('admin.resources.destroy', $resource) }}">
            @csrf
            @method('DELETE')
            <button
                onclick="return confirm('Delete this resource?')"
                class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg w-full">
                Delete
            </button>
        </form>

    </div>
</div>

</div>
</div>
@endforeach

</div>
@endsection
