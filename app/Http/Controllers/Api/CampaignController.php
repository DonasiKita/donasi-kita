<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\CampaignResource;
use App\Http\Resources\CampaignCollection;

class CampaignController extends Controller
{
    /**
     * Get all campaigns with pagination
     *
     * @queryParam page integer Page number. Example: 1
     * @queryParam limit integer Items per page. Example: 10
     * @queryParam status string Filter by status (active, completed, expired). Example: active
     * @queryParam search string Search by title or description.
     * @queryParam sort string Sort by (latest, popular, ending_soon). Example: latest
     *
     * @response 200 {
     *   "data": [...],
     *   "links": {...},
     *   "meta": {...}
     * }
     */
    public function index(Request $request)
    {
        try {
            // Validasi query parameters
            $validated = $request->validate([
                'page' => 'integer|min:1',
                'limit' => 'integer|min:1|max:50',
                'status' => 'in:active,completed,expired',
                'search' => 'string|max:100',
                'sort' => 'in:latest,popular,ending_soon,most_funded',
            ]);

            // Query builder
            $query = Campaign::with(['user:id,name,email']);

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            } else {
                $query->where('status', 'active'); // Default active
            }

            // Search
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Sorting
            switch ($request->get('sort', 'latest')) {
                case 'popular':
                    $query->orderByDesc('current_amount');
                    break;
                case 'ending_soon':
                    $query->orderBy('end_date');
                    break;
                case 'most_funded':
                    $query->orderByRaw('(current_amount / target_amount) DESC');
                    break;
                case 'latest':
                default:
                    $query->orderByDesc('created_at');
                    break;
            }

            // Pagination
            $limit = $request->get('limit', 10);
            $campaigns = $query->paginate($limit);

            // Return dengan CampaignCollection
            return new CampaignCollection($campaigns);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve campaigns',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single campaign by ID
     *
     * @urlParam id integer required Campaign ID. Example: 1
     *
     * @response 200 {
     *   "data": {...}
     * }
     * @response 404 {
     *   "message": "Campaign not found"
     * }
     */
    public function show($id)
    {
        try {
            $campaign = Campaign::with(['user:id,name,email', 'donations'])
                ->findOrFail($id);

            return new CampaignResource($campaign);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Campaign not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve campaign',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get featured campaigns (high progress, trending, etc.)
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Featured campaigns retrieved",
     *   "data": [...]
     * }
     */
    public function featured(): JsonResponse
    {
        try {
            // Get campaigns with high progress (>70%)
            $featuredCampaigns = Campaign::where('status', 'active')
                ->whereRaw('(current_amount / target_amount) >= 0.7')
                ->orderByRaw('(current_amount / target_amount) DESC')
                ->limit(6)
                ->get();

            // If not enough, add latest campaigns
            if ($featuredCampaigns->count() < 6) {
                $needed = 6 - $featuredCampaigns->count();
                $additional = Campaign::where('status', 'active')
                    ->whereNotIn('id', $featuredCampaigns->pluck('id'))
                    ->orderByDesc('created_at')
                    ->limit($needed)
                    ->get();

                $featuredCampaigns = $featuredCampaigns->merge($additional);
            }

            return response()->json([
                'success' => true,
                'message' => 'Featured campaigns retrieved',
                'data' => CampaignResource::collection($featuredCampaigns)
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve featured campaigns',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
