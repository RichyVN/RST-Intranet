<p>
  <?php Echo $this->t('These settings affect the appearance of the thumbnail images of this gallery.') ?>
</p>

<p>
  <label for="<?php Echo $this->Field_Name('thumb_width') ?>"><?php Echo $this->t('Thumbnail width:') ?></label>
  <input type="text" name="<?php Echo $this->Field_Name('thumb_width') ?>" id="<?php Echo $this->Field_Name('thumb_width') ?>" value="<?php Echo HTMLSpecialChars($this->Get_Gallery_Meta('thumb_width')) ?>" size="4" />px            
</p>

<p>
  <label for="<?php Echo $this->Field_Name('thumb_height') ?>"><?php Echo $this->t('Thumbnail height:') ?></label>
  <input type="text" name="<?php Echo $this->Field_Name('thumb_height') ?>" id="<?php Echo $this->Field_Name('thumb_height') ?>" value="<?php Echo HTMLSpecialChars($this->Get_Gallery_Meta('thumb_height')) ?>" size="4" />px            
</p>

<p>
  <input type="checkbox" name="<?php Echo $this->Field_Name('thumb_grayscale') ?>" id="<?php Echo $this->Field_Name('thumb_grayscale') ?>" value="yes" <?php Checked($this->Get_Gallery_Meta('thumb_grayscale'), 'yes') ?> />
  <label for="<?php Echo $this->Field_Name('thumb_grayscale') ?>"><?php Echo $this->t('Convert thumbnails to grayscale.') ?></label>            
</p>

<p>
  <input type="checkbox" name="<?php Echo $this->Field_Name('thumb_negate') ?>" id="<?php Echo $this->Field_Name('thumb_negate') ?>" value="yes" <?php Checked($this->Get_Gallery_Meta('thumb_negate'), 'yes') ?> />
  <label for="<?php Echo $this->Field_Name('thumb_negate') ?>"><?php Echo $this->t('Negate the thumbnails.') ?></label>            
</p>
