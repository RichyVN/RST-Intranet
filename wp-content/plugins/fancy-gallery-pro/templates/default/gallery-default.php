<?php
/*
Fancy Gallery Template: Default Gallery Template
Description: This template displays the thumbnail images with a link to the accordingly full size image.
Version: 1.0.1
Author: Dennis Hoppe
Author URI: http://DennisHoppe.de
*/
?>
<div class="gallery fancy-gallery <?php Echo BaseName(__FILE__, '.php') ?>" id="gallery_<?php Echo $this->gallery->id ?>">
  <?php ForEach($this->gallery->images AS $image): ?>
  <a href="<?php Echo $image->href ?>" title="<?php Echo HTMLSpecialChars($image->title) ?>" class="<?php Echo $this->gallery->attributes->link_class ?>">
    <img <?php ForEach ($image->attributes AS $attribute => $value) PrintF('%s="%s" ', $attribute, HTMLSpecialChars(Strip_Tags($value))); ?> >
  </a>
  <?php EndForEach ?>
  <div class="clear"></div>
</div>