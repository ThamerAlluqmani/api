<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'due_date' => $this->due_date,
            'category'=> new CategoryResource($this->whenLoaded('category')),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'files' => FileResource::collection($this->whenLoaded('files')),
        ];
    }
}
