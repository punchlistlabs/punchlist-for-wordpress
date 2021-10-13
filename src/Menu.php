<?php

namespace Punchlist;

class Menu
{

    protected $component;

    public function __construct(Component $component)
    {
        $this->component = $component;
    }

    /**
     * Creates the submenu item and calls on the Menu Page object to render
     * the actual contents of the page.
     */
    public function addMenuPage()
    {
        add_menu_page(
            'Punchlist',
            'Punchlist',
            'manage_options',
            'punchlist-admin-page',
            [$this->component, 'render'],
            plugin_dir_url(__FILE__) . '../images/pl-logo-small.png'
        );
    }

    /**
     * Creates the metabox item and calls on the MetaBox object to render
     * the actual contents of the metabox.
     */
    public function addMetaBox()
    {
        add_meta_box(
            'punchlist_metabox',
            'Punchlist',
            [$this->component, 'render'],
            ['page', 'post'],
            'side'
        );
    }
}
