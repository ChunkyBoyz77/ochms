@extends('layouts.admin')

@section('title', 'Landlord Verifications')

@section('content')

<h1 class="text-xl font-semibold mb-6">Landlord Verification Requests</h1>

<div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">

    <table class="w-full text-sm">
        <thead class="bg-purple-50">
            <tr class="text-gray-600">
                <th class="px-6 py-4 text-left">Landlord</th>
                <th class="px-6 py-4 text-left">Email</th>
                <th class="px-6 py-4 text-left">Submitted</th>
                <th class="px-6 py-4 text-center">Status</th>
                <th class="px-6 py-4 text-center">Action</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($landlords as $landlord)
                <tr class="border-b hover:bg-gray-50 transition">
                    
                    <td class="px-6 py-5">
                        @php
                            $name = $landlord->user->name;
                            $initials = collect(explode(' ', $name))
                                ->map(fn ($n) => strtoupper(substr($n, 0, 1)))
                                ->take(2)
                                ->implode('');
                        @endphp

                        <div class="flex items-center gap-4">
                            <!-- Initials Avatar -->
                            <div class="w-12 h-12 rounded-full bg-purple-100
                                        flex items-center justify-center
                                        text-purple-700 font-semibold text-lg">
                                {{ $initials }}
                            </div>

                            <!-- Name -->
                            <span class="font-medium text-gray-900">
                                {{ $landlord->user->name }}
                            </span>
                        </div>
                    </td>


                    <td class="px-6 py-5 text-gray-600">
                        {{ $landlord->user->email }}
                    </td>

                    <td class="px-6 py-5 text-gray-600">
                        {{ $landlord->screening_submitted_at->format('d M Y') }}
                    </td>

                    <td class="px-6 py-5 text-center">
                        <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700 font-semibold">
                            Pending
                        </span>
                    </td>

                    <td class="px-6 py-5 text-center">
                        <a href="{{ route('admin.verifications.show', $landlord->id) }}"
                           class="inline-flex items-center gap-2 text-purple-600 hover:underline">
                            <i class="fa-solid fa-eye"></i>
                            Review
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                        No pending verification requests
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>

@endsection
