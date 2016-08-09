<?php
/**
 * Created by PhpStorm.
 * User: v.ganzyuk
 * Date: 02.08.16
 * Time: 11:38
 */

namespace SendMail;

use SendMail\Mailgun\Mailgun;
use SendMail\SendGrid\SendGrid;
use SendMail\Exceptions\InvalidParameter;

class SendMail
{
    /**
     * @var string
     */
    protected $apiEndpoint;

    /**
     * @var SendGrid|\Mailgun\Mailgun
     */
    protected $client;

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $domain;

    /**
     * SendMail constructor.
     * @param string|null $apiEndpoint
     * @param string|null $apiKey
     * @param string|null $service
     * @param string|null $domain
     * @throws InvalidParameter
     */

    public function __construct(
        $apiEndpoint = '',
        $apiKey = null,
        $service = null,
        $domain = null
    )
    {
        $this->apiEndpoint = $apiEndpoint;
        $this->apiKey = $apiKey;
        $this->domain = $domain;
        switch ($service) {
            case 'mailgun':
                if (is_null($this->domain)) {
                    throw new InvalidParameter("Parameter \$domain must be specified if the service 'mailgun'");
                } elseif (is_null($this->domain)) {
                    throw new InvalidParameter("Parameter \$apiKey must be specified if the service 'mailgun'");
                }
                $this->client = new Mailgun($this->apiKey, $this->domain);
                break;
            default:
                $this->client = new SendGrid($this->apiEndpoint, $apiKey);
                break;
        }
    }

    /**
     * @param array $post
     * @return int
     */
    public function send(array $post)
    {
        return $this->client->send($post);
    }
}