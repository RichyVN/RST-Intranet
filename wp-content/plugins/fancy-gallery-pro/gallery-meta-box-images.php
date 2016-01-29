<script type="text/javascript">
var $jFrame;
var $Frame;

jQuery(function($){
  // Start resizising library iframe
  $jFrame = jQuery('iframe#fancy-gallery-library-window:first');
  $Frame = $jFrame.get(0);
  resize_library_iframe();
});

function resize_library_iframe(){
  if ($Frame.contentWindow.document.documentElement){
    if ( $Frame.contentWindow.document.documentElement.scrollHeight == $jFrame.attr('height') ){
      // Size the frame down until the scrollHeight is higher than the frame height
      while ( $Frame.contentWindow.document.documentElement.scrollHeight == $jFrame.attr('height') && $jFrame.attr('height') > 250 ){
        $jFrame.attr('height', parseInt($Frame.contentWindow.document.documentElement.scrollHeight * 0.9) );
      }
    }
    else if ($Frame.contentWindow.document.documentElement.scrollHeight > $jFrame.attr('height')){
      // Size the frame up!!!
      $jFrame.attr('height', $Frame.contentWindow.document.documentElement.scrollHeight - 1 );
    }
  }

  // Next run in 0.400sec.
  window.setTimeout('resize_library_iframe()', 400);
}
</script>

<iframe id="fancy-gallery-library-window"
        src="<?php Echo Add_Query_Arg(Array('post_id' => $GLOBALS['post']->ID, 'type' => 'image', 'tab' => 'gallery', 'strip_tabs' => 'true'), Admin_url('media-upload.php')) ?>"
        name="Fancy_Gallery_Images_Frame"
        width="100%"
        height="250"
        scrolling="no"
        marginheight="0"
        marginwidth="0"
        frameborder="0">
</iframe>
