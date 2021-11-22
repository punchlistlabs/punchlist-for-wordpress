<div>
    <div>
        <a class="button button-primary" id="pl-create-project-edit-screen">Create a Punchlist Project</a>
    </div>
    <p class="pl-divider"><em>or</em></p>
    <div>
        <select name="pl-add-to-project-select" id="pl-add-to-project-select">
            <option value="">Add to Existing Project</option>
        </select>
        <a class="button button-primary" id="pl-add-to-project"></a>
    </div>
    <?php
         wp_nonce_field('pl_create_project_edit_screen', 'plnonce'); 
         wp_nonce_field('pl_get_projects', 'plnonce2'); 
    ?>    
</div>