<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class QrCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $employer;
    public $qrCodePath;
    public $qrCodeUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($employer, $qrCodePath, $qrCodeUrl)
    {
        $this->employer = $employer;
        $this->qrCodePath = $qrCodePath; // Chemin absolu du fichier
        $this->qrCodeUrl = $qrCodeUrl;   // URL publique du QR Code
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Votre QR Code de connexion')
                    ->view('emails.qrcode') // Vue Blade pour le contenu de l'email
                    ->attach($this->qrCodePath, [
                        'as' => 'VotreQRCode.svg',
                        'mime' => 'image/svg+xml',
                    ]);
    }
}
