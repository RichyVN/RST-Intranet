<p>
  <?php Echo $this->t('Usually posts have optional hand-crafted summaries of their content. But galleries do not.') ?>
  <?php Echo $this->t('Gallery excerpts are randomly chosen images from a gallery.') ?>
  <i><?php Echo $this->t('These settings may be overridden for individual galleries.') ?></i>
</p>

<p>
  <input type="checkbox" name="disable_excerpts" id="disable_excerpts" value="yes" <?php Checked ($this->get_option('disable_excerpts'), 'yes') ?> >
  <label for="disable_excerpts"><?php Echo $this->t('Do not generate excerpts out of random gallery images.') ?></label>
</p>

<table>
<tr>
  <td><label for="excerpt_image_number"><?php Echo $this->t('Images per Excerpt:') ?></label></td>
  <td><input type="text" name="excerpt_image_number" id="excerpt_image_number" value="<?php Echo HTMLSpecialChars($this->Get_Option('excerpt_image_number')) ?>" size="4"></td>
  </tr>
</tr>
<tr>
  <td><label for="excerpt_thumb_width"><?php Echo $this->t('Thumbnail width:') ?></label></td>
  <td><input type="text" name="excerpt_thumb_width" id="excerpt_thumb_width" value="<?php Echo HTMLSpecialChars($this->Get_Option('excerpt_thumb_width')) ?>" size="4">px</td>
</tr>
<tr>
  <td><label for="excerpt_thumb_height"><?php Echo $this->t('Thumbnail height:') ?></label></td>
  <td><input type="text" name="excerpt_thumb_height" id="excerpt_thumb_height" value="<?php Echo HTMLSpecialChars($this->Get_Option('excerpt_thumb_height')) ?>" size="4">px</td>
</tr>
</table>