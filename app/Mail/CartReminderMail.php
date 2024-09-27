<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CartReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $cartDetails;

    /**
     * Create a new message instance.
     */
    public function __construct($cartDetails)
    {
        $this->cartDetails = $cartDetails;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Reminder: You have items in your cart')
                    ->view('emails.cartReminder');
    }
}
