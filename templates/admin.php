<div class="wrap">

    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <form id="create-project">
        <div id="">
            <h2>WP x Punchlist</h2>
            <!-- <div class="">
                <p>
                    <label>What theme would you like to create a project for?</label>
                    <br />
                    <select name="theme">
                        <?php
                        foreach (wp_get_themes() as $id => $theme) {
                            echo "<option value=\"{$id}\">{$theme->Name}</option>";
                        }
                        ?>
                    </select>
                </p>
        </div> -->

            <?php
            //submit_button('Create Punchlist Project for ' . get_bloginfo('name'));
            wp_nonce_field('pl-create-project', 'pl-create-project');
            ?>

            <a id="create-quick-project" class="button button-primary" target="_blank" alt="Create a Punchlist Project">Create Punchlist Project for <?php echo get_bloginfo('name'); ?></a>


    </form>

    <h3>To unlock the full functionality of this plugin you must get an API key from your <a href="https://app.usepunchlist.com/settings">Punchlist account.</a></h3>

</div><!-- .wrap -->