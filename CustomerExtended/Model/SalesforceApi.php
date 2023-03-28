<?php

namespace Wheelpros\CustomerExtended\Model;

use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Serialize\Serializer\Json;
use Wheelpros\CustomerExtended\ConfigurationService\Config;

class SalesforceApi
{
    private const GRANT_TYPE = 'password';

    /**
     * @var Config
     */
    private Config $config;
    /**
     * @var Curl
     */
    private Curl $curl;
    /**
     * @var Json
     */
    private Json $json;

    /**
     * @param Config $config
     * @param Curl   $curl
     * @param Json   $json
     */
    public function __construct(
        Config $config,
        Curl   $curl,
        Json   $json
    ) {
        $this->config = $config;
        $this->curl = $curl;
        $this->json = $json;
    }

    /**
     * Get authorization token
     *
     * @return string
     */
    public function getSalesforceAuthToken(): string
    {
        $authApiEndpoint = $this->config->getAuthApiEndpoint();
        $clientID        = $this->config->getClientId();
        $clientSecret    = $this->config->getClientSecret();
        $username        = $this->config->getUsername();
        $password        = $this->config->getPassword();

        // Prepare Query params
        $params = "grant_type=" . self::GRANT_TYPE ."&client_id=$clientID&client_secret=$clientSecret&username=$username&password=$password";

        // make api request
        $this->doPostRequest($authApiEndpoint, $params);

        $response = $this->json->unserialize($this->curl->getBody());

        if (isset($response['access_token'])) {
            return $response['access_token'];
        }

        return "";
    }

    /**
     * Fetch customer orders
     *
     * @return array
     */
    public function fetchOrders()
    {
        $orderApiEndpoint = $this->config->getSalesforceOrderApiEndpoint();
        //$authToken = $this->getSalesforceAuthToken();
        $authToken = "00D0S000000DPPP!AQYAQIM2fpAGXkQ374Va83dOi0gZo.mVuQdnqtKvh7MJoUyibvej21Uy0P0EAcAmlyuEb7BDq3EhrtJt8.wnWpb7DTalswQ.";
        $params = "?q=SELECT+FIELDS(ALL)+from+ccrz__E_Order__c+Where+ccrz__Account__c='0010S00000QQJMxQAP'+LIMIT+30";
        $uri = $orderApiEndpoint . $params;

        // make api request
        $this->doGetRequest($uri, $authToken);

        return $this->json->unserialize($this->curl->getBody());
    }

    /**
     * Do Curl POST request
     *
     * @param  string $uri
     * @param  string $params
     * @return void
     */
    private function doPostRequest(string $uri, string $params)
    {
        $this->curl->post($uri, $params);
    }

    /**
     * Do Curl POST request
     *
     * @param  string $uri
     * @param  string $authToken
     * @return void
     */
    private function doGetRequest(string $uri, string $authToken)
    {
        $this->curl->addHeader('Authorization', "Bearer $authToken");
        $this->curl->get($uri);
    }
}
