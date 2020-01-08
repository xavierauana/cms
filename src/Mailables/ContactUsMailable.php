<?php

namespace Anacreation\Cms\Mailables;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactUsMailable extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var string
     */
    private $email;
    /**
     * @var array
     */
    public $fields;
    /**
     * @var array
     */
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $email, array $fields, string $subject = 'Enquiry') {
        //
        $this->email = $email;
        $this->fields = $fields;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this->view('cms::emails.contact_us')
                    ->subject($this->subject)
                    ->from($this->email,
                           'Web site');
    }
}
