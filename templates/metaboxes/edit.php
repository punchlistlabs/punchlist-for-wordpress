<div>
    <div>
        <select name="pl-add-to-project-select" id="pl-add-to-project-select">
            <option value="">Select Project</option>
        </select>
        <a class="button button-primary" id="pl-add-to-project">Add to Punchlist Project</a>
    </div>
    <!-- <?php
        if ($plUrl = get_post_meta(get_the_id(), 'pl-project-url', true)) {
    ?>
        <a class="button button-secondary" href="<?php echo $plUrl ?>" target="_blank">Go To Punchlist Project</a>
        -->
    <?php } 
         wp_nonce_field('pl-create-project-edit-screen', 'plnonce'); 
         wp_nonce_field('pl_get_projects', 'plnonce2'); 
    ?>
     
    <p class="pl-divider"><em>or</em></p>
    <div>
        <a class="button button-primary" id="pl-create-project-edit-screen">Create a Punchlist Project</a>
    </div>
</div>