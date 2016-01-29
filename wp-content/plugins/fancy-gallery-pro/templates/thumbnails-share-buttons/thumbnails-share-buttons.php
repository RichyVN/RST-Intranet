<?php
/*
Fancy Gallery Template: Thumbnails / Image Title with Share buttons
Description: This template displays the thumbnail images and the image title with share buttons.
Version: 1.0.1
Author: Dennis Hoppe
Author URI: http://DennisHoppe.de
*/

// Read base url
$base_url = SPrintF('%s/%s', Get_Bloginfo('wpurl'), SubStr(RealPath(DirName(__FILE__)), Strlen(ABSPATH)));
$base_url = Str_Replace("\\", '/', $base_url); // Windows Workaround

?>
<div class="gallery fancy-gallery <?php Echo BaseName(__FILE__, '.php') ?>" id="gallery_<?php Echo $this->gallery->id ?>"><?php
ForEach($this->gallery->images AS $image){

  // Build <img> Tags
  $img_code = '<img';
  ForEach ($image->attributes AS $attribute => $value)
    $img_code .= SPrintF(' %s="%s"', $attribute, HTMLSpecialChars(Strip_Tags($value)));
  $img_code .= '>';

  // Build FB share button
  $fb_code = SPrintF(
    '<a href="%1$s" title="%2$s" class="%3$s" target="_blank">%4$s</a>',
    SPrintF('https://www.facebook.com/sharer/sharer.php?u=%s', $image->href),
    HTMLSpecialChars($image->title),
    $this->gallery->attributes->link_class,
    SPrintF('<img src="%s/facebook-button.png" alt="Share it" height="20">', $base_url)
  );

  // Build Pintarest share button
  $pinterest_code = SPrintF(
    '<a href="%1$s" title="%2$s" class="%3$s" target="_blank">%4$s</a>',
    SPrintF('https://pinterest.com/pin/create/button/?url=%1$s&media=%2$s', Get_Permalink($this->gallery->id), $image->href),
    HTMLSpecialChars($image->title),
    $this->gallery->attributes->link_class,
    SPrintF('<img src="%s/pinterest-button.png" alt="Pin it" height="20">', $base_url)
  );

  // Build Twitter share button
  $twitter_code = SPrintF(
    '<a href="%1$s" title="%2$s" class="%3$s" target="_blank">%4$s</a>',
    SPrintF('https://twitter.com/share?url=%1$s', $image->href),
    HTMLSpecialChars($image->title),
    $this->gallery->attributes->link_class,
    SPrintF('<img src="%s/twitter-button.png" alt="Tweet it" height="20">', $base_url)
  );

  // Build <a> Tag for the image
  $link_code = SPrintF(
    '<a href="%1$s" title="%2$s" class="%3$s">%4$s</a>',
    $image->href,
    HTMLSpecialChars(SPrintF('%s %s %s %s', $image->title, $fb_code, $pinterest_code, $twitter_code )),
    $this->gallery->attributes->link_class,
    $img_code
  );

  Echo $link_code;
}
?></div>