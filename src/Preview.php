<?php

/**
 * Plugin Name: Public Post Preview
 * Version: 2.9.3
 * Description: Allow anonymous users to preview a post before it is published.
 * Author: Dominik Schilling
 * Author URI: https://dominikschilling.de/
 * Plugin URI: https://dominikschilling.de/wp-plugins/public-post-preview/en/
 * Text Domain: public-post-preview
 * Requires at least: 5.0
 * Tested up to: 5.7
 * Requires PHP: 5.6
 * License: GPLv2 or later
 *
 * Previously (2009-2011) maintained by Jonathan Dingman and Matt Martz.
 *
 *  Copyright (C) 2012-2021 Dominik Schilling
 *
 *  This program is free software; you can redistribute it and/or
 *  modify it under the terms of the GNU General Public License
 *  as published by the Free Software Foundation; either version 2
 *  of the License, or (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

namespace Punchlist;

class Preview
{
    public $post;
    public $link;

    public function __construct($post)
    {
        $this->post = $post;

        add_filter('query_vars', array(__CLASS__, 'addQueryVars'));
        // Add the query var to WordPress SEO by Yoast whitelist.
        add_filter('wpseo_whitelist_permalink_vars', array(__CLASS__, 'addQueryVars'));
    }
    public function createPreview()
    {
        $this->createLink();
        update_post_meta($this->post->ID, 'pl_preview_link', $this->link);
    }

    public function createLink()
    {
        if ($this->post->post_type === 'page') {
            $args = ['page_id' => $this->post->ID];
        } elseif ($this->post->post_type === 'post') {
            $args = ['p' => $this->post->ID];
        } else {
            $args = array(
                'p'         => $this->post->ID,
                'post_type' => $this->post->post_type,
            );
        }

        $args['preview'] = true;
        $args['pluid'] = uniqid();
        $args['post_to_preview'] = $this->post->ID;

        $this->link = get_site_url() . '?' . http_build_query($args);
    }


    public static function makePostViewable($posts)
    {
        // Remove the filter again, otherwise it will be applied to other queries too.
        remove_filter('posts_results', array(__CLASS__, 'makePostViewable'), 10);

        if (empty($posts)) {
            return $posts;
        }

        $postId = (int) $posts[0]->ID;

        // If the post has gone live, redirect to its proper permalink.
        if (in_array(get_post_status($postId), ['publish', 'private'])) {
            wp_safe_redirect(get_permalink($postId), 301);
            exit;
        }

        // Set post status to publish so that it's visible.
        $posts[0]->post_status = 'publish';

        // Disable comments and pings for this post.
        add_filter('comments_open', '__return_false');
        add_filter('pings_open', '__return_false');
        add_filter('wp_link_pages_link', array(__CLASS__, 'filter_wp_link_pages_link'), 10, 2);

        return $posts;
    }

    public static function showPreview($query)
    {
        if ($_GET['pluid'] && $_GET['post_to_preview'] && self::verifyPreviewCode($_GET['post_to_preview'], $_GET['pluid'])) {
            add_filter('posts_results', [__CLASS__, 'makePostViewable'], 10, 2);
        }
    }

    public static function verifyPreviewCode($postId, $pluid)
    {
        return (bool) strstr(get_post_meta($postId, 'pl_preview_link', true), $pluid);
    }
}
