<h4><?php Echo $this->t('Single images') ?></h4>
<p>
  <input type="checkbox" name="associate_single_images" value="yes" id="associate_single_images" <?php Checked ($this->get_option('associate_single_images'), 'yes') ?> />
  <label for="associate_single_images"><?php Echo $this->t('Consolidate single images in a post to one group so the user can navigate through them.') ?></label><br />
  <small><?php Echo $this->t('So you will have an image navigation for all images.') ?></small>
</p>
<p>
  <input type="checkbox" name="group_single_images_by_post" value="yes" id="group_single_images_by_post" <?php Checked ($this->get_option('group_single_images_by_post'), 'yes') ?> />
  <label for="group_single_images_by_post"><?php Echo $this->t('Group single images by post.') ?></label><br />
  <small><?php Echo $this->t('Will create different navigation paths for different posts.') ?></small>
</p>


<h4><?php Echo $this->t('Image appearance') ?></h4>
<p>
  <input type="checkbox" name="change_image_display" id="change_image_display" value="yes" <?php Checked ($this->get_option('change_image_display'), 'yes') ?> />
  <label for="change_image_display"><?php Echo $this->t('Convert images to inline elements.') ?></label><br />
  <small><?php Echo $this->t('Tick this box if your images are among each other.') ?></small>
</p>
