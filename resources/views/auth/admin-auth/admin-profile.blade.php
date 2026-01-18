@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-6 space-y-8">

    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Admin Profile</h1>
            <p class="text-gray-600 mt-1">Manage your administrative account</p>
        </div>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('profile.update') }}" id="profileForm" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Account Information Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-6">
            <div class="bg-purple-500 px-6 py-4">
                <h3 class="font-semibold text-white text-lg">Account Information</h3>
            </div>
            
            <div class="p-6 space-y-5">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text"
                        id="name"
                        name="name" 
                        value="{{ old('name', $user->name) }}" 
                        required
                        class="w-full border-2 border-gray-200 px-4 py-3 rounded-lg
                               focus:ring-2 focus:ring-purple-500 focus:border-purple-500
                               outline-none transition-all"
                        placeholder="Enter your full name">
                    @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="email"
                        id="email"
                        name="email" 
                        value="{{ old('email', $user->email) }}" 
                        required
                        class="w-full border-2 border-gray-200 px-4 py-3 rounded-lg
                               focus:ring-2 focus:ring-purple-500 focus:border-purple-500
                               outline-none transition-all"
                        placeholder="admin@jhepa.edu.my">
                    @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Staff ID (Read-only) -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Staff ID
                    </label>
                    <div class="bg-gray-50 border-2 border-gray-200 px-4 py-3 rounded-lg flex items-center justify-between">
                        <span class="font-mono font-semibold text-gray-900">{{ $user->jhepa_admin->staff_id }}</span>
                        <span class="text-xs bg-purple-500 text-white px-3 py-1 rounded-full">Read Only</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">
                        <i class="fa-solid fa-circle-info mr-1"></i>
                        Staff ID and role permissions are managed by the system
                    </p>
                </div>

                <!-- Profile Picture -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Profile Picture
                    </label>
                    
                    @if($user->profile_pic)
                    <div class="mb-3 flex items-center gap-4">
                        <img src="{{ asset('storage/'.$user->profile_pic) }}" 
                             class="w-20 h-20 rounded-full object-cover border-2 border-gray-200"
                             alt="Current profile picture">
                        <span class="text-sm text-gray-600">Current profile picture</span>
                    </div>
                    @endif
                    
                    <input 
                        type="file" 
                        name="profile_pic"
                        accept="image/*"
                        class="w-full border-2 border-gray-200 px-4 py-3 rounded-lg
                               focus:ring-2 focus:ring-gray-900 focus:border-gray-900
                               outline-none transition-all file:mr-4 file:py-2 file:px-4
                               file:rounded-lg file:border-0 file:text-sm file:font-semibold
                               file:bg-purple-500 file:text-white hover:file:bg-purple-800
                               file:cursor-pointer">
                    <p class="mt-2 text-xs text-gray-500">Accepted formats: JPG, PNG, GIF (Max: 2MB)</p>
                    @error('profile_pic')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Change Password Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-6">
            <div class="bg-purple-500 px-6 py-4">
                <h3 class="font-semibold text-white text-lg">Change Password</h3>
            </div>
            
            <div class="p-6 space-y-5">
                <p class="text-sm text-gray-600 bg-blue-50 border border-blue-200 px-4 py-3 rounded-lg">
                    <i class="fa-solid fa-circle-info text-blue-600 mr-2"></i>
                    Leave blank if you don't want to change your password
                </p>

                <!-- Current Password -->
                <div>
                    <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-2">
                        Current Password
                    </label>
                    <div class="relative">
                        <input 
                            type="password"
                            id="current_password"
                            name="current_password" 
                            class="w-full border-2 border-gray-200 px-4 py-3 rounded-lg pr-10
                                   focus:ring-2 focus:ring-purple-500 focus:border-purple-500
                                   outline-none transition-all"
                            placeholder="Enter current password">
                        <button type="button"
                                onclick="togglePasswordVisibility('current_password', this)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                    @error('current_password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        New Password
                    </label>
                    <div class="relative">
                        <input 
                            type="password"
                            id="password"
                            name="password" 
                            class="w-full border-2 border-gray-200 px-4 py-3 rounded-lg pr-10
                                   focus:ring-2 focus:ring-purple-500 focus:border-purple-500
                                   outline-none transition-all"
                            placeholder="Enter new password"
                            oninput="checkPasswordStrength(); checkPasswordMatch();">
                        <button type="button"
                                onclick="togglePasswordVisibility('password', this)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                    <p id="passwordStatus" class="text-sm mt-3 text-gray-500 hidden"></p>
                    @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                        Confirm New Password
                    </label>
                    <div class="relative">
                        <input 
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation" 
                            class="w-full border-2 border-gray-200 px-4 py-3 rounded-lg pr-10
                                   focus:ring-2 focus:ring-purple-500 focus:border-purple-500
                                   outline-none transition-all"
                            placeholder="Confirm new password"
                            oninput="checkPasswordMatch();">
                        <button type="button"
                                onclick="togglePasswordVisibility('password_confirmation', this)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                    <p id="matchMessage" class="text-sm mt-3"></p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('admin.dashboard') }}" 
               class="px-6 py-3 rounded-xl font-semibold text-gray-700 hover:bg-gray-100 transition">
                Cancel
            </a>
            <button 
                type="submit"
                id="saveBtn"
                class="bg-purple-500 hover:bg-purple-600 text-white px-8 py-3 rounded-xl font-semibold 
                       shadow-lg hover:shadow-xl transition-all duration-300
                       disabled:opacity-70 disabled:cursor-not-allowed
                       flex items-center gap-2">
                <i class="fa-solid fa-floppy-disk"></i>
                <span id="btnText">Save Changes</span>
                <span id="btnLoading" class="hidden">
                    <i class="fa-solid fa-circle-notch animate-spin"></i>
                    Saving...
                </span>
            </button>
        </div>
    </form>

