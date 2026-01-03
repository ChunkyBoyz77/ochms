@extends('layouts.ocs')

@section('title', 'My Booking Requests')

@section('content')

<h1 class="text-2xl font-semibold mb-6">
    My Booking Requests
</h1>

@if($listings->isEmpty())
    <div class="bg-white rounded-xl shadow-sm p-8 text-center text-gray-500">
        <i class="fa-solid fa-bed text-3xl mb-3 text-gray-300"></i>
        <p class="text-lg font-medium">No booking requests yet</p>
        <p class="text-sm mt-1">
            Start browsing properties and request a booking.
        </p>

        <a href="{{ route('ocs.listings.browse') }}"
           class="inline-block mt-4
                  bg-gray-900 text-white
                  px-6 py-3 rounded-xl
                  font-semibold hover:bg-gray-800 transition">
            Browse Listings
        </a>
    </div>
@else

<div class="space-y-6">

@foreach($listings as $listing)

<div class="bg-white rounded-2xl border shadow-sm p-6">

    {{-- HEADER --}}
    <div class="flex items-center gap-5 mb-4">

        {{-- IMAGE --}}
        <img
            src="{{ $listing->images->first()?->image_path
                ? asset('storage/'.$listing->images->first()->image_path)
                : asset('images/ocs-taman-placeholder.jpg') }}"
            class="w-24 h-24 rounded-xl object-cover border">

        {{-- INFO --}}
        <div class="flex-1">
            <h3 class="text-lg font-semibold text-gray-900">
                {{ $listing->title }}
            </h3>

            <p class="text-sm text-gray-500">
                <i class="fa-solid fa-location-dot mr-1"></i>
                {{ $listing->address }}
            </p>

            <p class="text-sm mt-1">
                <span class="font-semibold text-gray-800">
                    RM {{ number_format($listing->monthly_rent) }}
                </span>
                / month
            </p>
        </div>

        {{-- STATUS --}}
        <div>
            @if($listing->status === 'requested'
                && $listing->ocs_id === auth()->user()->ocs->id)

                <span class="inline-flex items-center gap-2
                            px-4 py-2 rounded-full text-sm
                            bg-yellow-100 text-yellow-700 font-semibold">
                    <i class="fa-solid fa-paper-plane"></i>
                    Booking Requested
                </span>

            @elseif($listing->status === 'occupied'
                && $listing->ocs_id === auth()->user()->ocs->id)

                <span class="inline-flex items-center gap-2
                            px-4 py-2 rounded-full text-sm
                            bg-green-100 text-green-700 font-semibold">
                    <i class="fa-solid fa-circle-check"></i>
                    Booking Approved
                </span>

            @elseif($listing->status === 'rejected'
                && $listing->ocs_id === auth()->user()->ocs->id)

                <span class="inline-flex items-center gap-2
                            px-4 py-2 rounded-full text-sm
                            bg-red-100 text-red-700 font-semibold">
                    <i class="fa-solid fa-circle-xmark"></i>
                    Booking Rejected
                </span>
            @endif
        </div>


    </div>

    {{-- FOOTER --}}
    <div class="flex justify-between items-center border-t pt-4">

        <div class="text-sm text-gray-500">
            Landlord:
            <span class="font-medium text-gray-700">
                {{ $listing->landlord->user->name }}
            </span>
        </div>

       @php
            $landlord = $listing->landlord->user;
            $student = $listing->ocs?->user ?? auth()->user();

            // Normalize phone number (Malaysia example)
            $phone = preg_replace('/[^0-9]/', '', $landlord->phone_number);

            $whatsappLink = "https://wa.me/{$phone}?text=" . urlencode(
                "Hi {$landlord->name}, I am {$student->name}. "
                . "My booking request for '{$listing->title}' has been approved."
            );
        @endphp

        {{-- ACTION BUTTON --}}
        @if(
            $listing->status === 'occupied'
            && $listing->ocs_id === auth()->user()->ocs->id
        )
            {{-- CURRENT TENANT --}}
            <a href="{{ $whatsappLink }}"
            target="_blank"
            class="inline-flex items-center gap-2
                    px-4 py-2
                    bg-green-600 text-white
                    rounded-lg text-sm font-semibold">
                <i class="fa-brands fa-whatsapp"></i>
                Contact Landlord on WhatsApp
            </a>

        @elseif(
            $listing->status === 'published'
            && $listing->last_occupied_ocs_id === auth()->user()->ocs->id
        )

            @php
                $alreadyReviewed = $listing->reviews
                    ->where('ocs_id', auth()->user()->ocs->id)
                    ->isNotEmpty();
            @endphp

            @if($alreadyReviewed)
                {{-- REVIEW ALREADY SUBMITTED --}}
                <span
                    class="inline-flex items-center gap-2
                        px-4 py-2
                        bg-gray-200 text-gray-500
                        rounded-lg text-sm font-semibold
                        cursor-not-allowed">
                    <i class="fa-solid fa-star"></i>
                    Review Submitted
                </span>
            @else
                {{-- CAN LEAVE REVIEW --}}
                <a href="{{ route('ocs.reviews.create', $listing) }}"
                class="inline-flex items-center gap-2
                        px-4 py-2
                        bg-amber-600 hover:bg-amber-700
                        text-white rounded-lg
                        text-sm font-semibold">
                    <i class="fa-solid fa-star"></i>
                    Leave Rating & Review
                </a>
            @endif

        @endif

    </div>

