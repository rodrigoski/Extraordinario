<?php

namespace App\Console\Commands;

use App\Mail\AppointmentReminderMail;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendAppointmentReminders extends Command
{
    protected $signature = 'appointments:send-reminders';

    protected $description = 'Send appointment reminders to clients for tomorrow.';

    public function handle(): int
    {
        $tomorrow = Carbon::tomorrow()->toDateString();

        // Solo se recuerdan las citas de mañana que no estén canceladas.
        $appointments = Appointment::with(['client', 'employee', 'service'])
            ->whereNotIn('status', ['cancelled'])
            ->whereDate('appointment_date', $tomorrow)
            ->get();

        foreach ($appointments as $appointment) {
            if (! $appointment->client || ! $appointment->client->email) {
                continue;
            }

            Mail::to($appointment->client->email)
                ->send(new AppointmentReminderMail($appointment));
        }

        $this->info('Se enviaron recordatorios para '.count($appointments).' cita(s) de mañana.');

        return self::SUCCESS;
    }
}
