<?php
namespace SendMail\Mailgun;


class Mailgun
{
    /**
     * @var \Mailgun\Mailgun
     */
    protected $client;
    
    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var array
     */
    protected $postData = [];

    /**
     * @var array
     */
    protected $postFiles = [];

    /**
     * SendGrid constructor.
     * @param string|null $apiKey
     */    
    
    protected $domain = null;

    /**
     * SendGrid constructor.
     * @param string $apiKey
     * @param string $domain
     */

    public function __construct($apiKey, $domain)
    {
        $this->apiKey = $apiKey;
        $this->domain = $domain;
        $this->client = new \Mailgun\Mailgun($apiKey);
       
    }

    /**
     * This method allows the sending of a fully formed message
     * array('from'    => 'email_from@example.com',
     * 'to'      => 'email_to@example.com',
     * 'subject' => 'Email subject',
     * 'html'    => 'It is so simple to send a message.',
     * 'cc' => array('email1', 'email3', 'email2'),
     * 'bcc' => array('email1', 'email3', 'email2'),
     * 'files' => $_FILES
     * );
     * @param array $postData
     * @return integer
     */
    public function send(array $postData)
    {
            $this->createPost($postData);
            $response = $this->client->sendMessage($this->domain, $this->postData, $this->postFiles);
            return $this->responseHandler($response);
    }

    /**
     * This method generates an associative array with the data to be sent.
     * @param array $postData
     * @return array
     */
    private function createPost(array $postData)
    {
        $message = new MailgunMessage();
        foreach ($postData as $key=>$value){
            $message->$key = $value;
        }
        $this->postData = $message->getPostData();
        $this->postFiles = $message->getPostFiles();
    }

    /**
     * @param \stdClass $response
     * @return int
     * @internal param array $responseObj
     */
    public function responseHandler($response)
    {
        $httpResponseCode = $response->http_response_code;
        return $httpResponseCode;
    }
}