</div>

<style>
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    .animate-spin {
        animation: spin 1s linear infinite;
    }
</style>

<script>
    // Form submission loading state
    document.getElementById('profileForm').addEventListener('submit', function() {
        const btn = document.getElementById('saveBtn');
        const btnText = document.getElementById('btnText');
        const btnLoading = document.getElementById('btnLoading');
        
        btn.disabled = true;
        btnText.classList.add('hidden');
        btnLoading.classList.remove('hidden');
    });

    // Toggle password visibility
    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    // Password strength checker
    function checkPasswordStrength() {
        const password = document.getElementById('password');
        const status = document.getElementById('passwordStatus');
        
        if (!password || !status) return;
        
        if (!password.value) {
            status.classList.add('hidden');
            return;
        }

        const rules = [
            password.value.length >= 8,
            /[A-Z]/.test(password.value),
            /\d/.test(password.value)
        ];

        status.classList.remove('hidden');

        if (rules.every(Boolean)) {
            status.textContent = 'All requirements fulfilled';
            status.className = 'text-sm mt-3 text-green-600';
        } else {
            status.textContent = 'Password must be at least 8 characters, include an uppercase letter and a number';
            status.className = 'text-sm mt-3 text-gray-500';
        }
    }

    // Password match checker
    function checkPasswordMatch() {
        const pwd = document.getElementById('password');
        const confirm = document.getElementById('password_confirmation');
        const msg = document.getElementById('matchMessage');
        
        if (!pwd || !confirm || !msg) return;

        if (!confirm.value) {
            msg.textContent = '';
            return;
        }

        if (pwd.value === confirm.value) {
            msg.textContent = 'Passwords match';
            msg.className = 'text-sm mt-3 text-green-600';
        } else {
            msg.textContent = 'Passwords do not match';
            msg.className = 'text-sm mt-3 text-red-500';
        }
    }
</script>
@endsection