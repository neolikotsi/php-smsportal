<?php

namespace NeoLikotsi\SMSPortal;

class RestClient
{
    /**
     * @var string
     */
    const HTTP_GET = 'GET';
    /**
     * @var string
     */
    const HTTP_POST = 'POST';
    /**
     * @var string
     */
    private $baseRestUri;
    private $apiToken;
    private $client;
    private $apiId;
    private $apiSecret;

    /**
     * Create a new API connection
     *
     * @param string $apiToken The token found on your integration
     */
    public function __construct(string $apiId, string $apiSecret, string $baseRestUri = 'https://rest.smsportal.com/v1/')
    {
        $this->client = new \GuzzleHttp\Client;
        $this->apiId = $apiId;
        $this->apiSecret = $apiSecret;
        $this->baseRestUri = $baseRestUri;
    }

    /**
     * Get Message Class
     *
     * @return RestClient
     */
    public function message() : RestClient
    {
        return $this;
    }

    /**
     * get apiToken
     *
     * https://docs.smsportal.com/reference#authentication
     * @return RestClient
     */
    public function authorize()
    {
        $response = $this->client->request(static::HTTP_GET, $this->baseRestUri . 'Authentication', [
            'http_errors' => false,
            'headers' => ['Authorization' => 'Basic ' . base64_encode($this->apiId . ':' . $this->apiSecret)]
        ]);
        $responseData = $this->getResponse($response->getBody());
        $this->apiToken = $responseData['token'];
        return $this;
    }

    /**
     * Submit API request to send SMS
     *
     * @link https://docs.smsportal.com/reference#bulkmessages
     * @param array $options
     * @return array
     */
    public function send(array $options)
    {
        $this->authorize();
        $response = $this->client->request(static::HTTP_POST, $this->baseRestUri . 'BulkMessages', [
            'json' => $options,
            'http_errors' => false,
            'headers' => ['Authorization' => 'Bearer ' . $this->apiToken]
        ]);
        return $this->getResponse($response->getBody());
    }

    /**
     * Submit API request to send SMS
     *
     * @link https://docs.smsportal.com/reference#groupmessages
     * @param array $options
     * @return array
     */
    public function sendToGroup(array $options)
    {
        $this->authorize();
        $response = $this->client->request(static::HTTP_POST, $this->baseRestUri . 'GroupMessages', [
            'json' => $options,
            'http_errors' => false,
            'headers' => ['Authorization' => 'Bearer ' . $this->apiToken]
        ]);
        return $this->getResponse($response->getBody());
    }

    /**
     * Get sms credit balance
     *
     * @link https://docs.smsportal.com/reference#balance
     * @return string
     */
    public function balance()
    {
        $response = $this->client->request(static::HTTP_GET, $this->baseRestUri . 'Balance', [
            'http_errors' => false,
            'headers' => ['Authorization' => 'Bearer ' . $this->apiToken]
        ]);
        $responseData = $this->getResponse($response->getBody());
        return $responseData['number'];
    }

    /**
     * Tranform response string to responseData
     *
     * @param string $responseBody
     * @return array
     */
    private function getResponse(string $responseBody)
    {
        return json_decode($responseBody, true);
    }
}
