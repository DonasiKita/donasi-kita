<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CampaignCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'total' => $this->total(),
                'count' => $this->count(),
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
                'total_pages' => $this->lastPage()
            ],
            'links' => [
                'self' => $this->url($this->currentPage()),
                'first' => $this->url(1),
                'last' => $this->url($this->lastPage()),
                'prev' => $this->previousPageUrl(),
                'next' => $this->nextPageUrl()
            ]
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'message' => 'Berhasil mengambil data kampanye'
        ];
    }
}
