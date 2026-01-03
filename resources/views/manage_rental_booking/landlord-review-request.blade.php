@extends('layouts.landlord')

@section('title', 'Booking Request Review')

@php
    $studentName = $listing->ocs->user->name;
    $initials = collect(explode(' ', $studentName))
        ->map(fn ($n) => strtoupper(substr($n, 0, 1)))
        ->take(2)
        ->implode('');
@endphp

@section('content')

<h1 class="text-xl font-semibold mb-6">
    Booking Request Review
</h1>

{{-- MAIN CARD --}}
<div class="bg-white rounded-xl shadow p-6 space-y-8">

    {{-- STUDENT HEADER --}}
    <div class="flex items-center gap-5">

        <div class="w-16 h-16 rounded-full bg-red-100
                    flex items-center justify-center
                    text-red-700 text-xl font-semibold">
            {{ $initials }}
        </div>

        <div>
            <h2 class="text-lg font-semibold text-gray-800">
                {{ $listing->ocs->user->name }}
            </h2>

            <p class="text-sm text-gray-500">
                {{ $listing->ocs->user->email }}
            </p>

            <span class="inline-flex items-center gap-1 mt-1
                         px-3 py-1 rounded-full text-xs font-medium
                         bg-red-100 text-red-700">
                <i class="fa-solid fa-paper-plane"></i>
                Booking Requested
            </span>
        </div>
    </div>

    <hr>

    {{-- STUDENT INFO --}}
    <div>
        <h3 class="font-semibold text-gray-800 mb-3">
            Student Information
        </h3>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">

            <div>
                <p class="text-gray-500">Matric ID</p>
                <p class="font-medium">
                    {{ $listing->ocs->matric_id }}
                </p>
            </div>

            <div>
                <p class="text-gray-500">Faculty</p>
                <p class="font-medium">
                    {{ $listing->ocs->faculty }}
                </p>
            </div>

            <div>
                <p class="text-gray-500">Course</p>
                <p class="font-medium">
                    {{ $listing->ocs->course }}
                </p>
            </div>

            <div>
                <p class="text-gray-500">Study Progress</p>
                <p class="font-medium">
                    Year {{ $listing->ocs->study_year }},
                    Sem {{ $listing->ocs->current_semester }}
                </p>
            </div>

        </div>
    </div>

    <hr>

    {{-- PROPERTY INFO --}}
    <div>
        <h3 class="font-semibold text-gray-800 mb-3">
            Property Requested
        </h3>

        <div class="flex items-center gap-4">

            <img
                src="{{ $listing->images->first()?->image_path
                    ? asset('storage/'.$listing->images->first()->image_path)
                    : asset('images/ocs-taman-placeholder.jpg') }}"
                class="w-24 h-24 rounded-xl object-cover border">

            <div>
                <p class="font-medium text-gray-900 text-lg">
                    {{ $listing->title }}
                </p>

                <p class="text-sm text-gray-500">
                    {{ $listing->address }}
                </p>

                <p class="text-sm mt-2">
                    <span class="font-semibold text-gray-800">
                        RM {{ number_format($listing->monthly_rent) }}
                    </span>
                    / month
                </p>
            </div>
        </div>
    </div>

    <hr>

    <!-- ADMIN ACTIONS -->
    <div class="flex items-center justify-end gap-4 pt-4">

        <button
            onclick="openRejectModal('{{ route('landlord.bookings.reject', $listing) }}')"
            class="flex items-center gap-2
                   bg-red-600 hover:bg-red-700
                   text-white px-6 py-3 rounded-lg font-semibold">
            <i class="fa-solid fa-circle-xmark"></i>
            Reject
        </button>

        <button
            onclick="openApproveModal('{{ route('landlord.bookings.approve', $listing) }}')"
            class="flex items-center gap-2
                   bg-green-600 hover:bg-green-700
                   text-white px-6 py-3 rounded-lg font-semibold">
            <i class="fa-solid fa-circle-check"></i>
            Approve
        </button>

    </div>

</div>

@endsection

