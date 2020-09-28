<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $content = $this->getContent($this->data);
        return $this->view('emails.sendEmail', compact('content'));
    }

    /**
     * Get content
     */
    function getContent($data)
    {
        $name = $data['template']->name;

        switch($name)
        {
            case 'register_verify':
                $link = url('user/verify', auth()->user()->verify_token);
                $html = $data['template']->content;
                $html = str_replace('{email}', $data['email'], $data['template']->content);
                $html = str_replace('{verify_link}', $link, $html);
            break;
            default:
                $html = '<h1>No template selected</h1>';
        }

        return $html;
    }
}
