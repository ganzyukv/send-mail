<?php
namespace SendMail\SendGrid;

use GuzzleHttp\Client;
use GuzzleHttp\Message\ResponseInterface;
use GuzzleHttp\Message\RequestInterface;



class SendGrid
{
    /**
     * @var string
     */
    protected $apiEndpoint;
    
    /**
     * @var Client
     */
    protected $client;
    
    /**
     * @var string
     */
    protected $apiKey;

    /**
     * SendGrid constructor.
     * @param string|null $apiEndpoint
     * @param string|null $apiKey
     * @param integer|null $timeout
     */
    public function __construct(
        $apiEndpoint = '',
        $apiKey = null,
        $timeout = 10
    )
    {
        $this->apiEndpoint = $apiEndpoint;
        $this->apiKey = $apiKey;
        $this->client = new Client(['timeout' => $timeout]);
    }

    /**
     * This method allows the sending of a fully formed message
     * array('from'    => 'email_from@example.com',
     * 'to'      => 'email_to@example.com',
     * 'reply_to' => 'replay_to@example.com',
     * 'subject' => 'Email subject',
     * 'html'    => 'It is so simple to send a message.',
     * 'send_type' => 'now', //Defines the send method (queue | now)
     * 'cc' => array('email1', 'email3', 'email2'),
     * 'bcc' => array('email1', 'email3', 'email2'),
     * 'origin' => 'Defines the origin of the email',
     * 'files' => $_FILES
     * );
     * @param array $postData
     * @return integer
     */
    public function send(array $postData)
    {
            $request = $this->client->createRequest('POST', $this->apiEndpoint);
            $request->setHeader('Api-Token', 'key:' . $this->apiKey);
            $body = $request->getBody();
            $this->createPost($postData, $body);
            $response = $this->client->send($request);
            return $this->responseHandler($response);
    }

    /**
     * This method generates an associative array with the data to be sent.
     * @param array $postData
     * @param $body RequestInterface
     * @return array
     */
    private function createPost(array $postData, $body)
    {
        $message = new SendGridMessage($body);
        foreach ($postData as $key=>$value){
            $message->$key = $value;
        }
        return $message->getMessage();
    }

    /**
     * @param ResponseInterface $responseObj
     * @return integer
     * @throws ResponseInterface
     */
    public function responseHandler(ResponseInterface $responseObj)
    {
        $httpResponseCode = $responseObj->getStatusCode();
        return $httpResponseCode;
    }
}

