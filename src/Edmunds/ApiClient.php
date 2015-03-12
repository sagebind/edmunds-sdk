<?php
namespace CarRived\Edmunds;

abstract class ApiClient
{
    private $apiKey;
    private $cache;
    private $requestCount = 0;

    public function __construct($apiKey, ApiCache $cache = null)
    {
        $this->apiKey = $apiKey;
        $this->cache = $cache; // cache for speeding up API requests over time
    }

    public function getRequestCount()
    {
        return $this->requestCount;
    }

    public function makeCall($endpoint, array $params)
    {
        // remove empty params
        $params = array_filter($params, function ($var) {
            return $var !== null && $var !== '';
        });

        // add data format and api key
        $params['fmt'] = 'json';
        $params['api_key'] = $this->apiKey;

        // build the request url
        $url = sprintf("https://api.edmunds.com%s?%s", $endpoint, http_build_query($params));

        // check if the url is cached
        if ($this->cache && $this->cache->has($url)) {
            $responseText = $this->cache->fetch($url);

            // parse json response into an object
            $responseObject = json_decode($responseText);
        } else {
            // send the request and get the response
            $context = stream_context_create(['http' => ['ignore_errors' => true]]);
            $responseText = file_get_contents($url, false, $context);
            $responseCode = intval(substr($http_response_header[0], 9, 3));

            // server error
            if ($responseCode >= 500) {
                throw new ApiException('The API service timed out.', $responseCode);
            }

            // parse json response into an object
            $responseObject = json_decode($responseText);

            // client error
            if ($responseCode >= 400) {
                throw new ApiException($responseObject, $responseCode);
            }

            // store in cache
            if ($this->cache) $this->cache->store($url, $responseText);
            $this->requestCount++;
        }

        return $responseObject;
    }
}
