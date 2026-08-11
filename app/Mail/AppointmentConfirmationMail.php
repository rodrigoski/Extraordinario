<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Appointment $appointment,
        public ?string $pdfPath = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmación de tu cita en BarberManager',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.appointment-confirmation',
            with: [
                'appointment' => $this->appointment,
            ],
        );
    }

    public function attachments(): array
    {
        if (! $this->pdfPath) {
            return [];
        }

        return [
            Attachment::fromStorageDisk('public', $this->pdfPath),
        ];
    }
}
