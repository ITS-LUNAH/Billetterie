<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment; // Pour les pièces jointes
use Illuminate\Queue\SerializesModels;
use App\Models\Reservation; // Importez le modèle Reservation

class TicketsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;
    protected $pdfContent; // Contenu binaire du PDF

    /**
     * Crée une nouvelle instance de message.
     */
    public function __construct(Reservation $reservation, string $pdfContent)
    {
        $this->reservation = $reservation;
        $this->pdfContent = $pdfContent;
    }

    /**
     * Obtenez l'enveloppe du message.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Vos Billets pour la Réservation #' . $this->reservation->idreservation,
        );
    }

    /**
     * Obtenez le contenu du message.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.tickets',
            with: [
                'reservationId' => $this->reservation->idreservation,
                'userName' => $this->reservation->participant->user->name ?? 'Cher client',
                // Autres données à passer à la vue de l'email
            ]
        );
    }

    /**
     * Obtenez les pièces jointes pour le message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->pdfContent, "billets_reservation_{$this->reservation->idreservation}.pdf")
                      ->withMime('application/pdf'),
        ];
    }
}
