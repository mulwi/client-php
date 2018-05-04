<?php

namespace Mulwi;

class Client
{
    /**
     * @var Context
     */
    private $context;

    public function __construct($appID, $apiKey, $endpoint = 'https://api.mulwi.com/')
    {
        $this->context = new Context($appID, $apiKey, $endpoint);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function listIndexes()
    {
        return $this->context->request("GET", "index");
    }

    /**
     * @param string $indexName
     * @return array
     * @throws \Exception
     */
    public function clearIndex($indexName)
    {
        return $this->context->request('POST', "index/$indexName/clear", null);
    }

    /**
     * @param string $indexName
     * @return array
     * @throws \Exception
     */
    public function deleteIndex($indexName)
    {
        return $this->context->request('DELETE', "index/$indexName", null);
    }

    /**
     * @param string $taskID
     * @param int $retryTime
     * @return array
     * @throws \Exception
     */
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

    /**
     * @param string $indexName
     * @return Index
     */
    public function getIndex($indexName)
    {
        return new Index($this->context, $indexName);
    }
}