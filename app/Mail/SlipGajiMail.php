<?php

namespace App\Mail;

use App\Models\Karyawan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SlipGajiMail extends Mailable
{
    use Queueable, SerializesModels;

    public Karyawan $karyawan;
    public ?string $pdfContent;

    /**
     * Create a new message instance.
     */
    public function __construct(Karyawan $karyawan, ?string $pdfContent = null)
    {
        $this->karyawan = $karyawan;
        $this->pdfContent = $pdfContent;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Slip Gaji Karyawan - ' . $this->karyawan->nama,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.slip_gaji',
            with: [
                'karyawan' => $this->karyawan,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        if ($this->pdfContent) {
            $fileName = 'Slip_Gaji_' . preg_replace('/[^A-Za-z0-9_-]/', '_', $this->karyawan->nama) . '.pdf';
            return [
                Attachment::fromData(fn () => $this->pdfContent, $fileName)
                    ->withMime('application/pdf'),
            ];
        }

        return [];
    }
}
