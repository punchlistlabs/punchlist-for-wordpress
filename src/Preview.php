<?php

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

        $this->link = get_site_url() . '/?' . http_build_query($args);
    }


    public static function makePostViewable($posts)
    {
        // Remove the filter again, otherwise it will be applied to other queries too.
        remove_filter('posts_results', array(__CLASS__, 'makePostViewable'), 10);

        if (empty($posts)) {
            return $posts;
        }

        $postId = (int) $posts[0]->ID;

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
        if (isset($_GET['pluid']) && isset($_GET['post_to_preview']) && self::verifyPreviewCode($_GET['post_to_preview'], $_GET['pluid'])) {
            add_filter('posts_results', [__CLASS__, 'makePostViewable'], 10, 2);
        }
    }

    public static function verifyPreviewCode($postId, $pluid)
    {
        return (bool) strstr(get_post_meta($postId, 'pl_preview_link', true), $pluid);
    }
}
