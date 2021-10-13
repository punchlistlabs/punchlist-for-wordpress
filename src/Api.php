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
        $postArgs = array_merge($this->args, $args);
        return wp_remote_post($_ENV['PUNCHLIST_URL'] . $path, $postArgs);
    }
}