</div>
@endforeach

</div>

@endif

{{-- ================= MY REVIEWS ================= --}}
@if($reviews->isNotEmpty())

<h2 class="text-xl font-semibold mt-12 mb-6">
    My Reviews
</h2>

<div class="space-y-6">

@foreach($reviews as $review)

<div class="bg-white rounded-2xl border shadow-sm p-6">

    <div class="flex items-start gap-5">

        {{-- PROPERTY IMAGE --}}
        <img
            src="{{ $review->listing->images->first()?->image_path
                ? asset('storage/'.$review->listing->images->first()->image_path)
                : asset('images/ocs-taman-placeholder.jpg') }}"
            class="w-20 h-20 rounded-xl object-cover border">

        <div class="flex-1">

            {{-- TITLE --}}
            <h3 class="font-semibold text-gray-900">
                {{ $review->listing->title }}
            </h3>

            <p class="text-sm text-gray-500 mb-2">
                {{ $review->listing->address }}
            </p>

            {{-- STARS --}}
            <div class="flex gap-1 text-yellow-400 mb-2">
                @for($i = 1; $i <= 5; $i++)
                    <i class="fa-solid fa-star {{ $i <= $review->rating ? '' : 'text-gray-300' }}"></i>
                @endfor
            </div>

            {{-- REVIEW TEXT --}}
            @if($review->review)
                <p class="text-sm text-gray-700 leading-relaxed">
                    {{ $review->review }}
                </p>
            @else
                <p class="text-sm text-gray-400 italic">
                    No written review provided.
                </p>
            @endif

        </div>
    </div>

    {{-- ACTIONS --}}
    <div class="flex justify-end gap-3 border-t pt-4 mt-4">

        <a href="{{ route('ocs.reviews.edit', $review) }}"
           class="px-4 py-2 rounded-lg
                  border text-gray-700
                  hover:bg-gray-100 text-sm font-medium">
            <i class="fa-solid fa-pen mr-1"></i>
            Edit
        </a>

        <form method="POST"
              action="{{ route('ocs.reviews.destroy', $review) }}"
              onsubmit="return confirm('Delete this review?')">
            @csrf
            @method('DELETE')

            <button
                class="px-4 py-2 rounded-lg
                       bg-red-600 hover:bg-red-700
                       text-white text-sm font-medium">
                <i class="fa-solid fa-trash mr-1"></i>
                Delete
            </button>
        </form>

    </div>

</div>

@endforeach

</div>

@endif


@endsection
