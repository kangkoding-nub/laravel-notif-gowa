<?php

namespace NotificationChannels\Gowa;

use DomainException;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use NotificationChannels\Gowa\Exceptions\CouldNotSendNotification;

class GowaApi
{
    protected string $apiUrl;
    protected string $username;
    protected string $password;
    protected HttpClient $httpClient;
    public bool $isEnable;
    protected string $action = 'message';
    protected string $priority = '10';

    public function __construct($config)
    {
        $this->apiUrl   = $config['apiUrl'];
        $this->username = $config['username'];
        $this->password = $config['password'];

        $this->isEnable = ($config['isEnable'] ?? 0);


        $this->httpClient = new HttpClient([
            'base_uri' =>  $this->apiUrl.'/send/'.$this->action,
            'timeout' => 8.0,
        ]);
    }

    /**
     * @param  array  $params
     *
     * @return array
     *
     * @throws CouldNotSendNotification|GuzzleException
     */
    public function send(array $params)
    {
        if($this->isEnable)
        {
            try {
                $response = $this->httpClient->post('', [
                    'auth' => [$this->username, $this->password],
                    'json' => [
                        'phone' => $params['to'].'@s.whatsapp.net',
                        'message' => $params['body'],
                        "is_forwarded" => false,
                        "duration" => 3600
                    ],
                    'headers' => [
                        'Accept' => 'application/json',
                    ]
                ]);
                return json_decode((string) $response->getBody(), true);
            } catch (DomainException $exception) {
                throw CouldNotSendNotification::exceptionWahaRespondedWithAnError($exception);
            } catch (\Exception $exception) {
                throw CouldNotSendNotification::couldNotCommunicateWithWaha($exception);
            }
        }
    }
}
