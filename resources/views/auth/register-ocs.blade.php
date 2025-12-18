<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - OCS</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-cover bg-center flex items-center justify-center"
      style="background-image: url('{{ asset('images/umpsa-bg.jpg') }}');">

    <div class="bg-white/95 p-10 rounded-xl shadow-2xl w-[650px]">

        <a href="{{ route('register.choose') }}" 
           class="text-gray-600 text-sm hover:text-black">← Back</a>

        <h2 class="text-2xl font-bold my-4">Sign Up – OCS</h2>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul class="text-sm list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.ocs.store') }}">
            @csrf

            {{-- Hidden role --}}
            <input type="hidden" name="role" value="ocs">

            {{-- Full Name --}}
            <div class="mt-4">
                <label class="font-semibold">Full Name</label>
                <input type="text" name="name" required
                       value="{{ old('name') }}"
                       class="w-full p-3 rounded border mt-1">
            </div>

            {{-- Email --}}
            <div class="mt-4">
                <label class="font-semibold">Email</label>
                <input type="email" name="email" required
                       value="{{ old('email') }}"
                       class="w-full p-3 rounded border mt-1">
            </div>

            {{-- Matric ID --}}
            <div class="mt-4">
                <label class="font-semibold">Student ID</label>
                <input type="text" name="matric_id"
                       value="{{ old('matric_id') }}"
                       class="w-full p-3 rounded border mt-1">
            </div>

            {{-- Faculty & Course --}}
            <div class="grid grid-cols-2 gap-4 mt-4">
                <select name="faculty" class="p-3 rounded border">
                    <option value="">Faculty</option>
                    <option value="FOCS">FOCS</option>
                    <option value="FKEE">FKEE</option>
                    <option value="FTKEE">FTKEE</option>
                </select>

                <select name="course" class="p-3 rounded border">
                    <option value="">Course</option>
                    <option value="Diploma">Diploma</option>
                    <option value="Degree">Degree</option>
                </select>
            </div>

            {{-- Password --}}
            <div class="mt-4">
                <label class="font-semibold">Password</label>
                <input type="password" name="password" required
                       class="w-full p-3 rounded border mt-1">
            </div>

            {{-- Confirm Password --}}
            <div class="mt-4">
                <label class="font-semibold">Confirm Password</label>
                <input type="password" name="password_confirmation" required
                       class="w-full p-3 rounded border mt-1">
            </div>

            <button type="submit"
                    class="w-full mt-6 bg-green-600 text-white py-3 rounded-lg hover:bg-green-700">
                Create Account
            </button>

        </form>

    </div>

</body>
</html>
