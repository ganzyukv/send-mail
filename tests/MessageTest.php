<?php
use SendMail\Message;

class MessageTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Message
     */
    public $message = null;

    public function setUp()
    {
        $this->message = new Message();
    }


    public function testCheckMessageIsTrue()
    {
        $this->message->to = 'to';
        $this->message->send_type = 'now';
        $this->message->subject = 'subject';
        $this->message->html = 'html';
        /* ожидаем, что $m->checkMessage() возвращает true */
        $this->assertTrue($this->message->checkMessage() === true);
    }

    /**
     * @expectedException SendMail\Exceptions\InvalidParameter
     * @expectedExceptionMessage Incorrect parameter:'to_this'
     */
    public function testCheckMessageInvalidParameter()
    {
        $this->message->to_this = '';
        $this->fail('An expected exception has not been raised in test block  CheckMessageInvalidParameter.');
    }

    /**
     * @expectedException SendMail\Exceptions\RequiredParameter
     * @expectedExceptionMessage Required parameter 'to' is empty
     */
    public function testCheckMessageRequiredParameterException()
    {
        $this->message->send_type = 'now';
        $this->message->subject = 'subject';
        $this->message->html = 'html';
        $this->message->checkMessage();

        $this->fail('An expected exception has not been raised An expected exception has not been raised in test block CheckMessageRequiredParameterException.');
    }

}
