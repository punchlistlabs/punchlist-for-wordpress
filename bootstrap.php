<?php

use Punchlist\Menu;
use Punchlist\Component;
use Punchlist\Api;
use Punchlist\Preview;

/*
Plugin Name: WP Punchlist
Plugin URI: https://usepunchlist.com/
Description: Harness the magic of Punchlist from the WP Dashboard
Author: Punchlist Labs
Version: 1.0
Author URI: https://usepunchlist.com/
Credits: This plugin borrows heavily from Public Post Preview plugin but Dominik Schilling. WP won't allow
two headers on the same plugin, but let's give credit where it's due.
License: GPLv2 or later

Copyright (C) 2021 Punchlist Labs Inc.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

require __DIR__ . '/vendor/autoload.php';

putenv('PUNCHLIST_URL=https://punchlist.test/api');
putenv('PUNCHLIST_SCRIPT=https://static.usepunchlist.com/js/punchlist-local.min.js?10212022');

if (!is_admin()) {
    add_action('pre_get_posts', ['Punchlist\Preview', 'showPreview']);
    add_action('wp_enqueue_scripts', 'loadScriptsAndStyles');
} else {
    add_action('admin_enqueue_scripts', 'adminLoadScriptsAndStyles');
    add_action('admin_menu', 'addPunchlistToAdminMenu');
    add_action('wp_ajax_pl_check_integration', 'checkIntegration');
    add_action('wp_ajax_pl_get_projects', 'getProjects');
    add_action('wp_ajax_pl_create_project_edit_screen', 'createPostPreview');
    add_action('wp_ajax_pl_add_to_project_edit_screen', 'addPageToProject');
    add_action('add_meta_boxes', 'addPlMetaBox');
    add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'settingsLink');
}

function settingsLink($links)
{
    $links[] = '<a href="' .
        admin_url('admin.php?page=punchlist-admin-page') .
        '">' . __('Settings') . '</a>';
    return $links;
}

function loadScriptsAndStyles()
{
    wp_enqueue_script('punchlist', getenv('PUNCHLIST_SCRIPT'), null, '1.0', false);
}

function adminLoadScriptsAndStyles()
{
    wp_enqueue_script('pl-admin-script', plugin_dir_url(__DIR__) . 'wp-punchlist/js/plAdminScript.js', ['jquery'], null, true);
    wp_localize_script('pl-admin-script', 'localVars', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'plUrl' => getenv('PUNCHLIST_URL'),
        'qpUrl' => getenv('PUNCHLIST_URL') . '/project/create?domain=' . urlencode(site_url())
    ]);

    wp_enqueue_script('pl-create-project', plugin_dir_url(__DIR__) . 'wp-punchlist/js/plCreateProject.js', ['jquery'], null, true);
    wp_localize_script('pl-create-project', 'localVars', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'plUrl' => getenv('PUNCHLIST_URL'),
        'plApiKey' => get_user_meta(get_current_user_id(), 'pl-api-key', true),
    ]);

    wp_register_style('pl_admin_styles', plugin_dir_url(__FILE__) . 'css/styles.css', false, '1.0.0');
    wp_enqueue_style('pl_admin_styles');
}

/** 
 * Get punchlist on the sidebar
 */

function addPunchListToAdminMenu()
{
    $page = new Component(['admin'], __DIR__ . '/templates/pages/');
    $menu = new Menu($page);
    $menu->addMenuPage();
}

/** 
 * Check the integration to the Punchlist API
 */

function checkIntegration()
{
    if (check_ajax_referer('pl_check_integration')) {
        $api = new Api($_POST['api-key']);
        $res = $api->verifyIntegration();

        if (json_decode($res)->data->ping === 'pong') {
            update_user_meta(get_current_user_id(), 'pl-api-key', $_POST['api-key']);
            wp_send_json(['message' => 'success']);
        } else {
            update_user_meta(get_current_user_id(), 'pl-api-key', null);
            wp_send_json_error(['message' => 'Invalid API key'], 401);
        }
    } else {
        wp_send_json_error('Access denied', 403);
    }
}

/** 
 * Create a project through the Punchlist API
 */

function createPostPreview()
{
    if (check_ajax_referer('pl_create_project_edit_screen')) {
        if (!in_array(get_post_status($_POST['post_ID']), ['publish', 'future', 'draft', 'pending'])) {
            wp_send_json_error(['message' => 'Unable to create a Punchlist project at this time. Did you save the post?'], 400);
        }

        $preview = new Preview(get_post($_POST['post_ID']));
        $preview->createPreview();

        //$publicUrl = DSPublicPostPreview::publicPreviewUrl($_POST['post_ID']);
        $apiKey = get_user_meta(get_current_user_id(), 'pl-api-key', true);
        $api = new Api($apiKey);

        $projectName = $_POST['name'] ?? bloginfo('name') . ' ' . date('m-d-Y');
        $newProject = $api->createProject($preview->link, $projectName);

        if ($newProject->url) {
            wp_send_json(['message' => 'success', 'data' => ['url' => $newProject->url]]);
        } else {
            wp_send_json_error(['message' => 'Error creating project'], 400);
        }
    } else {
        wp_send_json_error('Access denied', 403);
    }
}

function addPageToProject()
{
    if (check_ajax_referer('pl_create_project_edit_screen')) {
        if (!in_array(get_post_status($_POST['post_ID']), ['publish', 'future', 'draft', 'pending'])) {
            wp_send_json_error(['message' => 'Unable to create a Punchlist project at this time. Did you save the post?'], 400);
        }

        $preview = new Preview(get_post($_POST['post_ID']));
        $preview->createPreview();

        //$publicUrl = DSPublicPostPreview::publicPreviewUrl($_POST['post_ID']);
        $apiKey = get_user_meta(get_current_user_id(), 'pl-api-key', true);
        $api = new Api($apiKey);

        $pageTitle = $_POST['name'] ?? bloginfo('name') . ' ' . date('m-d-Y');
        $newPage = $api->addPageToProject($preview->link, (int) $_POST['project_id'], $pageTitle);

        if ($newPage->direct_link) {
            wp_send_json(['message' => 'success', 'data' => ['url' => $newPage->direct_link]]);
        } else {
            wp_send_json_error(['message' => 'Error adding page to project'], 400);
        }
    } else {
        wp_send_json_error('Access denied', 403);
    }
}

function getProjects()
{
    if (check_ajax_referer('pl_get_projects', '_ajax_nonce')) {
        $apiKey = get_user_meta(get_current_user_id(), 'pl-api-key', true);
        $api = new Api($apiKey);
        $projects = $api->getProjects();

        if($projects) {
            wp_send_json(['message' => 'success', 'data' => json_decode($projects)]);
        } else {
            wp_send_json_error(['message' => 'Error retrieving projects']);
        }
    }
}


/**\
 * Add a PL metabox to the edit screens to allow for 
 * project creation
 */

function addPlMetaBox()
{
    $metaBox = new Component(['edit'], __DIR__ . '/templates/metaboxes/');
    $menu = new Menu($metaBox);
    $menu->addMetaBox();
}
