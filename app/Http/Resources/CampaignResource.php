<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CampaignResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'target_amount' => (int) $this->target_amount,
            'current_amount' => (int) $this->current_amount,
            'progress_percentage' => $this->target_amount > 0 ?
                min(100, ($this->current_amount / $this->target_amount) * 100) : 0,
            'image_url' => $this->image_url,
            'is_active' => (bool) $this->is_active,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'donations_count' => $this->whenLoaded('donations', function () {
                return $this->donations->count();
            }),
            'recent_donations' => $this->whenLoaded('donations', function () {
                return $this->donations->take(5)->map(function ($donation) {
                    return [
                        'id' => $donation->id,
                        'donor_name' => $donation->donor_name,
                        'amount' => (int) $donation->amount,
                        'created_at' => $donation->created_at->format('d M Y H:i'),
                    ];
                });
            })
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'message' => 'Berhasil mengambil detail kampanye'
        ];
    }
}
