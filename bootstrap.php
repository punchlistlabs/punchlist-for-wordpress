<?php

use Punchlist\Menu;
use Punchlist\Page;

/*
Plugin Name: WP Punchlist
Plugin URI: https://usepunchlist.com/
Description: Harness the magic of Punchlist from the WP Dashboard
Author: Punchlist Labs
Version: 1.0
Author URI: https://usepunchlist.com/
*/

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}


require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


add_action('wp_enqueue_scripts', 'loadScriptsAndStyles');
add_action('admin_enqueue_scripts', 'adminLoadScriptsAndStyles');

function loadScriptsAndStyles()
{
    //localizeVariables();
    wp_enqueue_script('punchlist', 'https://static.usepunchlist.com/js/usepunchlist.min.js?09182021', null, '1.0', true);
}

// function localizeVariables()
// {
//     wp_localize_script('wp-pucnhlist', 'localVars', [
//         'ajaxurl' => admin_url('admin-ajax.php'),
//         'plUrl' => $_ENV['PUNCHLIST_URL'],
//         'qpUrl' => $_ENV['PUNCHLIST_URL'] . '/project/create?domain' . urlencode(site_url())
//     ]);
// }

function adminLoadScriptsAndStyles()
{
    wp_enqueue_script('pl-admin-script', plugin_dir_url(__DIR__) . 'wp-punchlist/js/plAdminScript.js', ['jquery'], null, true);
    wp_localize_script('pl-admin-script', 'localVars', [
        'ajaxurl' => admin_url('admin-ajax.php'),
        'plUrl' => $_ENV['PUNCHLIST_URL'],
        'qpUrl' => $_ENV['PUNCHLIST_URL'] . '/project/create?domain=' . urlencode(site_url())
    ]);
}

add_action('admin_menu', 'addPunchlistToAdminMenu');

function addPunchListToAdminMenu()
{
    $page = new Page(['admin']);
    $menu = new Menu($page);
    $menu->addMenuPage();

    // add_menu_page('Punchlist', 'Punchlist', 'manage_options', 'punchlist', );
}

// add_submenu_page(
//     'tools',
//     'WP x Punchlist',
//     'Punchlist',
//     'manage_options',
//     'punchlist',
//     function () {
//         echo 'doing the closh';
//     }
// );