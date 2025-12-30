<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::latest()->paginate(10);
        return view('admin.campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        return view('admin.campaigns.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'target_amount' => 'required|numeric|min:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        $slug = Str::slug($validated['title']);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('campaigns', 'public');
            $validated['image_url'] = Storage::url($imagePath);
        }

        Campaign::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'description' => $validated['description'],
            'target_amount' => $validated['target_amount'],
            'current_amount' => 0,
            'image_url' => $validated['image_url'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.campaigns.index')
            ->with('success', 'Kampanye berhasil dibuat!');
    }

    public function show($id)
    {
        $campaign = Campaign::with('donations')->findOrFail($id);
        return view('admin.campaigns.show', compact('campaign'));
    }

    public function edit($id)
    {
        $campaign = Campaign::findOrFail($id);
        return view('admin.campaigns.edit', compact('campaign'));
    }

    public function update(Request $request, $id)
    {
        $campaign = Campaign::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'target_amount' => 'required|numeric|min:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        $slug = Str::slug($validated['title']);

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($campaign->image_url) {
                $oldImage = str_replace('/storage/', '', $campaign->image_url);
                Storage::disk('public')->delete($oldImage);
            }

            $imagePath = $request->file('image')->store('campaigns', 'public');
            $validated['image_url'] = Storage::url($imagePath);
        }

        $campaign->update([
            'title' => $validated['title'],
            'slug' => $slug,
            'description' => $validated['description'],
            'target_amount' => $validated['target_amount'],
            'image_url' => $validated['image_url'] ?? $campaign->image_url,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.campaigns.index')
            ->with('success', 'Kampanye berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $campaign = Campaign::findOrFail($id);

        // Hapus gambar jika ada
        if ($campaign->image_url) {
            $imagePath = str_replace('/storage/', '', $campaign->image_url);
            Storage::disk('public')->delete($imagePath);
        }

        $campaign->delete();

        return redirect()->route('admin.campaigns.index')
            ->with('success', 'Kampanye berhasil dihapus!');
    }
}
