<?php

namespace Punchlist;


class Component
{
    protected $templates;
    protected $templateDir;

    public function __construct(array $templates = [], $templateDir = null)
    {
        $this->templates = $templates;
        $this->templateDir = $templateDir ?? __DIR__ . '/../templates/';
    }

    public function render()
    {
        // ob_start();
        foreach ($this->templates as $t) {
            include_once($this->templateDir . $t . '.php');
        }
        // ob_flush();
    }
}
