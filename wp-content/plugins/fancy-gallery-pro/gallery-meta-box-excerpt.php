<h3>
  <input type="radio" name="<?php Echo $this->Field_Name('excerpt_type') ?>" id="excerpt_type_images" value="images" <?php Checked($this->Get_Gallery_Meta('excerpt_type'), 'images') ?> />
  <label for="excerpt_type_images"><?php Echo $this->t('Random Images') ?></label>
</h3>
<div class="togglebox">
  <p><?php Echo $this->t('In this mode the excerpt of the gallery will display a set of random images.') ?></p>

  <table>
  <tr>
    <td><label for="<?php Echo $this->Field_Name('excerpt_image_number') ?>"><?php Echo $this->t('Images per Excerpt:') ?></label></td>
    <td><input type="text" name="<?php Echo $this->Field_Name('excerpt_image_number') ?>" id="<?php Echo $this->Field_Name('excerpt_image_number') ?>" value="<?php Echo HTMLSpecialChars($this->Get_Gallery_Meta('excerpt_image_number')) ?>" size="4" /></td>
  </tr>
  <tr>
    <td><label for="<?php Echo $this->Field_Name('excerpt_thumb_width') ?>"><?php Echo $this->t('Thumbnail width:') ?></label></td>
    <td><input type="text" name="<?php Echo $this->Field_Name('excerpt_thumb_width') ?>" id="<?php Echo $this->Field_Name('excerpt_thumb_width') ?>" value="<?php Echo HTMLSpecialChars($this->Get_Gallery_Meta('excerpt_thumb_width')) ?>" size="4" />px</td>            
  </tr>  
  <tr>
    <td><label for="<?php Echo $this->Field_Name('excerpt_thumb_height') ?>"><?php Echo $this->t('Thumbnail height:') ?></label></td>
    <td><input type="text" name="<?php Echo $this->Field_Name('excerpt_thumb_height') ?>" id="<?php Echo $this->Field_Name('excerpt_thumb_height') ?>" value="<?php Echo HTMLSpecialChars($this->Get_Gallery_Meta('excerpt_thumb_height')) ?>" size="4" />px</td>            
  </tr>
  </table>
  <p>
    <input type="checkbox" name="<?php Echo $this->Field_Name('excerpt_thumb_grayscale') ?>" id="<?php Echo $this->Field_Name('excerpt_thumb_grayscale') ?>" value="yes" <?php Checked($this->Get_Gallery_Meta('excerpt_thumb_grayscale'), 'yes') ?> />
    <label for="<?php Echo $this->Field_Name('excerpt_thumb_grayscale') ?>"><?php Echo $this->t('Convert thumbnails to grayscale.') ?></label>
  </p>            
  <p>
    <input type="checkbox" name="<?php Echo $this->Field_Name('excerpt_thumb_negate') ?>" id="<?php Echo $this->Field_Name('excerpt_thumb_negate') ?>" value="yes" <?php Checked($this->Get_Gallery_Meta('excerpt_thumb_negate'), 'yes') ?> />
    <label for="<?php Echo $this->Field_Name('excerpt_thumb_negate') ?>"><?php Echo $this->t('Negate the thumbnails.') ?></label>            
  </p>

  <h4><?php Echo $this->t('Template') ?></h4>
  <p><?php Echo $this->t('Please choose a template to display the excerpt of this gallery.') ?></p>  
  <?php ForEach ( $this->Get_Template_Files() AS $name => $properties ) : ?>
  <p>
    <input type="radio" name="<?php Echo $this->Field_Name('excerpt_template') ?>" id="excerpt_template_<?php Echo Sanitize_Title($properties['file']) ?>" value="<?php Echo HTMLSpecialChars($properties['file']) ?>"
      <?php Checked($this->Get_Gallery_Meta('excerpt_template'), $properties['file']) ?>
      <?php Checked(!$this->Get_Gallery_Meta('excerpt_template') && $properties['file'] == $this->Get_Default_Template()) ?> />
    <label for="excerpt_template_<?php Echo Sanitize_Title($properties['file']) ?>">
    <?php If (Empty($properties['name'])) : ?>
      <em><?php Echo $properties['file'] ?></em>
    <?php Else : ?>
      <strong><?php Echo $properties['name'] ?></strong>
    <?php EndIf; ?>
    </label>
    <?php If ($properties['version']) : ?> (<?php Echo $properties['version'] ?>)<?php Endif; ?>
    <?php If ($properties['author'] && !$properties['author_uri'] ) : ?>
      <?php Echo $this->t('by') ?> <?php Echo $properties['author'] ?>
    <?php ElseIf ($properties['author'] && $properties['author_uri'] ) : ?>
      <?php Echo $this->t('by') ?> <a href="<?php Echo $properties['author_uri'] ?>" target="_blank"><?php Echo $properties['author'] ?></a>
    <?php Endif; ?>
    <?php If ($properties['description']) : ?><br /><?php Echo $properties['description']; Endif; ?>
  </p>
  <?php EndForEach; ?>

</div>

<h3>
  <input type="radio" name="<?php Echo $this->Field_Name('excerpt_type') ?>" id="excerpt_type_text" value="text" <?php Checked($this->Get_Gallery_Meta('excerpt_type'), 'text') ?> />
  <label for="excerpt_type_text"><?php Echo $this->t('Text Excerpt') ?></label>
</h3>
<div class="togglebox">
  <p>
    <label class="screen-reader-text" for="excerpt"><?php _e('Excerpt') ?></label>
    <textarea rows="1" cols="40" name="excerpt" tabindex="6" id="excerpt"><?php echo $post->post_excerpt; // textarea_escaped ?></textarea>
  </p>
  <p><?php _e('Excerpts are optional hand-crafted summaries of your content that can be used in your theme. <a href="http://codex.wordpress.org/Excerpt" target="_blank">Learn more about manual excerpts.</a>'); ?></p>
</div>
