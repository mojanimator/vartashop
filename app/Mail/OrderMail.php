<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderMail extends Mailable# implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $title;
    public $message;
    public $id;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($title, $message, $id)
    {
        $this->title = $title;
        $this->message = $message;
        $this->id = $id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->subject('پیام از ورتاشاپ')->markdown('emails.order')->with(['title' => $this->title, 'message' => $this->message, 'id' => $this->id,]);
    }
}
