<div class="wrap">

    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <h2>WP x Punchlist</h2>
    <h3>To unlock the full functionality of this plugin you must get an API key from your <a href="https://app.usepunchlist.com/settings">Punchlist account.</a></h3>

    <form id="set-up-api">
        <label for="api-key">API Key
            <input type="password" name="api-key" value="<?php echo get_user_meta(get_current_user_id(), 'pl-api-key', true) ?>" />
            <input type="submit" id="submit-api-key" class="button button-primary" alt="Integrate with Punchlist API" value="Verify API Integration" />
    </form>
    <hr>
    <form id="create-project">
        <div>
            <p><small>This will create a project using the home page of the site.<br>You can create projects for draft previews by registering an API key</small></p>
            <p>
                <?php wp_nonce_field('pl-create-project', 'pl-create-project'); ?>
                <a id="create-quick-project" class="button button-primary" target="_blank" alt="Create a Quick Project">Create a Quick Project for<br><?php echo get_bloginfo('name'); ?></a>
            </p>
        </div>
    </form>
</div><!-- .wrap -->