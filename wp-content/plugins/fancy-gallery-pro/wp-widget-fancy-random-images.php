<?php If (!Class_Exists('wp_widget_fancy_random_images')){
Class wp_widget_fancy_random_images Extends WP_Widget {
  var $fancy_gallery;

  Function __construct(){
    // Get Fancy Gallery
    If (IsSet($GLOBALS['wp_plugin_fancy_gallery']) && Is_Object($GLOBALS['wp_plugin_fancy_gallery']))
      $this->fancy_gallery = $GLOBALS['wp_plugin_fancy_gallery'];
    Else
      return False;

    // Setup the Widget data
    parent::__construct (
      False,
      $this->t('Fancy Random Images'),
      Array('description' => $this->t('Displays some random images from your galleries.'))
    );
  }

  Function t ($text, $context = ''){
    return $this->fancy_gallery->t($text, $context);
  }

  Function Default_Options(){
    // Default settings
    return Array(
      'limit'  => 0,
      'exclude' => False,
      'thumb_width' => get_option('thumbnail_size_w'),
      'thumb_height' => get_option('thumbnail_size_h'),
      'link_target' => 'file'
    );
  }

  Function Load_Options($options){
    $options = (ARRAY) $options;

    // Delete empty values
    ForEach ($options AS $key => $value)
      If (!$value) Unset($options[$key]);

    // Load options
    $this->arr_option = Array_Merge ($this->Default_Options(), $options);
  }

  Function Get_Option($key, $default = False){
    If (IsSet($this->arr_option[$key]) && $this->arr_option[$key])
      return $this->arr_option[$key];
    Else
      return $default;
  }

  Function Set_Option($key, $value){
    $this->arr_option[$key] = $value;
  }

  Function Get_Fancy_Images($orderby = 'ID', $order = 'ASC', $limit = -1){
    Global $wpdb;

    // Check Parameters
    If (!$orderby) $orderby = 'ID';
    If (!In_Array($order, Array('ASC', 'DESC', 'RAND'))) $order = 'ASC';
    $limit = IntVal ($limit);

    // Build Statement
    $stmt = '
    SELECT attachment.*, gallery.ID gallery_id
    FROM   '.$wpdb->posts.' attachment, '.$wpdb->posts.' gallery
    WHERE  attachment.post_type = "attachment"
    AND    attachment.post_mime_type LIKE "image/%"
    AND    gallery.post_type = "'.$this->fancy_gallery->gallery_post_type.'"
    AND    attachment.post_parent = gallery.ID
    GROUP BY attachment.ID ';
    If ($order == 'RAND')
      $stmt .= 'ORDER BY RAND() ';
    Else
      $stmt .= 'ORDER BY attachment.' . $orderby . ' ' . $order . ' ';
    If ($limit > 0) $stmt .= 'LIMIT ' . $limit;

    return $wpdb->Get_Results($stmt);
  }

  Function Form ($settings){
    // Load options
    $this->load_options ($settings); Unset ($settings);
    ?>
    <p>
      <label for="<?php Echo $this->Get_Field_Id('title') ?>"><?php Echo $this->t('Title') ?></label>:
      <input type="text" id="<?php Echo $this->Get_Field_Id('title') ?>" name="<?php Echo $this->get_field_name('title')?>" value="<?php Echo HTMLSpecialChars($this->get_option('title')) ?>" /><br />
      <small><?php Echo $this->t('Leave blank to use the widget default title.') ?></small>
    </p>

    <p>
      <label for="<?php Echo $this->Get_Field_Id('limit') ?>"><?php Echo $this->t('Number of Images') ?></label>:
      <input type="text" id="<?php Echo $this->Get_Field_Id('limit') ?>" name="<?php Echo $this->get_field_name('limit')?>" value="<?php Echo HTMLSpecialChars($this->get_option('limit')) ?>" size="4" /><br />
      <small><?php Echo $this->t('Leave blank (or "0") to show all.') ?></small>
    </p>

    <p>
      <label for="<?php Echo $this->Get_Field_Id('link_target') ?>"><?php Echo $this->t('Link target') ?></label>:
      <select name="<?php Echo $this->get_field_name('link_target')?>" id="<?php Echo $this->Get_Field_Id('link_target') ?>">
        <option value="file" <?php Selected($this->Get_option('link_target'), 'file') ?>><?php Echo $this->t('Fullsize Image') ?></option>
        <option value="gallery" <?php Selected($this->Get_option('link_target'), 'gallery') ?>><?php Echo $this->t('Image Gallery') ?></option>
      </select>
    </p>

    <p>
      <label for="<?php Echo $this->Get_Field_Id('thumb_width') ?>"><?php Echo $this->t('Thumbnail width:') ?></label>
      <input type="text" name="<?php Echo $this->get_field_name('thumb_width') ?>" id="<?php Echo $this->Get_Field_Id('thumb_width') ?>" value="<?php Echo HTMLSpecialChars($this->Get_Option('thumb_width')) ?>" size="4" />px
    </p>

    <p>
      <label for="<?php Echo $this->Get_Field_Id('thumb_height') ?>"><?php Echo $this->t('Thumbnail height:') ?></label>
      <input type="text" name="<?php Echo $this->get_field_name('thumb_height') ?>" id="<?php Echo $this->Get_Field_Id('thumb_height') ?>" value="<?php Echo HTMLSpecialChars($this->Get_Option('thumb_height')) ?>" size="4" />px
    </p>

    <p>
      <input type="checkbox" name="<?php Echo $this->get_field_name('thumb_grayscale') ?>" id="<?php Echo $this->Get_Field_Id('thumb_grayscale') ?>" value="yes" <?php Checked($this->Get_Option('thumb_grayscale'), 'yes') ?> />
      <label for="<?php Echo $this->Get_Field_Id('thumb_grayscale') ?>"><?php Echo $this->t('Convert thumbnails to grayscale.') ?></label>
    </p>

    <p>
      <input type="checkbox" name="<?php Echo $this->get_field_name('thumb_negate') ?>" id="<?php Echo $this->Get_Field_Id('thumb_negate') ?>" value="yes" <?php Checked($this->Get_Option('thumb_negate'), 'yes') ?> />
      <label for="<?php Echo $this->Get_Field_Id('thumb_negate') ?>"><?php Echo $this->t('Negate the thumbnails.') ?></label>
    </p>

    <p>
      <label for="<?php echo $this->get_field_id('exclude'); ?>"><?php _e( 'Exclude:' ); ?></label>
      <input type="text" value="<?php echo HTMLSpecialChars($this->get_option('exclude')); ?>" name="<?php echo $this->get_field_name('exclude'); ?>" id="<?php echo $this->get_field_id('exclude'); ?>" class="widefat" /><br />
      <small><?php Echo $this->t( 'Term IDs, separated by commas.' ); ?></small>
    </p>

    <h3><?php Echo $this->t('Template') ?></h3>
    <p><?php Echo $this->t('Please choose a template to display this widget.') ?></p>
    <?php ForEach ( $this->fancy_gallery->Get_Template_Files() AS $name => $properties ) : ?>
    <p>
      <input type="radio" name="<?php Echo $this->get_field_name('template') ?>" id="<?php Echo Sanitize_Title($properties['file']) ?>" value="<?php Echo HTMLSpecialChars($properties['file']) ?>"
        <?php Checked($this->Get_Option('template'), $properties['file']) ?>
        <?php Checked(!$this->Get_Option('template') && $properties['file'] == $this->fancy_gallery->Get_Default_Template()) ?> />
      <label for="<?php Echo Sanitize_Title($properties['file']) ?>">
      <?php If (Empty($properties['name'])) : ?>
        <em><?php Echo $properties['file'] ?></em>
      <?php Else : ?>
        <strong><?php Echo $properties['name'] ?></strong>
      <?php EndIf; ?>
      </label>
      <?php If ($properties['description']) : ?><br /><?php Echo $properties['description']; Endif; ?>
    </p>
    <?php EndForEach;

  }

  Function Widget ($args, $settings){
    // Load options
    $this->load_options ($settings); Unset ($settings);

    // Get random images
    $arr_images = $this->Get_Fancy_Images( 'ID', 'RAND', $this->get_option('limit') );

    If (Empty($arr_images))
      return False;
    Else {
      ForEach ($arr_images AS &$image){
        $include .= $image->ID . ',';
        ($this->Get_Option('link_target') == 'gallery') ? $image->href = Get_Permalink($image->gallery_id) : '';
      }
    }

    // Generate Attributes
    $attributes = $this->fancy_gallery->Generate_Gallery_Attributes(Array(
      'include' => $include,
      'thumb_width' => $this->Get_Option('thumb_width'),
      'thumb_height' => $this->Get_Option('thumb_height'),
      'thumb_grayscale' => $this->Get_Option('thumb_grayscale'),
      'thumb_negate' => $this->Get_Option('thumb_negate')
    ));

    // Build Gallery
    $this->fancy_gallery->Build_Gallery ($arr_images, $attributes);

    // Display Widget
    Echo $args['before_widget'];

    Echo $args['before_title'] . Apply_Filters('widget_title', $this->get_option('title'), $settings, $this->id_base) . $args['after_title'];

    Echo $this->fancy_gallery->Render_Gallery($this->get_option('template'));

    Echo $args['after_widget'];
  }

  Function Update ($new_settings, $old_settings){
    return $new_settings;
  }

} /* End of Class */
Add_Action ('widgets_init', Create_Function ('','Register_Widget(\'wp_widget_fancy_random_images\');') );
} /* End of If-Class-Exists-Condition */
/* End of File */
