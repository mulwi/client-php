<?php

namespace Mulwi;

class Index
{
    /**
     * @var Context
     */
    private $context;

    /**
     * @var string
     */
    private $indexName;

    public function __construct(Context $context, $indexName)
    {
        $this->context = $context;
        $this->indexName = $indexName;
    }

    /**
     * @param string $externalID
     * @return array
     * @throws \Exception
     */
    public function getDocument($externalID)
    {
        return $this->context->request("GET", "index/{$this->indexName}/{$externalID}");
    }

    /**
     * @param string $externalID
     * @param array $document
     * @return array
     */
    public function addDocument($externalID, $document)
    {
        return $this->updateDocument($externalID, $document);
    }

    /**
     * @param string $externalID
     * @param array $document
     * @return array
     * @throws \Exception
     */
    public function updateDocument($externalID, $document)
    {
        return $this->context->request("PUT", "index/{$this->indexName}/{$externalID}", null, $document);
    }

    /**
     * @param string $externalID
     * @return array
     * @throws \Exception
     */
    public function deleteDocument($externalID)
    {
        return $this->context->request("DELETE", "index/{$this->indexName}/{$externalID}");
    }

    /**
     * @param array $requests
     * @return array
     * @throws \Exception
     */
    public function batch($requests)
    {
        return $this->context->request("POST", "index/{$this->indexName}/batch", null, $requests);
    }
}