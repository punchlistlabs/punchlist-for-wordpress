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
        return $this->get('/v1/ping');
    }

    public function createProject($url, $name)
    {
        return $this->post('/v1/projects/alt', ['form_params' => ['domain' => $url, 'name' => $name, 'type' => 'web', 'no_proxy' => false]]);
    }

    public function addPageToProject($url, $project_id, $title = "")
    {
        return $this->post('/v2/pages', ['form_params' => ['url' => $url, 'title' => $title, 'type' => 'web', 'project_id' => $project_id]]);
    }

    public function getProjects()
    {
        return $this->get('/v2/projects');
    }

    public function get($path)
    {
        $res = json_decode(wp_remote_get(getenv('PUNCHLIST_URL') . $path, $this->wpargs)['body'], true);
        return $this->sanitizeResponse($res);
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
