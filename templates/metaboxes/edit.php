<div>
    <?php
        if ($plUrl = get_post_meta(get_the_id(), 'pl-project-url', true)) {
    ?>
        <a class="button button-secondary" href="<?php echo $plUrl ?>" target="_blank">Go To Punchlist Project</a>
    <?php } 
         wp_nonce_field('pl-create-project-edit-screen', 'plnonce'); 
    ?>
    <a class="button button-primary" id="pl-create-project-edit-screen">Create a Punchlist Project</a>
</div>