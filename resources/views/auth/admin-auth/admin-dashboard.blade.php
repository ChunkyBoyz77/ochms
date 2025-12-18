<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | OCHMS</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-100">

    <!-- TOP BAR -->
    <div class="bg-gray-900 text-white px-6 py-4 flex justify-between items-center">
        <h1 class="text-xl font-semibold">
            OCHMS — JHEPA Admin
        </h1>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="text-sm hover:underline">
                Logout
            </button>
        </form>
    </div>

    <!-- MAIN CONTENT -->
    <div class="p-8">

        <div class="bg-white rounded-xl shadow p-6 max-w-xl">
            <h2 class="text-2xl font-bold mb-4">
                Welcome, {{ auth()->user()->name }}
            </h2>

            <p class="text-gray-600 mb-6">
                You are logged in as a <strong>JHEPA Administrator</strong>.
            </p>

            <div class="space-y-2 text-sm">
                <p>
                    <span class="font-semibold">Role:</span>
                    {{ auth()->user()->role }}
                </p>

                <p>
                    <span class="font-semibold">Staff ID:</span>
                    {{ auth()->user()->adminProfile->staff_id ?? 'N/A' }}
                </p>

                <p>
                    <span class="font-semibold">Email (system):</span>
                    {{ auth()->user()->email }}
                </p>
            </div>

            <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                ✅ Admin authentication successful.
            </div>
        </div>

    </div>

</body>
</html>
