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

        $date = Carbon::parse($this->date);
        $date = $date->locale('id')->isoFormat('D MMMM Y');

        $data = [

            'id' => $this->id,
            'title' => $this->title,
            'date' => $date,
            'description' => $this->description,
        ];

        if ($request->load_student) {
            $data['student'] = new UserResource($this->student);
        }

        if ($request->load_lecture) {
            $data['lecture'] = new UserResource($this->lecture);
            $data['lecture2'] = new UserResource($this->lecture2);
        }
        return $data;
    }
}
