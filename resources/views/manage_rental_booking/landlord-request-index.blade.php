@extends('layouts.landlord')

@section('title', 'Booking Requests')

@section('content')

<h1 class="text-xl font-semibold mb-6">
    Booking Requests
</h1>

<div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">

    <table class="w-full text-sm">
        <thead class="bg-red-50">
            <tr class="text-gray-600">
                <th class="px-6 py-4 text-left">Property</th>
                <th class="px-6 py-4 text-left">Student</th>
                <th class="px-6 py-4 text-left">Requested On</th>
                <th class="px-6 py-4 text-center">Status</th>
                <th class="px-6 py-4 text-center">Action</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($requests as $listing)
                <tr class="border-b hover:bg-gray-50 transition">

                    {{-- PROPERTY --}}
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-4">

                            <img
                                src="{{ $listing->images->first()?->image_path
                                    ? asset('storage/'.$listing->images->first()->image_path)
                                    : asset('images/ocs-taman-placeholder.jpg') }}"
                                class="w-14 h-14 rounded-lg object-cover border">

                            <div>
                                <p class="font-medium text-gray-900">
                                    {{ $listing->title }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $listing->address }}
                                </p>
                            </div>

                        </div>
                    </td>

                    {{-- STUDENT --}}
                    <td class="px-6 py-5 text-gray-700">
                        {{ $listing->ocs->user->name ?? '—' }}
                        <p class="text-xs text-gray-500">
                            {{ $listing->ocs->matric_id ?? '' }}
                        </p>
                    </td>

                    {{-- REQUESTED DATE --}}
                    <td class="px-6 py-5 text-gray-600">
                        {{ $listing->updated_at->format('d M Y') }}
                    </td>

                    {{-- STATUS --}}
                    <td class="px-6 py-5 text-center">
                        <span class="px-3 py-1 text-xs rounded-full
                                     bg-red-100 text-red-700 font-semibold">
                            Requested
                        </span>
                    </td>

                    {{-- ACTION --}}
                    <td class="px-6 py-5 text-center">
                        <a href="{{ route('landlord.bookings.show', $listing) }}"
                           class="inline-flex items-center gap-2
                                  text-red-600 hover:underline">
                            <i class="fa-solid fa-eye"></i>
                            Review
                        </a>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="5"
                        class="px-6 py-8 text-center text-gray-500">
                        No booking requests yet
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>

@endsection
