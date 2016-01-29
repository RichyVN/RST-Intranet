<?php
// Current page and offset
$current_page = $_GET['paged'] = IsSet($_GET['paged']) ? IntVal($_GET['paged']) : 1;

// Current Gallery
$current_gallery = Get_Post($_GET['post_id']);

// Get the images for this page
$images_query = New WP_Query(Array(
  'post_type'				=> 'attachment',
  'post_status'			=> 'any',
  'post_mime_type'	=> 'image',
  'paged'						=> $current_page,
  'posts_per_page'	=> 20
));

// Prepare Pagination
$page_links = paginate_links( array(
	'base' => Add_Query_Arg( Array('paged' => '%#%', 'move_attachment' => Null, 'move_to' => Null) ),
	'format' => '',
	'prev_text' => __('&laquo;'),
	'next_text' => __('&raquo;'),
	'total' => $images_query->max_num_pages,
	'current' => $images_query->query_vars['paged']
));
?>

<?php If (!Empty($message)): ?>
<div class="updated fade">
	<p><strong><?php Echo $message ?></strong></p>
</div>
<?php EndIf ?>

<?php If ( $page_links ) : ?>
<div class="tablenav">
	<div class="tablenav-pages"><?php Echo $page_links ?></div>
</div>
<?php EndIf ?>

<?php ForEach ($images_query->posts AS $image) :
$attachment_counter++;
$image->parent = Get_Post($image->post_parent);
If (Is_Object($image->parent)){
	$image->parent->title = Get_The_Title($image->parent->ID);
	$image->parent->link = Get_Permalink($image->parent->ID);
	$image->parent->type = Get_Post_Type_Object($image->parent->post_type);
}
Else $image->parent = False;
$image->move_link = Add_Query_Arg(Array('move_attachment' => $image->ID, 'move_to' => $current_gallery->ID));
?>
<div class="attachment" id="attachment-<?php Echo $image->ID ?>">
	<?php Echo wp_get_attachment_image( $image->ID ); ?>
	<div class="details">
		<div class="name"><?php PrintF($this->t('Image: "%s"'), $image->post_title) ?></div>
		<?php If ($image->parent) : ?>
		<div class="post"><?php PrintF($this->t('Belongs to %s: "%s"'), $image->parent->type->labels->singular_name, '<a href="'.$image->parent->link.'" target="_blank">'.$image->parent->title.'</a>') ?></div>
		<?php Else: ?>
		<div class="post"><?php Echo $this->t('Not attached to a post.') ?></div>
		<?php Endif ?>
		<div class="ajax-loader hidden"><img src="<?php echo Admin_Url('images/loading.gif') ?>" alt="Loading"></div>
		<div class="import-success hidden"><?php Echo $this->t('This image belongs to your gallery.') ?></div>

		<?php If ($image->parent->ID != $current_gallery->ID): ?>
		<p class="import"><a href="<?php Echo $image->move_link ?>" class="import button"><?php Echo $this->t('Import to my gallery') ?></a></p>
		<?php EndIf ?>

	</div>
	<div class="clear"></div>
</div>
<?php If ($attachment_counter % 2 == 0): ?><div class="clear"></div><?php EndIf; EndForEach ?>

<?php If ( $page_links ) : ?>
<div class="tablenav">
	<div class="tablenav-pages"><?php Echo $page_links ?></div>
</div>
<?php EndIf;