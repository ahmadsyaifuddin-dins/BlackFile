<?php

namespace App\Mail;

use App\Models\Entity;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewEntityAlert extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Instance Entity yang baru dibuat.
     *
     * @var \App\Models\Entity
     */
    public $entity;

    /**
     * Buat instance pesan baru.
     *
     * @param \App\Models\Entity $entity
     */
    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
    }

    /**
     * Dapatkan amplop pesan.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[BLACKFILE ALERT] New Entity Registered: ' . $this->entity->codename,
        );
    }

    /**
     * Dapatkan definisi konten pesan.
     */
    public function content(): Content
    {
        // Kita menggunakan template markdown
        return new Content(
            markdown: 'emails.entities.new-alert',
        );
    }

    /**
     * Dapatkan lampiran untuk pesan.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}