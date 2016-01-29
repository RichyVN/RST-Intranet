<?php If (!$this->get_option('deactivate_archive')) : ?>
<p>
  <?php PrintF($this->t('The Archive link for your Galleries is: <div><code><a href="%1$s" target="_blank">%1$s</a></code></div>'), Get_Post_Type_Archive_Link($this->gallery_post_type)) ?>
</p>
<p>
  <?php PrintF($this->t('The Archive Feed for your Galleries is: <div><code><a href="%1$s" target="_blank">%1$s</a></code></div>'), Get_Post_Type_Archive_Feed_Link($this->gallery_post_type)) ?>
</p>
<?php EndIf ?>

<h4><?php Echo $this->t('Deactivate Archive') ?></h4>
<p>
  <input type="checkbox" name="deactivate_archive" id="deactivate_archive" value="yes" <?php Checked ($this->get_option('deactivate_archive'), 'yes') ?> />
  <label for="deactivate_archive"><?php Echo $this->t('Deactivate the archive feature.') ?></label><br />
  <small><?php Echo $this->t('If you tick this box visitors will no more be able to navigate through the gallery archive.') ?></small>
</p>
