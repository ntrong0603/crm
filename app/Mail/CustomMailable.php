<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomMailable extends Mailable
{
    use Queueable, SerializesModels;

    protected $datas;
    public $subject;
    protected $views;
    protected $files;
    protected $isTemplate;
    protected $arrOther;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($isTemplate, $datas, $subject, $views = '', $arrOther = [])
    {
        $this->datas      = $datas;
        $this->subject    = $subject;
        $this->views      = $views;

        $this->isTemplate = $isTemplate;
        $this->arrOther = $arrOther;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->isTemplate) {
            $mail = $this->view($this->views)
                    ->with([
                        'datas' => $this->datas
                    ])
                    ->subject($this->subject);
        } else {
            $mail = $this->html($this->datas)
                    ->subject($this->subject);
        }

        if (isset($this->arrOther['cc'])) {
            $mail = $this->cc($this->arrOther['cc']);
        }
        if (isset($this->arrOther['from'])) {
            $mail = $this->from($this->arrOther['from']['email'], $this->arrOther['from']['name']);
        }
        if (isset($this->arrOther['bcc'])) {
            $mail = $this->bcc($this->arrOther['bcc']);
        }
        if (isset($this->arrOther['files'])) {
            foreach ($this->arrOther['files'] as $file) {
                $mail->attach($file);
            }
        }

        return $mail;
    }
}
