<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Subscription extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
     
    public $user;
    public $sub;
    public $amount;
    public $duration;
    public $artist;
    public $track;
    public $product;
    public $currency;
    public $expires_at;

    public function __construct($sub_maile)
    {
        $this->user = $sub_maile['user'];
        $this->sub = $sub_maile['sub_name'];
        $this->amount = $sub_maile['sub_amount'];
        $this->duration = $sub_maile['sub_duration'];
        $this->artist = $sub_maile['sub_artist'];
        $this->track = $sub_maile['sub_track'];
        $this->product = $sub_maile['product'];
        $this->currency = $sub_maile['currency'];
        $this->expires_at = $sub_maile['expires_at'];
        
 
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You have just Subscription to a new plan',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.subscribe',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
