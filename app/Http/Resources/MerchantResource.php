<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MerchantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'       => $this->id,
            'uid'      => $this->uid,
            'name'     => $this->name,
            'phone'    => $this->phone,
            'email'    => $this->email,
            'category' => $this->category,
            'two_factor_enabled' => $this->two_factor_enabled,
            'has_notes' => $this->notes->count(),
            'created_at' => $this->created_at,
        ];
    }

}
