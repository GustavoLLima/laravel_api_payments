<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
          'id' => $this->id,
          'name' => $this->name,
          'cpf_cnpj' => $this->cpf_cnpj,
          'email' => $this->email,
          'type' => $this->type,
          'total_value' => $this->total_value,
          'created_at' => $this->created_at,
          'updated_at' => $this->updated_at
        ];
    }
}
