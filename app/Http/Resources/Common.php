<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Common extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'status' => ($this->status)?$this->status:true,
            'message' => ($this->message)?$this->message:"",
            'data' => ($this->data)?$this->data:[]
        ];
    }
}
