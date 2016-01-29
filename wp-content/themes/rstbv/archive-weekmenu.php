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
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>
	<section id="primary" class="site-content">
		<div id="content" role="main">
		
		
			<header class="archive-weekmenu-header">
				<h1 class="title">Weekmenu</h1>
			</header><!-- .archive-header -->
				<div class="entry-content">
					<div class="weekmenu-content">
						<p>De avondmaaltijden: inschrijven voor 15:00 uur (dezelfde dag).</p>
					</div>
						<!-- hier komt het custom gedeelte van de qoutes-->               
						<?php 
							$args = array (
							'post_type'             => 'weekmenu',
							'posts_per_page'        => '5',
							'meta_query'             => array(
								array(
									'key'       => 'dag',
								),
							),
						);
						?>
						<?php 
						$myprojects = new WP_Query( $args ); 
						while ($myprojects->have_posts()) : $myprojects->the_post(); 
						?>
						<div class="weekmenu">
							<h2><?php the_title(); ?></h2>
							<span class="label">Soep: </span> <?php the_field('soep'); ?><br />
							<span class="label">Koude Snack: </span> <?php the_field('koude_snack'); ?><br />
							<span class="label">Warme Snack: </span> <?php the_field('warme_snack'); ?><br />
							<span class="label">Lunchtip: </span> <?php the_field('lunchtip'); ?><br />
							<span class="label">Avond: </span> <?php the_field('avond'); ?><br />
						</div><!-- #weekmenu -->
				<?php endwhile ?>
				</div><!-- #entry-content -->
				<div class="weekmenu-content">
					<p>Met vriendelijke groet Rook Catering Services.<br />
					Eet Smakelijk.</p>
				</div>

		</div><!-- #content -->
	</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>