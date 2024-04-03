<?php

/*
Plugin Name: Punchlist
Plugin URI: https://punchlist.com/integrations?utm_source=wordpress-directory&utm_medium=web
Description: Harness the magic of Punchlist from the WP Dashboard
Author: Punchlist Labs
Version: 1.4.5
Author URI: https://punchlist.com
Credits: This plugin borrows heavily from Public Post Preview plugin but Dominik Schilling. WP won't allow
two headers on the same plugin, but let's give credit where it's due.
License: GPLv2

Copyright (C) 2024 Marketwake.

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

use Punchlist\Api;
use Punchlist\Component;
use Punchlist\Menu;
use Punchlist\Preview;

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

require __DIR__ . '/vendor/autoload.php';

putenv('PUNCHLIST_URL=https://app.punchlist.com/api/public/v1');
putenv('PUNCHLIST_SCRIPT=https://static.usepunchlist.com/js/punchlist.min.js?121521');

if (!is_admin()) {
    add_action('pre_get_posts', ['Punchlist\Preview', 'showPreview']);
    add_action('wp_enqueue_scripts', 'punchlistLoadScriptsAndStyles');
} else {
    add_action('admin_enqueue_scripts', 'punchlistAdminLoadScriptsAndStyles');
    add_action('admin_menu', 'punchlistAddToMenu');
    add_action('wp_ajax_pl_check_integration', 'punchlistCheckIntegration');
    add_action('wp_ajax_pl_get_projects', 'punchlistGetProjects');
    add_action('wp_ajax_pl_create_project_edit_screen', 'punchlistCreatePostPreview');
    add_action('wp_ajax_pl_add_to_project_edit_screen', 'punchlistAddPageToProject');
    add_action('add_meta_boxes', 'punchlistAddMetaBox');
    add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'punchlistSettingsLink');
}

function punchlistSettingsLink($links)
{
    $links[] = '<a href="' .
        admin_url('admin.php?page=punchlist-admin-page') .
        '">' . __('Settings') . '</a>';
    return $links;
}

function punchlistLoadScriptsAndStyles()
{
    wp_enqueue_script('punchlist', getenv('PUNCHLIST_SCRIPT'), null, '2.4', true);
}

function punchlistAdminLoadScriptsAndStyles()
{
    wp_enqueue_script('pl-admin-script', plugin_dir_url(__DIR__) . 'punchlist/js/plAdminScript.js', ['jquery'], null, true);
    wp_localize_script('pl-admin-script', 'localVars', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'plUrl' => getenv('PUNCHLIST_URL')
    ]);

    wp_enqueue_script('pl-create-project', plugin_dir_url(__DIR__) . 'punchlist/js/plCreateProject.js', ['jquery'], null, true);
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

function punchlistAddToMenu()
{
    $page = new Component(['admin'], __DIR__ . '/templates/pages/');
    $menu = new Menu($page);
    $menu->addMenuPage();
}

/**
 * Check the integration to the Punchlist API
 */

function punchlistCheckIntegration()
{
    
    if (check_ajax_referer('pl_check_integration')) {
        
        $apiKey = sanitize_meta('pl-api-key', sanitize_text_field($_POST['api-key']), 'user');
        
        $api = new Api($apiKey);
        
        if ($api->verifyIntegration()) {
            update_user_meta(get_current_user_id(), 'pl-api-key', $apiKey);
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

function punchlistCreatePostPreview()
{
    if (check_ajax_referer('pl_create_project_edit_screen')) {
        $postId = is_numeric($_POST['post_ID']) ? (int) $_POST['post_ID'] : null;
        if ($postId) {
            if (!in_array(get_post_status($postId), ['publish', 'future', 'draft', 'pending'])) {
                wp_send_json_error(['message' => 'Unable to create a Punchlist project at this time. Did you save the post?'], 400);
            }

            $preview = new Preview(get_post($postId));
            $preview->createPreview();

            $apiKey = get_user_meta(get_current_user_id(), 'pl-api-key', true);
            $api = new Api($apiKey);

            $projectName = sanitize_text_field($_POST['name']) ? sanitize_text_field($_POST['name']) : 'Unnamed WP Project' . ' ' . date('m-d-Y');
            $newProject = $api->createProject($preview->link, $projectName);
            
            if ($newProject['share_link']) {
                wp_send_json(['message' => 'success', 'data' => ['url' => $newProject['share_link']]]);
            } else {
                wp_send_json_error(['message' => 'Error creating project'], 400);
            }
        } else {
            wp_send_json_error(['message' => 'Invalid post ID'], 400);
        }
    } else {
        wp_send_json_error('Access denied', 403);
    }
}

function punchlistAddPageToProject()
{
    if (check_ajax_referer('pl_create_project_edit_screen')) {
        $postId = is_numeric($_POST['post_ID']) ? (int) $_POST['post_ID'] : null;
        
        if ($postId) {
            if (!in_array(get_post_status($postId), ['publish', 'future', 'draft', 'pending'])) {
                wp_send_json_error(['message' => 'Unable to create a Punchlist project at this time. Did you save the post?'], 400);
            }
            
            $preview = new Preview(get_post($postId));
            $preview->createPreview();
            $apiKey = get_user_meta(get_current_user_id(), 'pl-api-key', true);
            $api = new Api($apiKey);

            $pageTitle = sanitize_text_field($_POST['name']) ?: bloginfo('name') . ' ' . date('m-d-Y');
            $projectId = $_POST['project_id'];
            $newPage = $api->addPageToProject($preview->link, $projectId, $pageTitle);
         
            if ($newPage['share_link']) {
                wp_send_json(['message' => 'success', 'data' => ['url' => $newPage['share_link']]]);
            } else {
                wp_send_json_error(['message' => 'Error adding page to project'], 400);
            }
        } else {
            wp_send_json_error(['message' => 'Invalid post ID'], 400);
        }
    } else {
        wp_send_json_error('Access denied', 403);
    }
}

function punchlistGetProjects()
{
    if (check_ajax_referer('pl_get_projects', '_ajax_nonce')) {
        $apiKey = get_user_meta(get_current_user_id(), 'pl-api-key', true);
        $api = new Api($apiKey);
        $projects = $api->getProjects();

        if ($projects) {
            return wp_send_json(['message' => 'success', 'data' => $projects]);
        }
        
        return wp_send_json_error(['message' => 'Error retrieving projects']);
    }
}

/**\
 * Add a PL metabox to the edit screens to allow for
 * project creation
 */

function punchlistAddMetaBox()
{
    $metaBox = new Component(['edit'], __DIR__ . '/templates/metaboxes/');
    $menu = new Menu($metaBox);
    $menu->addMetaBox();
}
