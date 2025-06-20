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
            'merchant'    => $this->whenLoaded('merchant', fn () => $this->merchant->name),
            'created_by'  => $this->whenLoaded('creator', fn () => $this->creator->name),
            'assigned_to' => $this->whenLoaded('assignee', fn () => $this->assignee->name),
            'created_at'  => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at'  => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
