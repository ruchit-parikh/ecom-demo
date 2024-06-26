<?php

namespace App\Mail\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetLinkEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    protected string $token;

    /**
     * Create a new message instance.
     *
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self
    {
        return $this
            ->subject("Reset your password")
            ->markdown('emails.auth.reset_password')
            ->with('token', $this->token);
    }
}
