<?php
/**
 * Click Send API using ClickSend API wrapper
 *
 * @url https://github.com/ClickSend/clicksend-php
 */

namespace NotificationChannels\ClickSend;

use DomainException;
use NotificationChannels\ClickSend\Exceptions\CouldNotSendNotification;

use ClickSendLib\ClickSendClient;
use ClickSendLib\APIException;

class ClickSendApi
{
    /** @var client */
    protected $client;

    /** @var string */
    protected $username;

    /** @var string */
    protected $api_key;

    /** @var string - default from config */
    protected $sms_from;


    public function __construct($username, $api_key, $sms_from)
    {
        $this->username = $username;
        $this->api_key  = $api_key;
        $this->sms_from = $sms_from;

        // Prepare ClickSend client
        try {
            $this->client = new ClickSendClient($username, $api_key);
        }
        catch(APIException $exception) {
            throw CouldNotSendNotification::couldNotCommunicateWithClicksend($exception);
        }

        // Client may get instances e.g. getSms(), getVoice(), getAccount(), getCountries() .....
        // $this->client->getSMS();
    }

    /**
     * @param $from
     * @param $to
     * @param $message
     * @return mixed
     * @throws CouldNotSendNotification
     */
    public function sendSms($from, $to, $message)
    {
        // The payload may have more messages but we use just one at a time
        $payload = ['messages' => [
            [
                "from"  => $from ?: $this->sms_from,
                "to"    => $to,
                "body"  => $message,
            ]
        ]];

        try {
            $response = $this->client->getSMS()->sendSms($payload);

            if($response->response_code != 'SUCCESS') {
                throw new DomainException($response->response_code);
            }

            $result = $response->data->messages[0];

            if ($result->status != 'SUCCESS') {
                throw new DomainException($result->status);
            }

            return $response;
        }
        catch (DomainException $exception) {
            throw CouldNotSendNotification::clicksendRespondedWithAnError($exception);
        }
        catch (APIException $exception) {
            throw CouldNotSendNotification::couldNotCommunicateWithClicksend($exception);
        }
    }
}
