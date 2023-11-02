<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content ?? '',
            // 'category' => new CategoryResource($this->category),
            'created_date' => Carbon::parse($this->created_at)->format('d/m/Y'),
            'created_time' => Carbon::parse($this->created_at)->format('h:i'),
            'view_count' => $this->view_count
            // 'comments' => new CommentCollection($this->comments()->paginate(4, ['*'], 'page', 1) ?? []),
        ];
    }
}
