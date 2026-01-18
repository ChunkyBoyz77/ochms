@extends('layouts.ocs')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-6 space-y-8">

    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">My Profile</h1>
            <p class="text-gray-600 mt-1">Manage your account information and preferences</p>
        </div>
    </div>


    <!-- Form -->
    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="profileForm">
        @csrf
        @method('PUT')

        <!-- Basic Information Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-6">
            <div class="bg-gray-900 px-6 py-4">
                <h3 class="font-semibold text-white text-lg">Basic Information</h3>
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
                               focus:ring-2 focus:ring-gray-900 focus:border-gray-900
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
                               focus:ring-2 focus:ring-gray-900 focus:border-gray-900
                               outline-none transition-all"
                        placeholder="your.email@student.umpsa.edu.my">
                    @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone Number -->
                <div>
                    <label for="phone_number" class="block text-sm font-semibold text-gray-700 mb-2">
                        Phone Number
                    </label>
                    <input 
                        type="text"
                        id="phone_number"
                        name="phone_number" 
                        value="{{ old('phone_number', $user->phone_number) }}" 
                        class="w-full border-2 border-gray-200 px-4 py-3 rounded-lg
                               focus:ring-2 focus:ring-gray-900 focus:border-gray-900
                               outline-none transition-all"
                        placeholder="+60 12-345 6789">
                    @error('phone_number')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
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
                               file:bg-gray-900 file:text-white hover:file:bg-gray-800
                               file:cursor-pointer">
                    <p class="mt-2 text-xs text-gray-500">Accepted formats: JPG, PNG, GIF (Max: 2MB)</p>
                    @error('profile_pic')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Academic Details Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-6">
            <div class="bg-gray-900 px-6 py-4">
                <h3 class="font-semibold text-white text-lg">Academic Details</h3>
            </div>
            
            <div class="p-6 space-y-5">
                <!-- Matric ID (Read-only) -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Matric ID
                    </label>
                    <div class="bg-gray-50 border-2 border-gray-200 px-4 py-3 rounded-lg flex items-center justify-between">
                        <span class="font-mono font-semibold text-gray-900">{{ $user->ocs->matric_id }}</span>
                        <span class="text-xs bg-gray-900 text-white px-3 py-1 rounded-full">Read Only</span>
                    </div>
                </div>

                <!-- Faculty Dropdown (Custom) -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Faculty <span class="text-red-500">*</span>
                    </label>
                    
                    <div 
                        x-data="{
                            open: false,
                            selected: '{{ old('faculty', $user->ocs->faculty) }}',
                            select(value) {
                                this.selected = value;
                                this.open = false;
                            }
                        }"
                        class="relative"
                    >
                        <!-- Hidden input for form submission -->
                        <input type="hidden" name="faculty" :value="selected">

                        <!-- Trigger Button -->
                        <button
                            type="button"
                            @click="open = !open"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg bg-white
                            text-left text-gray-700
                            flex items-center justify-between
                            focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-gray-900
                            transition">

                            <span
                                x-text="selected || 'Select your faculty'"
                                :class="selected ? 'text-gray-900' : 'text-gray-500'">
                            </span>

                            <i class="fa-solid fa-chevron-down text-xs text-gray-400 transition-transform"
                            :class="open ? 'rotate-180' : ''"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div
                            x-show="open"
                            @click.away="open = false"
                            x-transition
                            class="absolute z-50 mt-2 w-full max-h-64 overflow-y-auto
                                bg-white rounded-lg shadow-xl
                                border border-gray-200">

                            <button
                                type="button"
                                @click="select('Faculty of Computing')"
                                class="w-full text-left px-4 py-3 text-sm
                                    hover:bg-gray-900 hover:text-white transition">
                                Faculty of Computing
                            </button>

                            <button
                                type="button"
                                @click="select('Faculty of Electrical and Electronics Engineering Technology')"
                                class="w-full text-left px-4 py-3 text-sm
                                    hover:bg-gray-900 hover:text-white transition">
                                Faculty of Electrical and Electronics Engineering Technology
                            </button>

                            <button
                                type="button"
                                @click="select('Faculty of Mechanical and Automotive Engineering Technology')"
                                class="w-full text-left px-4 py-3 text-sm
                                    hover:bg-gray-900 hover:text-white transition">
                                Faculty of Mechanical and Automotive Engineering Technology
                            </button>

                            <button
                                type="button"
                                @click="select('Faculty of Manufacturing and Mechatronic Engineering Technology')"
                                class="w-full text-left px-4 py-3 text-sm
                                    hover:bg-gray-900 hover:text-white transition">
                                Faculty of Manufacturing and Mechatronic Engineering Technology
                            </button>
                        </div>
                    </div>
                    
                    @error('faculty')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                <!-- Course -->
                <div>
                    <label for="course" class="block text-sm font-semibold text-gray-700 mb-2">
                        Course / Program <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text"
                        id="course"
                        name="course" 
                        value="{{ old('course', $user->ocs->course) }}" 
                        required
                        class="w-full border-2 border-gray-200 px-4 py-3 rounded-lg
                               focus:ring-2 focus:ring-gray-900 focus:border-gray-900
                               outline-none transition-all"
                        placeholder="e.g., Bachelor of Computer Science">
                    @error('course')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Study Year & Semester -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="study_year" class="block text-sm font-semibold text-gray-700 mb-2">
                            Study Year <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number"
                            id="study_year"
                            name="study_year" 
                            value="{{ old('study_year', $user->ocs->study_year) }}" 
                            required
                            min="1"
                            max="6"
                            class="w-full border-2 border-gray-200 px-4 py-3 rounded-lg
                                   focus:ring-2 focus:ring-gray-900 focus:border-gray-900
                                   outline-none transition-all"
                            placeholder="e.g., 1">
                        @error('study_year')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="current_semester" class="block text-sm font-semibold text-gray-700 mb-2">
                            Current Semester <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number"
                            id="current_semester"
                            name="current_semester" 
                            value="{{ old('current_semester', $user->ocs->current_semester) }}" 
                            required
                            min="1"
                            max="8"
                            class="w-full border-2 border-gray-200 px-4 py-3 rounded-lg
                                   focus:ring-2 focus:ring-gray-900 focus:border-gray-900
                                   outline-none transition-all"
                            placeholder="e.g., 1">
                        @error('current_semester')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Change Password Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-6">
            <div class="bg-gray-900 px-6 py-4">
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
                                   focus:ring-2 focus:ring-gray-900 focus:border-gray-900
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
                    <label for="profile_password" class="block text-sm font-semibold text-gray-700 mb-2">
                        New Password
                    </label>
                    <div class="relative">
                        <input 
                            type="password"
                            id="profile_password"
                            name="password" 
                            class="w-full border-2 border-gray-200 px-4 py-3 rounded-lg pr-10
                                   focus:ring-2 focus:ring-gray-900 focus:border-gray-900
                                   outline-none transition-all"
                            placeholder="Enter new password"
                            oninput="checkProfilePasswordStrength(); checkProfilePasswordMatch();">
                        <button type="button"
                                onclick="togglePasswordVisibility('profile_password', this)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                    <p id="profilePasswordStatus" class="text-sm mt-5 text-gray-500 hidden"></p>
                    @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="profile_password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                        Confirm New Password
                    </label>
                    <div class="relative">
                        <input 
                            type="password"
                            id="profile_password_confirmation"
                            name="password_confirmation" 
                            class="w-full border-2 border-gray-200 px-4 py-3 rounded-lg pr-10
                                   focus:ring-2 focus:ring-gray-900 focus:border-gray-900
                                   outline-none transition-all"
                            placeholder="Confirm new password"
                            oninput="checkProfilePasswordMatch();">
                        <button type="button"
                                onclick="togglePasswordVisibility('profile_password_confirmation', this)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                    <p id="profileMatchMessage" class="text-sm mt-3"></p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('home') }}" 
               class="px-6 py-3 rounded-xl font-semibold text-gray-700 hover:bg-gray-100 transition">
                Cancel
            </a>
            <button 
                type="submit"
                id="saveBtn"
                class="bg-gray-900 hover:bg-gray-800 text-white px-8 py-3 rounded-xl font-semibold 
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

    // Password strength checker for profile page
    function checkProfilePasswordStrength() {
        const password = document.getElementById('profile_password');
        const status = document.getElementById('profilePasswordStatus');
        
        if (!password || !status) return;
        
        // If password is empty, hide status
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

    // Password match checker for profile page
    function checkProfilePasswordMatch() {
        const pwd = document.getElementById('profile_password');
        const confirm = document.getElementById('profile_password_confirmation');
        const msg = document.getElementById('profileMatchMessage');
        
        if (!pwd || !confirm || !msg) return;

        if (!confirm.value) {
            msg.textContent = '';
            return;
        }

        if (pwd.value === confirm.value) {
            msg.textContent = 'Passwords match';
            msg.className = 'text-sm text-green-600';
        } else {
            msg.textContent = 'Passwords do not match';
            msg.className = 'text-sm text-red-500';
        }
    }
</script>
@endsection