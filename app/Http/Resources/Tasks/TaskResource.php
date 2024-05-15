<?php

namespace App\Http\Resources\Tasks;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //        $childs = [];
        //        foreach ($this->childs as $child) {
        //            $childs[] = new TaskResource($child);
        //        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'priority' => $this->priority,

            'user' => $this->user,
            'status' => $this->status->name,
            'parent' => new TaskResource($this->parent),
            'url' => url(route('tasks.show', $this)),
        ];
    }
}
