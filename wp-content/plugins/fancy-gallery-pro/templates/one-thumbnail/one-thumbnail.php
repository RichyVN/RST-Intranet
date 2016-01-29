<?php
/*
Fancy Gallery Template: One Thumbnail only
Description: This template displays the gallery thumbnail and enables the user to navigate through all images.
Version: 1.0.0
Author: Dennis Hoppe
Author URI: http://DennisHoppe.de
*/

If ($thumb_id = Get_Post_Thumbnail_ID($this->gallery->id)){
  // Try to find this image in the gallery
  ForEach ($this->gallery->images AS $index => $image){
    If ($thumb_id == $image->ID){
      $thumb = $image;
      Unset($this->gallery->images[$index]);
      Break;
    }
  }
}
Else $thumb = Array_Shift($this->gallery->images);

?>
<div class="gallery fancy-gallery <?php Echo BaseName(__FILE__, '.php') ?>" id="gallery_<?php Echo $this->gallery->id ?>">
  <a href="<?php Echo $thumb->href ?>" title="<?php Echo HTMLSpecialChars($thumb->title) ?>" class="<?php Echo $this->gallery->attributes->link_class ?>">
     <img <?php ForEach ($thumb->attributes AS $attribute => $value) PrintF('%s="%s" ', $attribute, HTMLSpecialChars(Strip_Tags($value))); ?> >
  </a>
  <?php ForEach($this->gallery->images AS $image) : ?>
    <a href="<?php Echo $image->href ?>" title="<?php Echo HTMLSpecialChars($image->title) ?>"></a>
  <?php EndForEach ?>
</div>