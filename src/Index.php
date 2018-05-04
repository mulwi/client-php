<?php

namespace Mulwi;

class Index
{
    private $context;

    private $indexName;


    public function __construct(Context $context, $indexName)
    {
        $this->context = $context;
        $this->indexName = $indexName;
    }

    public function getDocument($externalID)
    {
        return $this->context->request("GET", "index/{$this->indexName}/{$externalID}");
    }

    public function addDocument($externalID, $document)
    {
        return $this->updateDocument($externalID, $document);
    }

    public function updateDocument($externalID, $document)
    {
        return $this->context->request("PUT", "index/{$this->indexName}/{$externalID}", null, $document);
    }

    public function deleteDocument($externalID)
    {
        return $this->context->request("DELETE", "index/{$this->indexName}/{$externalID}");
    }

    public function batch($requests)
    {
        return $this->context->request("POST", "index/{$this->indexName}/batch", null, $requests);
    }
}