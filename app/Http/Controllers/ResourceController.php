<?php

// app/Http/Controllers/ResourceController.php

namespace App\Http\Controllers;

use App\Models\Ocs;
use App\Models\Listing;
use App\Models\Landlord;
use App\Models\Resource;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ResourceController extends Controller
{
    /* ================= ADMIN ================= */
    public function adminDashboard()
    {
        return view('auth.admin-auth.admin-dashboard', [
            // Stats Overview
            'totalLandlords' => Landlord::count(),
            'totalStudents' => Ocs::count(),
            'totalProperties' => Listing::count(),
            'pendingVerifications' => Landlord::where('screening_status', 'pending')->count(),
            
            // Properties Breakdown
            'propertiesByType' => Listing::select('property_type', DB::raw('count(*) as count'))
                ->groupBy('property_type')
                ->get(),
            
            // System Stats
            'averageRating' => number_format(
                Review::avg('rating') ?? 0, 
                1
            ),
            'totalReviews' => Review::count(),
            'occupiedListings' => Listing::where('status', 'occupied')->count(),
            
            // Recent Activity
            'activities' => collect([
                // Recent verifications
                ...Landlord::where('screening_status', 'approved')
                    ->latest('updated_at')
                    ->take(3)
                    ->get()
                    ->map(fn($l) => [
                        'type' => 'verification',
                        'title' => 'Landlord Verified',
                        'subtitle' => $l->user->name ?? 'Unknown',
                        'created_at' => $l->updated_at,
                    ]),
                
                // Recent resources added
                ...Resource::latest()
                    ->take(2)
                    ->get()
                    ->map(fn($r) => [
                        'type' => 'resource',
                        'title' => 'Resource Added',
                        'subtitle' => $r->title,
                        'created_at' => $r->created_at,
                    ]),
            ])->sortByDesc('created_at')->take(5)->values(),
            
            
        ]);
    }

    public function adminMapView(Request $request)
    {
        $query = Listing::with(['landlord.user', 'images'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');

        // Filter by status
        if ($request->filled('status')) {
            $query->whereIn('status', $request->status);
        }

        // Filter by property type
        if ($request->filled('type')) {
            $query->whereIn('property_type', $request->type);
        }

        $listings = $query->get();

        return view('auth.admin-auth.admin-check-properties', compact('listings'));
    }

    public function index()
    {
        $resources = Resource::latest()->get();
        return view('manage_umpsa_resources.admin-resource-list', compact('resources'));
    }

    public function ocsIndex()
    {
        $resources = Resource::latest()->get();

        return view('manage_umpsa_resources.ocs-resource-list', compact('resources'));
    }

    public function create()
    {
        return view('manage_umpsa_resources.admin-create-resource');
    }

    public function store(Request $request)
    {

        
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'external_link' => 'nullable|url',
            'description' => ['required', 'string', 'not_regex:/^<p><br><\/p>$/'],
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('resources', 'public');
            
        }

        Resource::create([
            'title' => $request->title,
            'category' => $request->category,
            'external_link' => $request->external_link,
            'description' => $request->description,
            'image_path' => $imagePath,
            'admin_id' => Auth::user()->jhepa_admin->id, // adjust if relation differs
        ]);

        return redirect()->route('admin.resources.index')
            ->with('success', 'Resource added successfully.');
    }

    public function edit(Resource $resource)
    {
        return view('manage_umpsa_resources.admin-edit-resource', compact('resource'));
    }

    public function update(Request $request, Resource $resource)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'external_link' => 'nullable|string|max:255',
            'description' => 'required',
            'image' => 'nullable|image|max:4096',
        ]);

        if ($request->hasFile('image')) {
            if ($resource->image_path && Storage::disk('public')->exists($resource->image_path)) {
                Storage::disk('public')->delete($resource->image_path);
            }

            $resource->image_path = $request->file('image')->store('resources', 'public');
        }

        $resource->update([
            'title' => $request->title,
            'category' => $request->category,
            'external_link' => $request->external_link,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.resources.index')
            ->with('success', 'Resource updated successfully.');
    }

    public function destroy(Resource $resource)
    {
        if ($resource->image_path && Storage::disk('public')->exists($resource->image_path)) {
            Storage::disk('public')->delete($resource->image_path);
        }

        $resource->delete();

         return redirect()
        ->route('admin.resources.index')
        ->with('error', 'Resource deleted successfully.');
    }

    /* ================= OCS + VIEW ================= */

    public function landing()
    {
        $resources = Resource::latest()->take(4)->get(); // show 4 only
        return view('welcome', compact('resources'));
    }


    public function show(Resource $resource)
    {
        return view('manage_umpsa_resources.admin-resource-details', compact('resource'));
    }

    public function OcsShow(Resource $resource)
    {
        return view('manage_umpsa_resources.ocs-resource-details', compact('resource'));
    }
}

