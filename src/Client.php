<?php

namespace Mulwi;

class Client
{
    private $context;

    public function __construct($appID, $apiKey, $endpoint = 'https://api.mulwi.com/')
    {
        $this->context = new Context($appID, $apiKey, $endpoint);
    }
    
    public function listIndexes()
    {
        return $this->context->request("GET", "index");
    }

    public function clearIndex($indexName)
    {
        return $this->context->request('POST', "index/$indexName/clear", null);
    }

    public function deleteIndex($indexName)
    {
        return $this->context->request('DELETE', "index/$indexName", null);
    }

    public function waitTask($taskID, $retryTime = 100)
    {
        while (true) {
            $res = $this->context->request("GET", "task/{$taskID}");
            if ($res['status'] === 'SUCCESS') {
                return $res;
            }
            usleep($retryTime * 1000);
        }
    }

    public function getIndex($indexName)
    {
        return new Index($this->context, $indexName);
    }
}