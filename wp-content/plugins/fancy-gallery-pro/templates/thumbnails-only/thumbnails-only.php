<?php
/*
Fancy Gallery Template: Thumbnails only
Description: This template displays the thumbnail images without any links.
Version: 1.0.1
Author: Dennis Hoppe
Author URI: http://DennisHoppe.de
*/
?>
<div class="gallery fancy-gallery <?php Echo BaseName(__FILE__, '.php') ?>" id="gallery_<?php Echo $this->gallery->id ?>">
  <?php ForEach($this->gallery->images AS $image): ?>
  <img <?php ForEach ($image->attributes AS $attribute => $value) PrintF('%s="%s" ', $attribute, HTMLSpecialChars(Strip_Tags($value))); ?> >
  <?php EndForEach ?>
  <div class="clear"></div>
</div>