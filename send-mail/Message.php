<?php
/**
 * Created by PhpStorm.
 * User: v.ganzyuk
 * Date: 02.08.16
 * Time: 13:34
 */

namespace SendMail;


class Message
{
    /**
     * @var array
     */
    protected $message = [
        'to',
        'from',
        'name',
        'reply_to',
        'origin',
        'send_type',
        'save',
        'cc',
        'bcc',
        'subject',
        'html',
        'files',
    ];
    
}