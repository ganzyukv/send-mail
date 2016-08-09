<?php
use SendMail\Message;

class MessageTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Message
     */
    public $body;

    public function setUp()
    {
        $client = new GuzzleHttp\Client();
        $request = $client->createRequest('POST');
        $body = $request->getBody();
        $this->body = new Message($body);
    }


    public function testCheckMessageIsTrue()
    {
        $this->body->setField('to', 'email@email.com');
        $this->body->setField('send_type', 'now');
        $this->body->setField('subject', 'subject');
        $this->body->setField('html', 'Message body');
        /* ожидаем, что $m->checkMessage() возвращает true */
        $this->assertTrue($this->body->checkMessage() === true);
    }

    /**
     * @expectedException SendMail\Exceptions\InvalidParameter
     * @expectedExceptionMessage Incorrect parameter:'to_this'
     */
    public function testCheckMessageInvalidParameter()
    {
        $this->body->to_this = '';
        $this->fail('An expected exception has not been raised in test block  CheckMessageInvalidParameter.');
    }

    /**
     * @expectedException SendMail\Exceptions\RequiredParameter
     * @expectedExceptionMessage Required parameter 'to' is empty
     */
    public function testCheckMessageRequiredParameterException()
    {
        $this->body->setField('send_type', 'now');
        $this->body->setField('subject', 'subject');
        $this->body->setField('html', 'Message body');
        $this->body->checkMessage();

        $this->fail('An expected exception has not been raised An expected exception has not been raised in test block CheckMessageRequiredParameterException.');
    }

}
