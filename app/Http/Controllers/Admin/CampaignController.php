<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CampaignController extends Controller
{
    /**
     * List campaign
     */
    public function index(): View
    {
        $campaigns = Campaign::latest()->paginate(10);
        return view('admin.campaigns.index', compact('campaigns'));
    }

    /**
     * Form create campaign
     */
    public function create(): View
    {
        return view('admin.campaigns.create');
    }

    /**
     * Store campaign
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'required|string',
            'target_amount' => 'required|numeric|min:1000',
            'deadline'      => 'required|date|after:today', // Validasi Deadline (Wajib & Harus hari esok dst)
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active'     => 'nullable|boolean',
        ]);

        // Slug unik
        $slug = Str::slug($validated['title']);
        $slugCount = Campaign::where('slug', 'LIKE', "{$slug}%")->count();
        if ($slugCount > 0) {
            $slug .= '-' . ($slugCount + 1);
        }

        // Upload image
        $imageUrl = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('campaigns', 'public');
            $imageUrl = Storage::url($imagePath); // Hasilnya: /storage/campaigns/namafile.jpg
        }

        Campaign::create([
            'title'          => $validated['title'],
            'slug'           => $slug,
            'description'    => $validated['description'],
            'target_amount'  => $validated['target_amount'],
            'current_amount' => 0,
            'backer_count'   => 0, // Default 0 donatur
            'deadline'       => $validated['deadline'], // Simpan Deadline
            'image_url'      => $imageUrl,
            'is_active'      => $request->boolean('is_active', true), // Default true jika null
        ]);

        return redirect()
            ->route('admin.campaigns.index')
            ->with('success', 'Kampanye berhasil dibuat!');
    }

    /**
     * Show campaign detail
     */
    public function show($id)
    {
        // Eager Load donasi yang statusnya 'paid' saja
        $campaign = Campaign::with(['donations' => function($q) {
            $q->where('payment_status', 'paid')->latest();
        }])->findOrFail($id);

        return view('admin.campaigns.show', compact('campaign'));
    }

    /**
     * Edit campaign
     */
    public function edit(int $id): View
    {
        $campaign = Campaign::findOrFail($id);
        return view('admin.campaigns.edit', compact('campaign'));
    }

    /**
     * Update campaign
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $campaign = Campaign::findOrFail($id);

        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'required|string',
            'target_amount' => 'required|numeric|min:1000',
            'deadline'      => 'required|date', // Validasi Deadline saat update
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active'     => 'nullable|boolean',
        ]);

        // Slug unik (kecuali dirinya sendiri)
        $slug = Str::slug($validated['title']);
        $slugCount = Campaign::where('slug', 'LIKE', "{$slug}%")
            ->where('id', '!=', $campaign->id)
            ->count();

        if ($slugCount > 0) {
            $slug .= '-' . ($slugCount + 1);
        }

        // Upload image baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($campaign->image_url) {
                // Konversi URL (/storage/campaigns/...) kembali ke Path (campaigns/...)
                $oldPath = str_replace('/storage/', '', $campaign->image_url);
                Storage::disk('public')->delete($oldPath);
            }

            $imagePath = $request->file('image')->store('campaigns', 'public');
            $campaign->image_url = Storage::url($imagePath);
        }

        $campaign->update([
            'title'         => $validated['title'],
            'slug'          => $slug,
            'description'   => $validated['description'],
            'target_amount' => $validated['target_amount'],
            'deadline'      => $validated['deadline'], // Update Deadline
            'is_active'     => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.campaigns.index')
            ->with('success', 'Kampanye berhasil diperbarui!');
    }

    /**
     * Delete campaign
     */
    public function destroy(int $id): RedirectResponse
    {
        $campaign = Campaign::findOrFail($id);

        if ($campaign->image_url) {
            $path = str_replace('/storage/', '', $campaign->image_url);
            Storage::disk('public')->delete($path);
        }

        $campaign->delete();

        return redirect()
            ->route('admin.campaigns.index')
            ->with('success', 'Kampanye berhasil dihapus!');
    }
}
