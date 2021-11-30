<div class="wrap">
    <?php wp_nonce_field('pl_check_integration', 'plnonce'); ?>
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <h3>To unlock the full functionality of this plugin you must get an API key from your <a href="https://app.usepunchlist.com/settings">Punchlist account.</a></h3>

    <form id="set-up-api">
        <!-- <label for="api-key">API Key</label> -->
        <div>
            <input type="password" name="api-key" placeholder="API Key" />
            <input type="submit" id="submit-api-key" class="button button-primary" alt="Integrate with Punchlist API" value="Verify API Integration" />
        </div>
    </form>
</div>