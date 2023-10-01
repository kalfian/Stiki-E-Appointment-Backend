<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {


        $students = [];
        $lectures = [];
        // get users if role lecture
        if($request->load_students) {
            $students = $this->students()->with(['user'])->get();
        }

        if($request->load_lectures) {
            $lectures = $this->lectures()->with(['user'])->get();
        }

        // convert start date to indonesia format
        $startDate = Carbon::parse($this->start_date);
        $startDate = $startDate->locale('id')->isoFormat('D MMMM Y');

        // convert end date to indonesia format
        $endDate = Carbon::parse($this->end_date);
        $endDate = $endDate->locale('id')->isoFormat('D MMMM Y');

        return [
            "id" => $this->id,
            "name" => $this->name,
            "banner" => optional($this->banner)->getUrl(),
            "banner_thumbnail" => optional($this->banner)->getUrl('thumbnail'),
            "description" => $this->description,
            "short_description" => substr($this->description, 0, 100),
            "location" => $this->location,
            "start_date" => $startDate,
            "end_date" => $endDate,
            "students" => $students,
            "lectures" => $lectures
        ];
    }
}
