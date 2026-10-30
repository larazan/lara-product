<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        $locale = app()->getLocale();

        return [
            'id' => $this->id,
            'name' => $this->getTranslation('name', $locale),
            'label' => $this->getTranslation('label', $locale),
            'order' => $this->order,
            'icon' => $this->icon,
            'score_weight' => $this->score_weight,
            'category_id' => $this->category_id,

            // Nama kategori induk
            'category_name' => $this->category
                ? $this->category->getTranslation('name', $locale)
                : null,
        ];
    }
}