<?php
namespace SendMail;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;


class SendMail
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
     * SendMail constructor.
     * @param string|null $apiEndpoint
     * @param string|null $apiKey
     * @param integer|null $timeout
     */
    public function __construct(
        $apiEndpoint = 'http://mailservice.local/api/emails/send',
        $apiKey = null,
        $timeout = 10
    )
    {
        $this->apiEndpoint = $apiEndpoint;
        $this->client = new Client([
            'headers' => [
                'api-token' => 'key:' . $apiKey
            ],
            'timeout' => $timeout]);
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
     * 'origin' => 'Defines the origin of the email'
     * );
     * @param array $postData
     * @return integer
     */
    public function send(array $postData)
    {
        $response = $this->client->post($this->apiEndpoint, ['form_params' => $this->createPost($postData)]);
        return $this->responseHandler($response);
    }

    /**
     * This method generates an associative array with the data to be sent.
     * @param array $postData
     * @return array
     */
    private function createPost(array $postData)
    {
        $message = new Message();
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

