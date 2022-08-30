<?php
/**
 * Admin send mail
 *
 * @package     App\Mail
 * @subpackage  AdminMailable
 * @copyright   Copyright (c) 2020 Daito! Corporation. All Rights Reserved.
 * @author      Pham Son <songoku_vn@monotos.biz>
 */

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminMailable extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;
    protected $subjectMail;
    protected $viewsTemplate;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data = [], $subjectMail = '', $viewsTemplate = '')
    {
        $this->data          = $data;
        $this->subjectMail   = $subjectMail;
        $this->viewsTemplate = $viewsTemplate;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this->view($this->viewsTemplate)
                ->with([
                    'data' => $this->data
                ])
                ->subject($this->subjectMail);

        return $mail;
    }
}
