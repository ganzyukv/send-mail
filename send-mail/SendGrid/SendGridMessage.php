<?php

namespace SendMail\SendGrid;

use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Post\PostFile;
use SendMail\Message;
use SendMail\Exceptions\InvalidParameter;
use SendMail\Exceptions\RequiredParameter;

class SendGridMessage extends Message
{
    /**
     * @var RequestInterface
     */
    protected $body;

    /**
     * @param $body RequestInterface
     */
    public function __construct($body)
    {
        $this->body = $body;
    }

    /**
     * @param $name
     * @param $value
     * @throws InvalidParameter
     */
    public function __set($name, $value)
    {
        if (in_array($name, $this->message)) {
            $name = strtolower($name);
            if ($name == 'cc' || $name == 'bcc') {
                if(is_array($value)){
                    $this->body->setField($name, $value);
                }else {
                    $this->body->setField($name.'[]', $value);
                }
            } elseif($name == 'files'){
                foreach ($value["error"] as $key => $error) {
                    if ($error == UPLOAD_ERR_OK) {
                        $tmp_name = $value["tmp_name"][$key];
                        $original_name = $value["name"][$key];
                        $this->body->addFile(new PostFile($name.'[]', fopen($tmp_name, 'r'), $original_name));
                    }
                }
            }else {
                if ($name == 'to' || $name == 'from') {
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        throw new InvalidParameter("Parameter '{$name}' must contain a valid email address");
                    }
                }
                $this->body->setField($name, $value);
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
        $this->checkMessage();
        return $this->message;
    }

    /**
     * @return bool
     * @throws RequiredParameter
     */
    public function checkMessage()
    {
        if (trim($this->body->getField('to')) == '') {
            throw new RequiredParameter("Required parameter 'to' is empty");
        } elseif (trim($this->body->getField('send_type')) == '') {
            throw new RequiredParameter("Required parameter 'send_type' is empty");
        } elseif (trim($this->body->getField('subject')) == '') {
            throw new RequiredParameter("Required parameter 'subject' is empty");
        } elseif (trim($this->body->getField('html')) == '') {
            throw new RequiredParameter("Required parameter 'html' is empty");
        }
        return true;
    }
}

