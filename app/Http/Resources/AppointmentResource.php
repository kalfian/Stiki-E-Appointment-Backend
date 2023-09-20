<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $start_date = Carbon::parse($this->start_date);
        $start_date = $start_date->locale('id')->isoFormat('D MMMM Y HH:mm');

        $end_date = Carbon::parse($this->end_date);
        $end_date = $end_date->locale('id')->isoFormat('D MMMM Y HH:mm');

        $data = [

            'id' => $this->id,
            "activity_id" => $this->activity_id,
            'title' => $this->title,
            'location' => $this->location,
            'status' => $this->status,
            'status_text' => status()::translateStatus($this->status),
            'start_date' => $start_date,
            'end_date' => $end_date,
            'description' => $this->description,
        ];

        if ($request->load_student) {
            $data['student'] = new UserResource($this->student);
        }

        if ($request->load_lectures) {
            $data['lecture'] = new UserResource($this->lecture);
            $data['lecture2'] = new UserResource($this->lecture2);
        }
        return $data;
    }
}
