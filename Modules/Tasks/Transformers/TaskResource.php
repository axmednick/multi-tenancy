<?php

namespace Modules\Tasks\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Users\Transformers\UserResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'assigned_to' => UserResource::make($this->assignee),
            'due_date' => $this->due_date,
            'version'=>$this->version,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
