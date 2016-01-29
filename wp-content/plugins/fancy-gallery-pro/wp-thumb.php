<?php /*

WordPress Thumb Generator

Author: Dennis Hoppe
Web: http://DennisHoppe.de
Mail: Mail@DennisHoppe.de


The WP Thumb Generator Script creates thumbnail images depending on the attachment id
of a WordPress image. Images can be resampled in aspect ratio or cropped. There is
a build in cache routine which is self cleaning every seven days. You haven't to
worry about it.


Parameters:

  a - the attachment id - IntVal
  h - new height        - IntVal
  w - new width         - IntVal
  c - crop image        - 0 or 1
  g - grayscale image   - 0 or 1
  n - negate image      - 0 or 1
  q - the output quality (only for jpeg) - values 1 to 100

*/



// Load WordPress
Ob_Start();
While (!Is_File ('wp-load.php')){
  If (Is_Dir('../')) ChDir('../');
  Else Die('Could not find WordPress.');
}
Include_Once 'wp-load.php';
Ob_End_Clean();


// Thumbnail generator Object
If (!Class_Exists('wp_thumbnail_image_generator')){
class wp_thumbnail_image_generator {
  var $attachment_id;
  var $attachment_file;
  var $attachment_mime;
  var $attachment_type;

  var $dst_height;
  var $dst_width;
  var $dst_qualy;
  var $crop;
  var $grayscale;
  var $negate;

  var $cache_dir_path;
  var $cache_dir_url;
  var $cache_file_path;
  var $cache_file_url;
  var $use_cache = True; // This is only for debug mode, sorry.

  var $image;

  function __construct(){
    $this->load_parameters();

    If (!$this->detect_mime_type())
      Die ('The attachment should be an image.');

    $this->detect_cache_dir();
    $this->check_for_cached_file();

    // Load the Image file
    If (!$this->load_image_file())
      Die('Error while loading ' . $this->attachment_file);

    // Resample the image
    If (!$this->Resample_Image())
      Die('Error while resampling ' . $this->attachment_file);

    If ($this->grayscale && !$this->Grayscale_Image())
      Die('Error while grayscaling ' . $this->attachment_file);

    If ($this->negate && !$this->Negate_Image())
      Die('Error while negating ' . $this->attachment_file);

    $this->prepare_cache_file();
    $this->cache_or_output_image();
    $this->clean_cache();
  }

  function load_parameters(){
    // Load Parameters
    $_REQUEST = Array_Merge (Array(
      'a' => 0,
      'h' => 0,
      'w' => 0,
      'c' => 0,
      'g' => 0,
      'q' => 80
    ), $_REQUEST);

    $this->attachment_id = IntVal ($_REQUEST['a']);
    If ($this->attachment_id < 1)
      Die('I do not think that '.$this->attachment_id.' is a valid attachment id.');

    $this->attachment_file = RealPath(get_attached_file ($this->attachment_id));
    If (!Is_File($this->attachment_file))
      Die ('Could not find this attachment.');

    $this->dst_height = IntVal ($_REQUEST['h']);
    If ($this->dst_height < 1)
      $this->dst_height = 0;

    $this->dst_width = IntVal ($_REQUEST['w']);
    If ($this->dst_width < 1)
      $this->dst_width = 0;

    If ($this->dst_width < 1 && $this->dst_height < 1)
      Die('New dimensions cannot be less then 1.');

    $this->crop = IntVal ($_REQUEST['c']);
    If ($this->crop == 0)
      $this->crop = False;
    Else
      $this->crop = True;

    If ($this->crop && ($this->dst_width < 1 || $this->dst_height < 1) )
      Die('If you want something cropped please tell me the dimensions.');

    $this->grayscale = IntVal ($_REQUEST['g']);
    If ($this->grayscale == 0)
      $this->grayscale = False;
    Else
      $this->grayscale = True;

    $this->negate = IntVal ($_REQUEST['n']);
    If ($this->negate == 0)
      $this->negate = False;
    Else
      $this->negate = True;

    $this->dst_qualy = IntVal ($_REQUEST['q']);
    If ($this->dst_qualy < 1 || $this->dst_qualy > 100)
      $this->dst_qualy = 80;
  }

  function detect_mime_type(){
    // Detect the correct mime type
    $arr_attachment_mime = Explode('/', get_post_mime_type( $this->attachment_id ));
    $this->attachment_mime = StrToLower ($arr_attachment_mime[0]);
    $this->attachment_type = StrToLower ($arr_attachment_mime[1]);

    If ($this->attachment_mime != 'image') return False;

    return True;
  }

  function detect_cache_dir(){
    Global $blog_id;

    // Read Cache Directory and URL
    $cache_dir_name = '/image_thumbs/';
    $cache_file_name = $blog_id . '-' .
                       $this->attachment_id . '-' .
                       $this->dst_width . '-' .
                       $this->dst_height . '-' .
                       IntVal($this->crop) . '-' .
                       IntVal($this->grayscale) . '-' .
                       IntVal($this->negate) . '-' .
                       $this->dst_qualy .
                       '.' . $this->attachment_type;

    $this->cache_dir_path = WP_CONTENT_DIR . $cache_dir_name;
    $this->cache_dir_url  = WP_CONTENT_URL . $cache_dir_name;
    $this->cache_file_path = $this->cache_dir_path . $cache_file_name;
    $this->cache_file_url  = $this->cache_dir_url  . $cache_file_name;
  }

  function check_for_cached_file(){
    // Take a look over the cache. Maybe there is this file already
    If ( $this->use_cache &&
         Is_File($this->cache_file_path) &&
         FileSize($this->cache_file_path) > 0 &&
         FileMTime($this->cache_file_path) > FileMTime($this->attachment_file) ){
      Header( 'Location: ' . $this->cache_file_url );
      Exit;
    }
  }

  function load_image_file(){
    # Get File Information
    If ($arr_image_size = GetImageSize($this->attachment_file))
      List ($width, $height, $image_type) = $arr_image_size;
    Else
      return False;

    # Loading image to memory according to type
    Switch ( $image_type ){
      case IMAGETYPE_GIF:
        If (!Function_Exists('imagecreatefromgif')) return False;
        If (!$image = imagecreatefromgif($this->attachment_file)) return False;
        Break;

      case IMAGETYPE_JPEG:
        If (!Function_Exists('imagecreatefromjpeg')) return False;
        If (!$image = imagecreatefromjpeg($this->attachment_file)) return False;
        Break;

      case IMAGETYPE_PNG:
        If (!Function_Exists('imagecreatefrompng')) return False;
        If (!$image = imagecreatefrompng($this->attachment_file)) return False;
        Break;

      default: return False;
    }

    # This is the transparency-preserving magic
    If (!Function_Exists('ImageCreateTrueColor')) return False;
    If (!$this->image = ImageCreateTrueColor( $width, $height )) return False;

    If ( $image_type == IMAGETYPE_GIF || $image_type == IMAGETYPE_PNG ){

      If ( $image_type == IMAGETYPE_GIF && $transparency >= 0 ){
        If (!Function_Exists('ImageColorTransparent')) return False;
        $transparency = ImageColorTransparent($image);

        If (!Function_Exists('ImageColorsForIndex')) return False;
        List($r, $g, $b) = Array_Values (ImageColorsForIndex($image, $transparency));

        If (!Function_Exists('ImageColorAllocate')) return False;
        $transparency = ImageColorAllocate($this->image, $r, $g, $b);

        If (!Function_Exists('Imagefill')) return False;
        Imagefill($this->image, 0, 0, $transparency);

        If (!Function_Exists('ImageColorTransparent')) return False;
        ImageColorTransparent($this->image, $transparency);
      }

      Elseif ($image_type == IMAGETYPE_PNG) {
        If (!Function_Exists('ImageAlphaBlending')) return False;
        ImageAlphaBlending($this->image, False);

        If (!Function_Exists('ImageSaveAlpha')) return False;
        ImageSaveAlpha($this->image, True);
      }
    }

    // Copy the image in our new one
    If (!ImageCopy ($this->image, $image, 0, 0, 0, 0, $width, $height)) return False;

    // Ok.
    return True;
  }

  function Resample_Image() {
    If ( $this->dst_height <= 0 && $this->dst_width <= 0 )
      return False;

    # Setting defaults and meta
    $image = $this->image;
    $final_width = 0;
    $final_height = 0;

    # Get File Information
    If ($arr_image_size = GetImageSize($this->attachment_file))
      List ($width_old, $height_old, $image_type) = $arr_image_size;
    Else
      return False;

    If ($this->crop){
      $factor = Max( $this->dst_width / $width_old, $this->dst_height / $height_old );

      $final_width  = $this->dst_width;
      $final_height = $this->dst_height;
    }
    Else {
      # Calculating proportionality
      If     ($this->dst_width  == 0) $factor = $this->dst_height / $height_old;
      ElseIf ($this->dst_height == 0) $factor = $this->dst_width / $width_old;
      Else   $factor = Min( $this->dst_width / $width_old, $this->dst_height / $height_old );

      $final_width  = $width_old * $factor;
      $final_height = $height_old * $factor;
    }

    # Resample the image
    If (!Function_Exists('ImageCreateTrueColor')) return False;
    If (!$this->image = ImageCreateTrueColor( $final_width, $final_height )) return False;

    # Copy the Transparency properties
    If ( $image_type == IMAGETYPE_GIF || $image_type == IMAGETYPE_PNG ){
      If ( $image_type == IMAGETYPE_GIF && $transparency >= 0 ){
        $transparency = ImageColorTransparent($image);
        List($r, $g, $b) = Array_Values (ImageColorsForIndex($image, $transparency));
        ImageColorAllocate($this->image, $r, $g, $b);
        Imagefill($this->image, 0, 0, $transparency);
        ImageColorTransparent($this->image, $transparency);
      }
      Elseif ($image_type == IMAGETYPE_PNG) {
        ImageAlphaBlending($this->image, False);
        ImageSaveAlpha($this->image, True);
      }
    }

    If (!Function_Exists('ImageCopyResampled')) return False;
    If ($this->crop){
      # Crop the image
      ImageCopyResampled( $this->image, $image, // resource $dst_image , resource $src_image
                          0, 0, // int $dst_x , int $dst_y
                          ($width_old * $factor - $final_width) / (2 * $factor), // int $src_x
                          ($height_old * $factor - $final_height) / (2 * $factor), // int $src_y
                          $final_width, $final_height, // int $dst_w , int $dst_h
                          $final_width / $factor, $final_height / $factor ); // int $src_w , int $src_h
    }
    Else {
      # Resample aspect ratio
      ImageCopyResampled($this->image, $image, 0, 0, 0, 0, $final_width, $final_height, $width_old, $height_old);
    }

    return True;
  }

  function Grayscale_Image(){
    If (!ImageFilter($this->image, IMG_FILTER_GRAYSCALE))
      return False;
    Else
      return True;
  }

  function Negate_Image(){
    If (!ImageFilter($this->image, IMG_FILTER_NEGATE))
      return False;
    Else
      return True;
  }

  function prepare_cache_file(){
    // Prepare Cache file
    If ( !Is_Dir ($this->cache_dir_path) &&
         !Is_File($this->cache_dir_path) &&
         Is_Writable (DirName($this->cache_dir_path))){
      MkDir ($this->cache_dir_path);
      ChMod ($this->cache_dir_path, 0755);
      }

    If ( Is_Dir ($this->cache_dir_path) &&
         Is_Writable ($this->cache_dir_path))
      Touch ($this->cache_file_path);
  }

  function cache_or_output_image(){
    // Cache the new image and send it to the browser or redirect the browser to the cached image
    If ($this->use_cache && is_writable($this->cache_file_path)){
      // Save the image in cache
      Switch ( $this->attachment_type ) {
        Case 'gif': imagegif($this->image, $this->cache_file_path); Break;
        Case 'jpeg': imagejpeg($this->image, $this->cache_file_path, $this->dst_qualy); Break;
        Case 'png': imagepng($this->image, $this->cache_file_path); Break;
        default: return false;
      }

      ChMod($this->cache_file_path, 0755);

      // If we have the image cached we have to output it now
      Header( 'Location: ' . $this->cache_file_url );
    }
    Else {
      // The cache is not writable. Send the image to the browser
      Header ('Content-Type: image/' . $this->attachment_type);
      Switch ( $this->attachment_type ) {
        Case 'gif': imagegif($this->image); Break;
        Case 'jpeg': imagejpeg($this->image, Null, $this->dst_qualy); Break;
        Case 'png': imagepng($this->image); Break;
        default: return false;
      }
      Exit;
    }
  }

  function clean_cache(){
    // Clean Cache files - This point will only be passed if there was a new cache file written!
    ForEach ( Glob($this->cache_dir_path.'*') AS $file ){
      // Delete Files which were not used the last 23 days
      If ( FileATime($file) < (Time() - (23 * 24 * 3600)) )
        Unlink ($file);
    }
  }

} /* End of the Class */
New wp_thumbnail_image_generator;
} /* End of the If-Class-Exists-Condition */
Else Die('Sorry but there is already a "wp_thumbnail_image_generator" class. oO');
/* End of File */