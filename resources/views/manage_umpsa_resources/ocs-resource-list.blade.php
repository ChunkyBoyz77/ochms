@extends('layouts.ocs')

@section('title', ' | UMPSA Resources')

@section('content')

<div class="px-10 mb-20 w-full mx-auto">

    {{-- PAGE HEADER --}}
    <div class="mb-8">
        <h1 class="text-2xl font-semibold mb-1">UMPSA Resources</h1>
        <p class="text-gray-500">
            Official resources, services, and important links provided by UMPSA
            to support your academic and campus life.
        </p>
    </div>

    @if($resources->isEmpty())
        <div class="bg-white rounded-xl shadow-sm p-10 text-center text-gray-600 border">
            <p class="text-lg font-medium">No resources available at the moment</p>
            <p class="text-sm text-gray-500 mt-2">Check back later for updates.</p>
        </div>
    @endif

    @foreach($resources as $resource)
    <div class="w-full bg-white rounded-xl shadow-sm overflow-hidden border mb-6 relative group hover:shadow-md transition">

        <a href="{{ route('resources.ocs.show', $resource) }}"
           class="absolute inset-0 z-10"></a>

        <div class="grid grid-cols-12">

            {{-- IMAGE --}}
            <div class="col-span-3">
                <img
                    src="{{ $resource->image_path
                            ? asset('storage/'.$resource->image_path)
                            : asset('images/resource-placeholder.jpg') }}"
                    class="w-full h-48 object-cover">
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
                <a href="{{ route('resources.ocs.show', $resource) }}"
                   class="bg-gray-900 hover:bg-gray-800
                          text-white px-4 py-2
                          rounded-lg text-sm font-medium">
                    View Details
                </a>
            </div>

        </div>
    </div>
    @endforeach

</div>

@endsection