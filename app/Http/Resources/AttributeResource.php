<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttributeResource extends JsonResource
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
            'description' => $this->description,
            'type' => $this->type,
            'unit' => $this->unit,
            'options' => $this->options,
            'validation_rules' => $this->validation_rules,
            'sort_order' => $this->sort_order,
            'icon_svg' => $this->icon_svg,
            'is_filterable' => $this->is_filterable,
            'is_comparable' => $this->is_comparable,
            'is_active' => $this->is_active,

            // Nama group 
            'group_name' => $this->group
                ? $this->group->getTranslation('name', $locale)
                : null,
        ];
    }
}
