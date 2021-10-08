<?php

namespace Punchlist;

class Api
{

    protected $key;
    protected $args;

    public function __construct($apiKey)
    {
        $this->key = $apiKey;
        $this->args = ['headers' => ['Authorization' => 'Bearer ' . $this->key], 'sslverify' => false];
    }

    public function verifyIntegration()
    {
        return $this->get('/ping');
    }

    public function get($path)
    {
        return wp_remote_get($_ENV['PUNCHLIST_URL'] . $path, $this->args)['body'];
    }
}
