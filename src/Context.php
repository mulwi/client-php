<?php

namespace Mulwi;

class Context
{
    private $appID;

    private $appKey;

    private $endpoint;

    public function __construct($appID, $appKey, $endpoint)
    {
        $this->appID = $appID;
        $this->appKey = $appKey;
        $this->endpoint = $endpoint;
    }


    public function request($method, $path, $params = null, $data = null)
    {
        $url = $this->endpoint . $path;

        if ($params && is_array($params)) {
            $url .= '?' . http_build_query($params);
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_USERAGENT, 'PHP_CLIENT');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_URL, $url);

        $headers = [
            'X-Application-Id'  => $this->appID,
            'X-Application-Key' => $this->appKey,
            'Content-type'      => 'application/json',
        ];

        $curlHeaders = [];
        foreach ($headers as $key => $value) {
            $curlHeaders[] = $key . ': ' . $value;
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $curlHeaders);

        switch ($method) {
            case 'GET':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                break;
            case 'POST':
                $body = $data ? json_encode($data) : null;
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
                break;
            case 'PUT':
                $body = $data ? json_encode($data) : null;
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
        }

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (!empty($error)) {
            throw new \Exception($url . ' ' . $error);
        }

        if ($code !== 200) {
            throw new \Exception($code . ":" . $response);
        }

        $result = json_decode($response, true);

        return $result;
    }
}