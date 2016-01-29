<label class="screen-reader-text" for="post_author_override"><?php $this->t('Owner'); ?></label>

<?php
Global $post;
WP_DropDown_Users(Array(
  'name' => 'post_author_override',
  'selected' => Empty($post->ID) ? $user_ID : $post->post_author,
  'include_selected' => True )
);
?>

(<small><?php Echo $this->t('Changes the owner of this gallery.') ?></small>)