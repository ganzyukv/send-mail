<?php

namespace SendMail;

use SendMail\Exceptions\InvalidParameter;
use SendMail\Exceptions\RequiredParameter;

class Message
{
    /**
     * @var array
     */
    protected $message = [
        'to' => '',
        'from' => '',
        'reply_to' => '',
        'origin' => '',
        'send_type' => '',
        'save' => '',
        'cc' => [],
        'bcc' => [],
        'subject' => '',
        'html' => '',
    ];

    /**
     * @param $name
     * @param $value
     * @throws InvalidParameter
     */
    public function __set($name, $value)
    {
        if (array_key_exists($name, $this->message)) {
            $name = strtolower($name);
            if ($name == 'cc' || $name == 'bcc') {
                if(is_array($value)){
                    $this->message[$name] = $value;
                }else {
                    $this->message[$name][] = $value;
                }
            } else {
                $this->message[$name] = $value;
            }
        } else {
            throw new InvalidParameter("Incorrect parameter:'{$name}'");
        }
    }

    /**
     * @return array
     */
    public function getMessage()
    {
        $this->message['cc'] = json_encode($this->message['cc']);
        $this->message['bcc'] = json_encode($this->message['bcc']);
        $this->checkMessage();
        return $this->message;
    }

    /**
     * @return bool
     * @throws RequiredParameter
     */
    public function checkMessage()
    {
        if (empty($this->message['to'])) {
            throw new RequiredParameter("Required parameter 'to' is empty");
        } elseif (empty($this->message['send_type'])) {
            throw new RequiredParameter("Required parameter 'send_type' is empty");
        } elseif (empty($this->message['subject'])) {
            throw new RequiredParameter("Required parameter 'subject' is empty");
        } elseif (empty($this->message['html'])) {
            throw new RequiredParameter("Required parameter 'html' is empty");
        }
        return true;
    }
}

