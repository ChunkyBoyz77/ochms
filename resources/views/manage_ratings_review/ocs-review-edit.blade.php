@extends('layouts.ocs')

@section('title', 'Leave a Review')

@section('content')

<h1 class="text-2xl font-semibold mb-6">
    Leave a Review
</h1>

<div class="bg-white rounded-2xl border shadow-sm p-6 w-full">

    {{-- PROPERTY --}}
    <div class="flex items-center gap-4 mb-6">
        <img
            src="{{ $listing->images->first()?->image_path
                ? asset('storage/'.$listing->images->first()->image_path)
                : asset('images/ocs-taman-placeholder.jpg') }}"
            class="w-20 h-20 rounded-xl object-cover border">

        <div>
            <h2 class="font-semibold text-gray-900 text-2xl mb-2">
                {{ $listing->title }}
            </h2>
            <p class="text-base text-gray-500">
                {{ $listing->address }}
            </p>
        </div>
    </div>

    <form method="POST"
          action="{{ route('ocs.reviews.update', $review) }}"
          class="space-y-8">
        @csrf
        @method('PUT')

                {{-- STAY PERIOD --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="block text-lg font-medium text-gray-700 mb-2">
                    Stayed From
                </label>
                <input
                    type="date"
                    name="stay_from"
                    value="{{ old('stay_from', $review->stay_from ?? '') }}"
                    required
                    class="w-full border rounded-xl p-3
                        focus:ring-2 focus:ring-gray-900">
            </div>

            <div>
                <label class="block text-lg font-medium text-gray-700 mb-2">
                    Stayed Until
                </label>
                <input
                    type="date"
                    name="stay_until"
                    value="{{ old('stay_until', $review->stay_until ?? '') }}"
                    required
                    class="w-full border rounded-xl p-3
                        focus:ring-2 focus:ring-gray-900">
            </div>

        </div>
        
        {{-- RATING --}}
        <div>
            <label class="block text-lg font-medium text-gray-700 mb-3">
                Your Rating
            </label>

            <div class="flex items-center gap-3">
                <div id="starRating" class="flex gap-1 text-5xl cursor-pointer">
                    @for($i = 1; $i <= 5; $i++)
                        <i data-value="{{ $i }}"
                           class="fa-solid fa-star text-gray-300 transition mr-7"></i>
                    @endfor
                </div>

            </div>

            <input type="hidden" value="{{ old('rating', $review->rating) }}" name="rating" id="ratingInput" required>
        </div>

        {{-- REVIEW --}}
        <div>
            <label class="block text-lg font-medium text-gray-700 mb-2">
                Review (optional)
            </label>

            <textarea
                name="review"
                rows="5"
                class="w-full border rounded-xl p-4  focus:ring-2 focus:ring-gray-900"
                placeholder="Share your experience with this property...">{{ old('review', $review->review) }}</textarea>
        </div>

        {{-- ACTIONS --}}
        <div class="flex justify-end gap-3">
            <a href="{{ route('ocs.bookings.index') }}"
               class="px-5 py-2 rounded-lg border text-gray-700 hover:bg-gray-100">
                Cancel
            </a>

            <button
                class="px-6 py-2 rounded-lg
                       bg-gray-900 hover:bg-gray-800
                       text-white font-semibold">
                Update Review
            </button>
        </div>

    </form>
</div>

{{-- ⭐ STAR RATING SCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const stars = document.querySelectorAll('#starRating i');
    const ratingInput = document.getElementById('ratingInput');
    const ratingLabel = document.getElementById('ratingLabel');

    let selectedRating = parseInt(ratingInput.value) || 0;

    function paintStars(rating) {
        stars.forEach(star => {
            const value = parseInt(star.dataset.value);
            star.classList.toggle('text-yellow-400', value <= rating);
            star.classList.toggle('text-gray-300', value > rating);
        });
    }

    // ⭐ INITIAL PAINT
    paintStars(selectedRating);

    stars.forEach(star => {
        star.addEventListener('mouseenter', () => {
            paintStars(star.dataset.value);
        });

        star.addEventListener('mouseleave', () => {
            paintStars(selectedRating);
        });

        star.addEventListener('click', () => {
            selectedRating = parseInt(star.dataset.value);
            ratingInput.value = selectedRating;

            if (ratingLabel) {
                ratingLabel.textContent = `${selectedRating} / 5`;
            }

            paintStars(selectedRating);
        });
    });
});
</script>


@endsection
