@extends('layouts.landlord')

@section('title', 'Account Verification')

@section('content')

<!-- HERO -->
<section class="relative bg-gray-900 rounded-2xl overflow-hidden mb-10 h-[30vh] sm:h-[40vh] lg:h-[60vh] min-h-[300px]">
    
    <img src="{{ asset('images/landlord-pending-dashboard-hero.jpg') }}"
         class="absolute inset-0 w-full h-full object-cover opacity-65">

    <div class="relative z-10 h-full flex items-center justify-center text-center px-6">
        <div>
            <h1 class="text-white text-lg
           sm:text-lg
           md:text-3xl
           lg:text-4xl
           xl:text-5xl font-bold">
                Complete Your Landlord Verification
            </h1>

            <p class="text-gray-200 mt-4 max-w-2xl mx-auto
                        text-sm
                        sm:text-base
                        md:text-xl">

                Before you can list properties or receive bookings,
                we need to verify your identity and documents.
            </p>

            <a href="{{ route('landlord.verification') }}"
            class="inline-block mt-6
                    bg-white text-gray-900 font-semibold
                    rounded-lg hover:bg-gray-100 transition

                    px-5 py-2 text-sm
                    sm:px-6 sm:py-2.5 sm:text-base
                    md:px-8 md:py-3 md:text-lg
                    lg:px-10 lg:py-4 lg:text-xl">
                Start Verification
            </a>

        </div>
    </div>
</section>


<!-- STEP GUIDE -->
<h2 class="text-xl sm:text-base lg:text-2xl font-semibold mb-6">How to get verified</h2>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">

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
            lg:text-center max-w-md">
        Upload your IC or passport for identity verification.
        This helps us confirm your identity before approving
        your landlord account.
    </p>
</div>

<!-- STEP 2 -->
<div class="bg-white rounded-2xl shadow-md
            p-6 sm:p-8 lg:p-12
            min-h-[300px] lg:min-h-[500px]
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
        Property Ownership Proof
    </h3>

    <p class="text-gray-600
            text-sm sm:text-base lg:text-lg
            leading-relaxed
            lg:text-center max-w-md">
        Provide official documents that prove you own the property
        or are legally authorized to rent it to students.
    </p>
</div>

<!-- STEP 3 -->
<div class="bg-white rounded-2xl shadow-md
            p-6 sm:p-8 lg:p-12
            min-h-[300px] lg:min-h-[500px]
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
            lg:text-center max-w-md">
        A short background verification conducted by OCHMS
        administrators to ensure a safe and trusted
        housing environment.
    </p>
</div>



</div>

<!-- CTA SECTION -->
<section class="bg-gray-100 rounded-2xl p-10 text-center">
    <h2 class="text-2xl sm:text-base xl:text-3xl font-bold mb-4">
        Ready to get started?
    </h2>

    <p class="text-gray-600 mb-6">
        Verification usually takes 1-3 working days after submission.
    </p>

    <a href="{{ route('landlord.verification') }}"
       class="inline-block bg-red-500 hover:bg-red-600 text-white
              px-10 py-4 rounded-lg text-lg font-semibold">
        Go to Verification Page
    </a>
</section>

@endsection
