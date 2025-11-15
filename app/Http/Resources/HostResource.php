<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HostResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'     => $this->id,
            'name'   => $this->name,
            'bio'    => $this->bio,
            'avatar' => $this->avatar,
        ];
    }
}
