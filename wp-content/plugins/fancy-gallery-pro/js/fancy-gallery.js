jQuery(function(){
  var FG = FANCYGALLERY; // Pointer to our global object

  // group gallery items
  jQuery('div.fancy-gallery a')
  .each(function(){
    var $this = jQuery(this);
    $this.attr('rel', $this.parents('.fancy-gallery').attr('id'));
  });

  // Added Image descriptions
  if (typeof(IMAGETITLES) == 'object'){
    for (var image_id in IMAGETITLES){
      var image_title = IMAGETITLES[image_id];
      jQuery('img.wp-image-' + image_id).attr('title', image_title);
    }
    IMAGETITLES = image_id = image_title = null;
  }

  // Patch the linked images
  jQuery('a[href$=".jpg"], a[href$=".jpeg"], a[href$=".png"], a[href$=".gif"], a[href$=".bmp"], a[href$=".wbmp"]').each(function(){

    // shorter access path
    var $lnk = jQuery(this);
    var $img = $lnk.find('img');

    if (FG.associate_single_images == 'yes'){ // Add a rel attribute
      if ($lnk.attr('rel') == '' || $lnk.attr('rel') == undefined)
        $lnk.attr('rel', 'single-image');
    }

    if (FG.use_as_image_title == 'alt_text'){ // Copy the alternate texts
      $img.attr('title', $img.attr('alt'));
    }
    else if (FG.use_as_image_title == 'caption'){ // Copy the captions
      if (caption = $lnk.parent('.wp-caption').find('.wp-caption-text').html())
        $img.attr('title', caption);
    }

    // Convert images in inline-blocks
    if (FG.change_image_display == 'yes')
      $img.css('display', 'inline-block');

    // Copy the title tag from link to img
    if ($lnk.attr('title') == '' || $lnk.attr('title') == undefined)
      $lnk.attr('title', $img.attr('title'));

    // Add the fancybox class
    $lnk.addClass('fancybox');
  });

  // Group single images by post
  if (FG.group_single_images_by_post == 'yes'){
    // group images by post
    var unit_counter = 0;
    jQuery('.fancy-gallery-content-unit').each(function(){
      var $unit = jQuery(this);
      unit_counter++;
      $unit
      .find('a[rel=single-image]')
      .each(function(){
        jQuery(this).attr('rel', 'single-image-unit-' + unit_counter);
      });
    });
  }

  // Add the light box effect to the linked images
  jQuery('a.fancybox')
  .unbind('click')
  .fancybox({
    loop: (FG.loop == 'yes'),
    padding: parseInt(FG.padding),
    closeBtn: (FG.hide_close_button != 'yes'),
    autoPlay: (FG.auto_play == 'yes'),
    playSpeed: parseInt(FG.play_speed),
    openEffect: FG.open_effect,
    openSpeed: parseInt(FG.open_speed),
    closeEffect: FG.close_effect,
    closeSpeed: parseInt(FG.close_speed),
    nextEffect: FG.change_effect,
    nextSpeed: parseInt(FG.change_speed),
    prevEffect: FG.change_effect,
    prevSpeed: parseInt(FG.change_speed),
    helpers: {
			title: {
        type: FG.title_position
      },
			buttons: {
        position: FG.controls_position
      },
      thumbs : {
        width: 50,
        height: 50,
        position: FG.thumbs_position // 'top' or 'bottom'
      }
		}
  });

});