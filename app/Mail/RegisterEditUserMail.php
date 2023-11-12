<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegisterEditUserMail extends Mailable# implements ShouldQueue
{
    use Queueable, SerializesModels;
    protected $token;
    public $_from;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token, $_from)
    {
        $this->token = $token;
        $this->_from = $_from;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->_from == 'register')
            return $this->subject(' تکمیل ثبت نام')->markdown('emails.user_register')->with(['email_token' => $this->token]);
        if ($this->_from == 'edit')
            return $this->subject('ویرایش اطلاعات')->markdown('emails.user_edit')->with(['email_token' => $this->token]);
    }
}
