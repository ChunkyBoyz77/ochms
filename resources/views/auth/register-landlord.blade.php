<x-guest-layout>

    <div class="min-h-screen flex items-center justify-center bg-cover bg-center"
         style="background-image: url('/images/ump-bg.jpg');">

        <div class="bg-white/95 rounded-xl shadow-2xl p-10 w-[500px] border-t-8 border-red-500">
            
            <!-- Back Button -->
            <a href="{{ route('register.choose') }}" 
               class="text-gray-600 text-sm hover:text-black">← Back</a>

            <h2 class="text-2xl font-bold my-4 text-red-600">Sign Up - Landlord</h2>

            <form method="POST" action="{{ route('register.landlord.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Username -->
                <div class="mb-4">
                    <label class="font-semibold">Full Name</label>
                    <input type="text" name="name" class="w-full p-3 rounded border mt-1" required>
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label class="font-semibold">Email</label>
                    <input type="email" name="email" class="w-full p-3 rounded border mt-1" required>
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label class="font-semibold">Password</label>
                    <input type="password" name="password" class="w-full p-3 rounded border mt-1" required>
                </div>

                <div class="mb-4">
                    <label class="font-semibold">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="w-full p-3 rounded border mt-1" required>
                </div>

                <h3 class="text-lg font-bold text-red-600 mt-6">Personal Details</h3>

                <div class="mt-4">
                    <input type="text" name="ic_number" placeholder="IC Number" class="w-full p-3 rounded border">
                </div>

                <div class="mt-4">
                    <input type="text" name="phone" placeholder="Phone Number" class="w-full p-3 rounded border">
                </div>

                <div class="mt-4">
                    <input type="text" name="address" placeholder="Residential Address" class="w-full p-3 rounded border">
                </div>

                <h3 class="text-lg font-bold text-red-600 mt-6">Screening Documents</h3>

                <div class="mt-4">
                    <label class="font-semibold">Upload IC Image</label>
                    <input type="file" name="ic_image" class="w-full p-3 rounded border mt-1">
                </div>

                <div class="mt-4">
                    <label class="font-semibold">Upload Proof of Address</label>
                    <input type="file" name="proof_of_address" class="w-full p-3 rounded border mt-1">
                </div>

                <button type="submit" 
                        class="w-full mt-6 bg-red-600 text-white py-3 rounded-lg hover:bg-red-700 transition">
                    Create Landlord Account
                </button>

            </form>

        </div>
    </div>
</x-guest-layout>
