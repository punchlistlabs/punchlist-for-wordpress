<?php

namespace Punchlist;

class Menu
{

    protected $page;

    public function __construct(Page $page)
    {
        $this->page = $page;
    }

    /**
     * Creates the submenu item and calls on the Submenu Page object to render
     * the actual contents of the page.
     */
    public function addMenuPage()
    {
        add_menu_page(
            'Punchlist',
            'Punchlist',
            'manage_options',
            'punchlist-admin-page',
            [$this->page, 'render'],
            plugin_dir_url(__FILE__) . '../images/pl-logo-small.png'
        );
    }
}
