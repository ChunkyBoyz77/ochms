@extends('layouts.admin')

@section('title', 'Verification Review')

@section('content')

@php
    $name = $landlord->user->name;
    $initials = collect(explode(' ', $name))
        ->map(fn ($n) => strtoupper(substr($n, 0, 1)))
        ->take(2)
        ->implode('');
@endphp

<h1 class="text-xl font-semibold mb-6">Verification Review</h1>

<div class="grid grid-cols-3 gap-6">

    <!-- LEFT PANEL -->
    <div class="col-span-2 bg-white rounded-xl shadow p-6 space-y-6">

        <!-- PROFILE HEADER -->
        <div class="flex items-center gap-5">
            @if ($landlord->user->profile_photo_path)
                <img src="{{ Storage::url($landlord->user->profile_photo_path) }}"
                     class="w-16 h-16 rounded-full object-cover border">
            @else
                <div class="w-16 h-16 rounded-full bg-purple-100
                            flex items-center justify-center
                            text-purple-700 text-xl font-semibold">
                    {{ $initials }}
                </div>
            @endif

            <div>
                <h2 class="text-lg font-semibold text-gray-800">
                    {{ $landlord->user->name }}
                </h2>
                <p class="text-sm text-gray-500">
                    {{ $landlord->user->email }}
                </p>

                <span class="inline-flex items-center gap-1 mt-1
                             px-3 py-1 rounded-full text-xs font-medium
                             bg-yellow-100 text-yellow-700">
                    <i class="fa-solid fa-hourglass-half"></i>
                    Pending Verification
                </span>
            </div>
        </div>

        <hr>

        <!-- BANK INFO -->
        <h3 class="font-semibold text-gray-800">Bank Information</h3>

        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Bank Name</p>
                <p class="font-medium">{{ $landlord->bank_name }}</p>
            </div>

            <div>
                <p class="text-gray-500">Account Number</p>
                <p class="font-medium">{{ $landlord->bank_account_num }}</p>
            </div>
        </div>

        <hr>

        <!-- DOCUMENTS -->
        <h3 class="font-semibold text-gray-800">Submitted Documents</h3>

        <div class="grid grid-cols-2 gap-4 text-sm">
            <a href="{{ Storage::url($landlord->ic_pic) }}" target="_blank"
               class="flex items-center gap-3 p-3 border rounded-lg
                      hover:bg-purple-50 transition">
                <i class="fa-solid fa-id-card text-purple-600 text-lg"></i>
                <span class="font-medium text-gray-700">Identity Document</span>
            </a>

            <a href="{{ Storage::url($landlord->supporting_document) }}" target="_blank"
               class="flex items-center gap-3 p-3 border rounded-lg
                      hover:bg-purple-50 transition">
                <i class="fa-solid fa-file-contract text-purple-600 text-lg"></i>
                <span class="font-medium text-gray-700">Verification Supporting Documents</span>
            </a>
        </div>

        <hr>

        <!-- CRIMINAL RECORD -->
        <h3 class="font-semibold text-gray-800">Criminal Record</h3>

        <p class="text-sm">
            {{ $landlord->has_criminal_record ? 'Yes' : 'No' }}
        </p>

        @if ($landlord->has_criminal_record)
            <p class="text-sm text-gray-600 mt-1">
                {{ $landlord->criminal_record_details }}
            </p>
        @endif

    </div>

    <!-- RIGHT PANEL -->
    <div class="bg-white rounded-xl shadow p-6 space-y-6">

        <h2 class="font-semibold text-gray-800">Admin Actions</h2>

        <!-- APPROVE -->
        <button
            onclick="openApproveModal('{{ route('admin.verifications.approve', $landlord->id) }}')"
            class= "w-full flex items-center justify-center gap-2
                       bg-green-600 hover:bg-green-700
                       text-white py-3 rounded-lg font-semibold">
                <i class="fa-solid fa-circle-check"></i>
            Approve
        </button>
        
        <!-- REJECT -->
        <button
            onclick="openRejectModal('{{ route('admin.verifications.reject', $landlord->id) }}')"
            class="w-full flex items-center justify-center gap-2
                       bg-red-600 hover:bg-red-700
                       text-white py-3 rounded-lg font-semibold">
                <i class="fa-solid fa-circle-xmark"></i>
            Reject
        </button>

        <hr>

        <!-- RESUBMISSION -->
        <form method="POST"
              action="{{ route('admin.verifications.resubmit', $landlord->id) }}"
              class="space-y-2">
            @csrf

            <label class="text-sm font-medium text-gray-700">
                Request Resubmission
            </label>

            <textarea name="reason"
                      class="w-full border rounded-lg p-3 text-sm"
                      placeholder="Explain what needs to be corrected"
                      required></textarea>

            <button
                class="w-full flex items-center justify-center gap-2
                       bg-yellow-500 hover:bg-yellow-600
                       text-white py-3 rounded-lg font-semibold">
                <i class="fa-solid fa-rotate-left"></i>
                Request Resubmission
            </button>
        </form>

    </div>

</div>

@endsection
