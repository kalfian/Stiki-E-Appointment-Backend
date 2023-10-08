<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class LogbookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $date = Carbon::parse($this->date);
        $date = $date->locale('id')->isoFormat('D MMMM Y HH:mm');

        return [
            "id" => $this->id,
            "activity_id" => $this->activity_id,
            "user_id" => $this->user_id,
            "date" => $date,
            "date_db" => $this->date,
            "description" => $this->description,
            "problem" => $this->problem,
            "lecture_comment" => $this->lecture_comment,
            "logbook_proof" => $this->logbook_proof,
        ];
    }
}
