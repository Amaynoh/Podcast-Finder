<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\HostResource;

class PodcastResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'image_url'   => $this->image_url,
            'host'        => new HostResource($this->whenLoaded('host')),
            'episodes'    => EpisodeResource::collection($this->whenLoaded('episodes')),
            'created_at'  => $this->created_at->format('Y-m-d H:i'),
        ];
    }
}
