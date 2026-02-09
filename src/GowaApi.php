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
        $this->apiUrl   = rtrim($config['apiUrl'] ?? '', '/');
        $this->username = $config['username'] ?? '';
        $this->password = $config['password'] ?? '';

        $this->isEnable = (bool) ($config['isEnable'] ?? false);

        $this->httpClient = new HttpClient([
            'base_uri' =>  $this->apiUrl . '/send/' . $this->action,
            'timeout' => 8.0,
        ]);
    }

    /**
     * @param  array  $params
     *
     * @return array|null
     *
     * @throws CouldNotSendNotification|GuzzleException
     */
    public function send(array $params): ?array
    {
        if ($this->isEnable) {
            try {
                $payload = [
                    'phone' => preg_replace('/[^0-9]/', '', $params['to']) . '@s.whatsapp.net',
                    'message' => $params['body'],
                    "is_forwarded" => false,
                    "duration" => 3600
                ];

                if (isset($params['time'])) {
                    $payload['time'] = $params['time'];
                }

                $response = $this->httpClient->post('', [
                    'auth' => [$this->username, $this->password],
                    'json' => $payload,
                    'headers' => [
                        'Accept' => 'application/json',
                    ]
                ]);

                return json_decode((string) $response->getBody(), true);
            } catch (DomainException $exception) {
                throw CouldNotSendNotification::exceptionGowaRespondedWithAnError($exception);
            } catch (\Exception $exception) {
                throw CouldNotSendNotification::couldNotCommunicateWithGowa($exception);
            }
        }

        return null;
    }
}
