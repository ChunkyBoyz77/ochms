<x-guest-layout>

    <div class="min-h-screen flex items-center justify-center bg-cover bg-center"
         style="background-image: url('/images/ump-bg.jpg');">
        
        <div class="bg-white/95 rounded-xl shadow-2xl p-10 w-[500px] border-t-8 border-purple-600">
            
            <a href="{{ route('register.choose') }}" 
               class="text-gray-600 text-sm hover:text-black">← Back</a>

            <h2 class="text-2xl font-bold my-4 text-purple-600">Sign Up - JHEPA Admin</h2>

            <form method="POST" action="{{ route('register.admin.store') }}">
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

                <h3 class="text-lg font-bold text-purple-600 mt-6">Admin Details</h3>

                <div class="mt-4">
                    <input type="text" name="staff_id" placeholder="Staff ID" class="w-full p-3 rounded border">
                </div>

                <div class="mt-4">
                    <input type="text" name="department" placeholder="Department" class="w-full p-3 rounded border">
                </div>

                <button type="submit" 
                        class="w-full mt-6 bg-purple-600 text-white py-3 rounded-lg hover:bg-purple-700 transition">
                    Create Admin Account
                </button>

            </form>

        </div>
    </div>
</x-guest-layout>
