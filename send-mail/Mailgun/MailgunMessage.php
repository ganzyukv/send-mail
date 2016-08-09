<?php

namespace SendMail\Mailgun;

use SendMail\Exceptions\InvalidParameter;
use SendMail\Exceptions\RequiredParameter;
use SendMail\Message;

class MailgunMessage extends Message
{
    /**
     * @var array
     */
    protected $postFiles = [];

    /**
     * @var array
     */

    protected $postData = [];

    /**
     * @param string $name
     * @param string $value
     * @throws InvalidParameter
     */

    public function __set($name, $value)
    {
        if (in_array($name, $this->message)) {
            $name = strtolower($name);
            if ($name == 'cc' || $name == 'bcc') {
                if (is_array($value)) {
                    $this->setField($name, $value);
                } elseif (!empty($value)){
                    $this->setField($name . '[]', $value);
                }
            } elseif ($name == 'files') {
                foreach ($value["error"] as $key => $error) {
                    if ($error == UPLOAD_ERR_OK) {
                        $tmp_name = $value["tmp_name"][$key];
                        $original_name = $value["name"][$key];
                        $this->addFile($original_name, $tmp_name);
                    }
                }
            } else {
                $this->setField($name, $value);
            }
        } else {
            throw new InvalidParameter("Incorrect parameter:'{$name}'");
        }
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function setField($name, $value)
    {
        $this->postData[$name] = $value;
    }

    /**
     * @param string $name
     * @param string $tmp_name
     */
    public function addFile($name, $tmp_name)
    {
        $this->postFiles['attachment'][$name]['filePath'] = $tmp_name;
        $this->postFiles['attachment'][$name]['remoteName'] = $name;
    }

    /**
     * @return array
     */
    public function getPostData()
    {
        $this->checkMessage();
        return $this->postData;
    }

    /**
     * @return array
     */
    public function getPostFiles()
    {
        return $this->postFiles;
    }

    /**
     * @param string $field
     * @return string
     */
    public function getField($field)
    {
        return $this->postData[$field];
    }

    /**
     * @return bool
     * @throws RequiredParameter
     *      * filter_var($email_a, FILTER_VALIDATE_EMAIL
     */
    public function checkMessage()
    {
        if (trim($this->getField('to')) == '') {
            throw new RequiredParameter("Required parameter 'to' is empty");
        } elseif (trim($this->getField('from')) == '') {
            throw new RequiredParameter("Required parameter 'from' is empty");
        } elseif (trim($this->getField('subject')) == '') {
            throw new RequiredParameter("Required parameter 'subject' is empty");
        } elseif (trim($this->getField('html')) == '') {
            throw new RequiredParameter("Required parameter 'html' is empty");
        }
        return true;
    }
}

