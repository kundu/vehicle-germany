<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VehicleDetailWithDeletedDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return  [
            'id'                  => $this->id,
            'manufacturer'        => $this->manufacturer,
            'model'               => $this->model,
            'fin'                 => $this->fin,
            'first_registration'  => $this->first_registration,
            'kilometers_stand'    => $this->kilometers_stand,
            'creator_id'          => $this->createdBy->id,
            'creator_name'        => $this->createdBy->name,
            'deleted_person_id'   => $this->lastEditedBy->id ?? "",
            'deleted_person_name' => $this->lastEditedBy->name ?? "",
            'created_at'          => $this->created_at->format('m/d/Y H:i'),
            'deleted_at'          => $this->deleted_at->format('m/d/Y H:i'),
        ];
    }
}
