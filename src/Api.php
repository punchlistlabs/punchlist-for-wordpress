<?php

namespace Punchlist;

use GuzzleHttp\Client;

class Api
{

    protected $key;
    protected $args;
    protected $client;

    public function __construct($apiKey)
    {
        $this->key = $apiKey;
        $this->wpargs = ['headers' => ['Authorization' => 'Bearer ' . $this->key, "Content-Type" => "multipart/form-data",], 'sslverify' => false];
        $this->args = ['headers' => ['Authorization' => 'Bearer ' . $this->key], 'verify' => false];
        $this->client = new Client();
    }

    public function verifyIntegration()
    {
        return $this->get('/ping');
    }

    public function createProject($url, $name)
    {
        return $this->post('/projects/alt', ['form_params' => ['domain' => $url, 'name' => $name, 'type' => 'web', 'no_proxy' => true]]);
    }

    public function get($path)
    {
        return wp_remote_get(getenv('PUNCHLIST_URL') . $path, $this->wpargs)['body'];
    }

    public function post($path, $args = [])
    {
        $url = getenv('PUNCHLIST_URL') . $path;
        $postArgs = array_merge($this->args, $args);
        $client = new Client();
        $res = $client->post(
            $url,
            $postArgs
        );

        return json_decode($res->getBody()->getContents());
    }
}
