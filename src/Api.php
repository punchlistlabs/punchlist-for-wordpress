<?php

namespace Punchlist;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

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
        $this->client = new Client(['verify' => false]);
    }

    public function verifyIntegration()
    {
        $tryProjects = $this->get('/projects');
        if (isset($tryProjects['items'])) {
            return true;
        } else {
            return false;
        }
    }

    public function createProject($url, $name)
    {
        return $this->postJson('/projects', ['domain' => $url, 'name' => $name, 'type' => 'web']);
    }

    public function addPageToProject($url, $project_id, $title = "")
    {
        return $this->postJson('/pages', ['url' => $url, 'title' => $title, 'project_id' => $project_id, 'type' => 'web']);
    }

    public function getProjects()
    {
        return $this->get('/projects');
    }

    public function get($path)
    {
        $res = wp_remote_get(getenv('PUNCHLIST_URL') . $path, $this->wpargs);
        $decoded = json_decode($res['body'], true);
        return $decoded['data'] ? $this->sanitizeResponse($decoded['data']) : null;
    }

    public function post($path, $args = [])
    {
        $url = getenv('PUNCHLIST_URL') . $path;
        $postArgs = array_merge($this->args, $args);
        $res = $this->client->post(
            $url,
            $postArgs
        );

        $res = json_decode($res->getBody()->getContents(), true);

        return $this->sanitizeResponse($res);
    }

    public function postJson($path, $args = []) {
        $postArgs = array_merge($this->args, ['json' => $args], ['Accept' => 'application/json']);
        
        $url = getenv('PUNCHLIST_URL') . $path;
      
        $res = $this->client->request(
            'POST',
            $url,
            $postArgs
        );
    
        $res = json_decode($res->getBody()->getContents(), true);
        return $this->sanitizeResponse($res['data']);
    
    }

    protected function sanitizeResponse(iterable $res)
    {
        $func = function(&$v, &$k) {
            $v = esc_html($v);
            $k = esc_html($k);
            return [$k => $v];
        };

        array_walk_recursive($res, $func);

        return $res;
    }
}
