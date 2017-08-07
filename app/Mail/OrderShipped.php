<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderShipped extends Mailable
{
    use Queueable, SerializesModels;

    public $options;
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($options, $data)
    {
        $this->options = $options;
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->options['from'], $this->options['from_jp'])
            ->subject($this->options['subject'])
            ->text($this->options['template']);
    }

    public function controllernikakeyo()
    {
        $options = [
            'from' => 'nanashi@test.jp',
            'from_jp' => '名無しさん',
            'to' => 'shimaduwataru@gmail.com',
            'subject' => 'gmailのテストメール',
            'template' => 'emails.mail', // resources/views/emails/mail.blade.php
        ];

        $data = [
            'test' => 'testです',
        ];
        Mail::to($options['to'])->send(new OrderShipped($options, $data));
    }
}
