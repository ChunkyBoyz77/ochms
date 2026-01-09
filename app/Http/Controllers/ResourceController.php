<?php

// app/Http/Controllers/ResourceController.php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ResourceController extends Controller
{
    /* ================= ADMIN ================= */

    public function index()
    {
        $resources = Resource::latest()->get();
        return view('manage_umpsa_resources.admin-resource-list', compact('resources'));
    }

    public function create()
    {
        return view('manage_umpsa_resources.admin-create-resource');
    }

    public function store(Request $request)
    {

        
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'external_link' => 'nullable|url',
            'description' => 'required',
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

        return back()->with('error', 'Resource deleted.');
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

