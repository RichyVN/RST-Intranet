<?php
/**
 * The template for displaying Custom Archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each specific one. For example, Twenty Twelve already
 * has tag.php for Tag archives, category.php for Category archives, and
 * author.php for Author archives.
 */

//Query             
$args = array (
    'post_type'              => array( 'werklijst' ),
    'order'                  => 'ASC',
    'orderby'                => 'title',
	  'posts_per_page'         => '20',
);
$werklijsten = new WP_Query( $args ); 


get_header(); ?>
	<div id="primary-werklijsten" class="site-content">
		<div id="content" role="main">
			<header class="archive-header">
				<h1 class="werklijst-title">
          <i class="fa fa-lg fa-th-list fa-ico-default"></i> Overzicht Alle Werklijsten</h1>
 			</header><!-- .archive-header -->
            <div class="entry-content">
                <div class="lijst">
                     <p>&nbsp;</p>
                      <table class="tb-werklijst" width="100%" border="0">
												<tbody>                 
										<!-- start loop hier -->
										<?php while ($werklijsten->have_posts()) : $werklijsten->the_post(); 
												$lijstid = get_the_ID();			
												$title = get_the_title();
												$content = get_the_content();
												$datum = get_field('datum');
												$dienst = get_field('dienst');
												$ploeg = get_field('ploeg');
												$ops = get_field('operationeel');
												$admin = get_field('administratief');
												$prep = get_field('voorbereid');
												$nietprep = get_field('niet_voorbereid');
												$params = array('lijst_id' => $lijstid);
										?>
												<tr>
													<td width="15%">
														<!-- Als je bent ingelogd EN een logistiekzuidzijde role hebt -->
														<?php if (is_user_logged_in() && current_user_can('logistiekzuidzijde') ) { ?>
														<a href="<?php echo add_query_arg($params, '/wp-test/edit-werklijst'); ?>">
															<?php echo $datum;?>
														</a> 
														<!-- Als je bent ingelogd EN een administrator role hebt -->
														<?php } elseif (is_user_logged_in() && current_user_can('administrator') ) { ?>	
														<a href="<?php echo add_query_arg($params, '/wp-test/edit-werklijst'); ?>">
															<?php echo $datum;?>
														</a> 	
														
														<?php } else { echo $datum; } ?>
																</td>
																	<td width="15%"><?php echo $dienst; ?></td>
																	<td width="10%"><?php echo "Ploeg: " . $ploeg; ?></td>
																	
												</tr>
										<?php endwhile ?> <!-- stop loop hier -->
											  </tbody>
											</table>
 <p>&nbsp;</p>
                </div><!-- #lijst -->
            </div><!-- #entry-content -->

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>

	