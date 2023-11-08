<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\User;
use App\Models\Appointment;
use App\Models\ReferenceStatus;

use App\Jobs\SendNotification;

use Carbon\Carbon;

class SendNotificationReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Notification Reminder to specific user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //
        $now = Carbon::now()->addMinutes(15);

        $appointmentStudents = Appointment::where('status', ReferenceStatus::STATUS_APPOINTMENT_ACCEPTED_ID)
            ->with(['lecture'])
            ->where('start_date', $now->format('Y-m-d H:i:00'))
            ->get();

        // Group by student
        $students = [];

        foreach ($appointmentStudents as $appointment) {
            $students[$appointment->student_id][] = $appointment;
        }

        foreach ($students as $studentId => $appointments) {
            $user = User::find($studentId);
            $message = $this->generateMessageStudent($user, $appointments);
            SendNotification::dispatch($studentId, false, "Jadwal Bimbingan", $message);
        }

        $appointmentLectures = Appointment::where('status', ReferenceStatus::STATUS_APPOINTMENT_ACCEPTED_ID)
            ->with(['student'])
            ->where('start_date', $now->format('Y-m-d H:i:00'))
            ->get();

        // Group by lecture
        $lectures = [];
        foreach ($appointmentLectures as $appointment) {
            $lectures[$appointment->lecture_id][] = $appointment;
        }

        foreach ($lectures as $lectureId => $appointments) {
            $user = User::find($lectureId);
            $message = $this->generateMessageLecture($user, $appointments);
            SendNotification::dispatch($lectureId, false, "Jadwal Bimbingan", $message);
        }

        return Command::SUCCESS;
    }

    private function generateMessageLecture($user, $appointments) {
        $message = '';
        $message .= "Hai {$user->name},\n";
        $message .= "15 Menit lagi akan ada jadwal bimbingan dengan\n";
        foreach ($appointments as $appointment) {
            $startDate = Carbon::parse($appointment->start_date)->format('H:i');
            $endDate = Carbon::parse($appointment->end_date)->format('H:i');
            $message .= "mahasiswa {$appointment->student->name} pada $startDate sampai $endDate\n";
        }

        return $message;
    }

    private function generateMessageStudent($user, $appointments) {
        $message = '';

        $message .= "Hai {$user->name},\n";
        $message .= "15 Menit lagi akan ada jadwal bimbingan\n";
        foreach ($appointments as $appointment) {
            $startDate = Carbon::parse($appointment->start_date)->format('H:i');
            $endDate = Carbon::parse($appointment->end_date)->format('H:i');
            $message .= "dengan dosen {$appointment->lecture->name} pada $startDate sampai $endDate\n";
        }

        return $message;

    }

}
