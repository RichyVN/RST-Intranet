<?php

// User capabilities
$arr_capabilities = Array(
  'edit_galleries' => $this->t('Edit and create (own) Galleries'),
  'edit_others_galleries' => $this->t('Edit others Galleries'),
  'edit_private_galleries' => $this->t('Edit (own) private Galleries'),
  'edit_published_galleries' => $this->t('Edit (own) published Galleries'),

  'delete_galleries' => $this->t('Delete (own) Galleries'),
  'delete_private_galleries' => $this->t('Delete (own) private Galleries'),
  'delete_published_galleries' => $this->t('Delete (own) published Galleries'),
  'delete_others_galleries' => $this->t('Delete others Galleries'),

  'publish_galleries' => $this->t('Publish Galleries'),
  'read_private_galleries' => $this->t('View (others) private Galleries'),

  #'manage_gallery_categories' => $this->t('Manage Gallery Categories'),
  
);

// Taxonomies
ForEach ( (Array) $this->arr_taxonomies AS $taxonomie => $tax_args )
  $arr_capabilities[ $tax_args['capabilities']['manage_terms'] ] = SPrintF($this->t('Manage %s'), $tax_args['labels']['name']);

// Show the user roles
ForEach ($GLOBALS['wp_roles']->roles AS $role_name => $arr_role) : ?>
  <h4><?php Echo Translate_User_Role($arr_role['name']) ?></h4>
  
  <?php ForEach ($arr_capabilities AS $capability => $caption) : ?>

    <div class="capability-selection">
      <span class="caption"><?php Echo $caption ?>:</span>

      <input type="radio" name="capabilities[<?php Echo $role_name ?>][<?php Echo $capability ?>]" id="capabilities[<?php Echo $role_name ?>][<?php Echo $capability ?>][yes]" value="yes" <?php Checked(IsSet($arr_role['capabilities'][$capability])) ?> />
      <label for="capabilities[<?php Echo $role_name ?>][<?php Echo $capability ?>][yes]"><?php _e('Yes') ?></label>
      
      <input type="radio" name="capabilities[<?php Echo $role_name ?>][<?php Echo $capability ?>]" value="no" id="capabilities[<?php Echo $role_name ?>][<?php Echo $capability ?>][no]" <?php Checked(!IsSet($arr_role['capabilities'][$capability])) ?> />
      <label for="capabilities[<?php Echo $role_name ?>][<?php Echo $capability ?>][no]"><?php _e('No') ?></label>
    </div>    

  <?php EndForEach; ?>

<?php EndForEach; ?>