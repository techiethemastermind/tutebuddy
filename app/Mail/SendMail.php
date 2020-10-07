<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\EmailTemplate;

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
        if(isset($this->data['test'])) {
            $content = $this->data['template']->content;
        } else {
            $content = $this->getContent($this->data);
        }
        
        return $this->view('emails.sendEmail', compact('content'));
    }

    /**
     * Get content
     */
    function getContent($data)
    {
        $template_type = $data['template_type'];
        $template = EmailTemplate::where('name', $template_type)->first();

        switch($template_type)
        {
            case 'register_verify':
                $user = $data['mail_data'];
                $link = url('user/verify', $user->verify_token);
                $html = $template->content;
                $html = str_replace('{email}', $user->email, $html);
                $html = str_replace('{verify_link}', $link, $html);
            break;

            case 'contact':
                $html = $template->content;
                $contact = $data['mail_data'];
                $html = str_replace('{name}', $contact['name'], $html);
                $html = str_replace('{company}', $contact['company'], $html);
                $html = str_replace('{company_email}', $contact['company_email'], $html);
                $html = str_replace('{business_phone}', $contact['business_phone'], $html);
                $html = str_replace('{mobile_phone}', $contact['mobile_phone'], $html);
                $html = str_replace('{message}', $contact['message'], $html);
                $html = str_replace('{contact_type}', $contact['contact_type'], $html);
                if(isset($contact['meet_time'])) {
                    $html = str_replace('{meet_time}', $contact['meet_time'], $html);
                }
                if(isset($contact['ext'])) {
                    $html = str_replace('{ext}', $contact['ext'], $html);
                }
            break;

            default:
                $html = '<h1>No template selected</h1>';
        }

        return $html;
    }
}
