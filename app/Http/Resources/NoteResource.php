<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NoteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'uid'         => $this->uid,
            'title'       => $this->title,
            'body'        => $this->body,
            'type'        => $this->type,
            'status'      => $this->status,
            'merchant'    => new MerchantResource($this->whenLoaded('merchant')),
            'created_by'  => new UserResource($this->whenLoaded('creator')),
            'assigned_to' => new UserResource($this->whenLoaded('assignee')),
            'created_at'  => $this->created_at->toFormattedDateString(),
        ];
    }
}
