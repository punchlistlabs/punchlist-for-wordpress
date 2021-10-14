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
        $this->args = ['headers' => ['Authorization' => 'Bearer ' . $this->key, "Content-Type" => "multipart/form-data",], 'sslverify' => false];
        $this->client = new Client();
    }

    public function verifyIntegration()
    {
        return $this->get('/ping');
    }

    public function createProject($url, $name)
    {
        return $this->post('/projects', ['body' => ['domain' => $url, 'name' => $name, 'type' => 'web']]);
    }

    public function get($path)
    {
        return wp_remote_get($_ENV['PUNCHLIST_URL'] . $path, $this->args)['body'];
    }

    public function post($path, $args = [])
    {
        //$postArgs = array_merge($this->args, $args);
        $client = new Client();
        $res = $client->post('https://f3fc3582a0ad.ngrok.io/api/v1/projects/alt', [
            'form_params' => [
                'domain' => 'https://google.com',
                'name' => 'fuck this shit',
                'type' => 'web',
            ],
            'sslverify' => false,
            'verify' => false,
            'headers' => ['Authorization' => 'Bearer XSdS2P4Pu1o3hEtDiMCbSYXFmDFQGL3E2HERcnvMlI714HH7VgCaq6zvvAo6']
        ]);

        return $res->getBody()->getContents();

        // return wp_remote_post($_ENV['PUNCHLIST_URL'] . $path, $postArgs);
    }
}
