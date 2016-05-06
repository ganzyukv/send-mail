<?php
namespace SendMail;

class SendMailTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SendMail
     */
    public $sendmail = null;
    public $post = [
            'to' => 'sally@example.com',
            'send_type' => 'now',
            'subject' => 'The PHP SDK is awesome!',
            'html' => 'It is so simple to send a message.',
        ];

    public function setUp()
    {
        $this->sendmail = new SendMail('http://mailservice.local/api/emails/send');
    }

    public function testSendOK()
    {
        $this->assertTrue($this->sendmail->send($this->post) === 201);
    }

    /**
     * @expectedException \GuzzleHttp\Exception\RequestException
     * @expectedExceptionCode 404
     */
    public function testSendBud404()
    {
        $sendmail = new SendMail('http://mailservice.local/');
        $sendmail->send($this->post);
    }

    /**
     * @expectedException \SendMail\Exceptions\InvalidParameter
     * @expectedExceptionMessage Incorrect parameter:'left_parameter'
     */
    public function testCheckPostInvalidParameter()
    {
        $this->post['left_parameter'] = 'It is not existing option';
        $this->sendmail->send($this->post);
        $this->fail('An expected exception has not been raised in test block  CheckPostInvalidParameter.');
    }

    /**
     * @expectedException \SendMail\Exceptions\RequiredParameter
     * @expectedExceptionMessage Required parameter 'to' is empty
     */
    public function testCheckPostRequiredParameterException()
    {
        $this->post['to'] = '';
        $this->sendmail->send($this->post);
        $this->fail('An expected exception has not been raised An expected exception has not been raised in test block CheckPostRequiredParameterException.');
    }

}