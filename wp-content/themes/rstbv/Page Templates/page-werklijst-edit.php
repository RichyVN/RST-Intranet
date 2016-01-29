<?php

/* Template Name: Page werklijst edit */

// Declare static variables 

$lijst_id = get_query_var('lijst_id'); // allert! this must be in functions.php!
$post_id = $lijst_id;
$url = site_url();
if ($id == "") {
	header("Location: ".$url."/werklijst");
}
$current_user = wp_get_current_user();
$username = $current_user->display_name;

// Get lists where postid = url id
$args = array (
  'post_type'              => array( 'werklijst' ),
	'p'                      => $lijst_id,
);

$lijsten = new WP_Query( $args ); 

wp_reset_postdata();

// Save post when button save is clicked
if (isset($_POST['save'])  && (isset($_POST['form1']) )) {
		global $wpdb;
		// Declare form variables
	  $FormNaam = sanitize_text_field($_POST['Naam']);
	  $FormOps = sanitize_text_field($_POST['Ops']);
		$FormAdmin = sanitize_text_field($_POST['Admin']);
	  $FormPrep = sanitize_text_field($_POST['Prep']);
	  $FormNotPrep = sanitize_text_field($_POST['NotPrep']);
		$table = $wpdb->postmeta;
	
    // update fields
		$result = $wpdb->query("
			UPDATE $table
			SET meta_value = '$FormOps'	
			WHERE post_id = $lijst_id AND meta_key = 'operationeel'");
	  $result = $wpdb->query("
			UPDATE $table
			SET meta_value = '$FormAdmin'	
			WHERE post_id = $lijst_id AND meta_key = 'administratief'");
	  $result = $wpdb->query("
			UPDATE $table
			SET meta_value = '$FormPrep'	
			WHERE post_id = $lijst_id AND meta_key = 'voorbereid'");
	  $result = $wpdb->query("
			UPDATE $table
			SET meta_value = '$FormNotPrep'	
			WHERE post_id = $lijst_id AND meta_key = 'niet_voorbereid'");
		
			header("Location: ".$url."/werklijst");
		$wpdb->flush();
}

//  Send Email and put post in trash after success and update is clicked
if (isset($_POST['update'])  && (isset($_POST['form1']) )) {
		global $wpdb;
		// Declare form variables
	  $FormDeContent = ($_POST['DeContent']); /* get the content from this post*/  
	  $FormDePloeg = ($_POST['DePloeg']);
	  $FormDeDatum = ($_POST['DeDatum']);
	  $FormDeDienst = ($_POST['DeDienst']);
	  $FormNaam = ($_POST['Naam']);
	  $FormOps = sanitize_text_field($_POST['Ops']);
		$FormAdmin = sanitize_text_field($_POST['Admin']);
	  $FormPrep = sanitize_text_field($_POST['Prep']);
	  $FormNotPrep = sanitize_text_field($_POST['NotPrep']);	
	  $table = $wpdb->postmeta;
	  $table2 = $wpdb->posts;

		// create email
		$to = "r.vnaamen@rstbv.nl";
	  //$to = "Werklijsten@rstbv.nl";     // is distributie groep
		$subject = "Werklijst - " . $FormDeDatum . " - Ploeg: " . $FormDePloeg . " - " . $FormDeDienst ;
		$body = "<body style=\"font-family:Verdana, Verdana, Geneva, sans-serif; font-size:14px; background-color:#fff; color:#000;\">\n";	
		$body .= "Geachte collega,<br \><br \>\n";
		$body .= "Dit is het verslag van de overdracht.<br \><br \><br \>\n";
	  $body .= "<strong>Opmerkingen van de Teamleader:</strong><br \>\n";
	  $body .= $FormDeContent . "<br \><br \><br \>\n";
	  $body .= "<strong>Operationele opmerkingen:</strong><br \>\n";
	  $body .= $FormOps . "<br \><br \><br \>\n";
	  $body .= "<strong>Administratieve opmerkingen:</strong><br \>\n";
	  $body .= $FormAdmin . "<br \><br \><br \>\n"; 
	  $body .= "<strong>Voorbereid opmerkingen:</strong><br \>\n";
	  $body .= $FormPrep . "<br \><br \><br \>\n"; 
	  $body .= "<strong>Niet Voorbereid opmerkingen:</strong><br \>\n";
	  $body .= $FormNotPrep . "<br \><br \><br \>\n"; 
		$body .= "Met vriendelijke groeten, <br \>\n";
		$body .= "\n";
	  $body .= $FormNaam. "\n";
		$body .= "</body>\n";
		$headers = array(
			'Content-Type: text/html; charset=UTF-8',
			'From: RSTBV <no-reply@rstbv.nl>',
		);
		wp_mail( $to, $subject, $body, $headers );

 // update velden
		
		$result = $wpdb->query("
			UPDATE $table
			SET meta_value = '$FormOps'	
			WHERE post_id = $lijst_id AND meta_key = 'operationeel'");
	  $result = $wpdb->query("
			UPDATE $table
			SET meta_value = '$FormAdmin'	
			WHERE post_id = $lijst_id AND meta_key = 'administratief'");
	  $result = $wpdb->query("
			UPDATE $table
			SET meta_value = '$FormPrep'	
			WHERE post_id = $lijst_id AND meta_key = 'voorbereid'");
	  $result = $wpdb->query("
			UPDATE $table
			SET meta_value = '$FormNotPrep'	
			WHERE post_id = $lijst_id AND meta_key = 'niet_voorbereid'");
		
	   //put post in the trash after sending email 
	   /* wp_trash_post( $lijst_id ); */
	
		header("Location: ".$url."/werklijst");
		$wpdb->flush();
		}

get_header(); ?>

<body>
   <div id="primary" class="site-content">
    <div id="content" role="main">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
   	  <div class="post" id="post-<?php the_ID(); ?>">
        <div class="entry-content hentry">
       	  <?php the_content(); ?>
    	<?php endwhile; ?>
    <?php endif; wp_reset_postdata(); ?>
        </div>
    </div>
<!-- start loop hier -->
<?php 
while ($lijsten->have_posts()) : $lijsten->the_post(); 
	$werklijstid = get_the_ID();
	$title = get_the_title();
	$content = get_the_content();
	$date = get_the_date();
	$datum = get_field('datum');
	$dienst = get_field('dienst');
	$ploeg = get_field('ploeg');
	$ops = get_field('operationeel');
	$admin = get_field('administratief');
	$prep = get_field('voorbereid');
	$notprep = get_field('niet_voorbereid');
	
endwhile ?> 
<!-- stop loop hier -->
		
<!-- hier komt het custom gedeelte van de form -->
<div class="entry-content">
		
		<h3><?php echo $datum . " - " . $dienst . " - Ploeg: " . $ploeg; ?></h3>

	<?php if ($content != "") { ;?>
  	<span class="rood">Opmerkingen van de Teamleader:</span> <br />
	<p class="rood"><strong><?php echo $content; ?></strong></p>
  	<?php } else { ;?>
  <p class="rood">Er zijn (nog) geen opmerkingen van de Teamleader</p>
		<?php	}; ?>	

	 			<form action="<?php echo site_url(); ?>/edit-werklijst?lijst_id=<?php echo $lijst_id; ?>" method="POST" name="form1">
		 	<table class="kraan-table" width="100%" cellpadding="3" cellspacing="3">
					<tr valign="baseline">
						<th align="left">Operationeel:</th>
						<td>
							<textarea name="Ops" cols="45" rows="5" maxlength="2055"><?php echo $ops; ?></textarea>
						</td>
					</tr>
					<tr valign="baseline">
						<th align="left">Administratief:</th>
						<td>
								<textarea name="Admin" cols="45" rows="5" maxlength="2055"><?php echo $admin; ?></textarea> 
						</td>
					</tr>
					<tr valign="baseline">
						<th align="left">Voorbereid:</th>
						<td>
								<textarea name="Prep" cols="45" rows="5" maxlength="2055"><?php echo $prep; ?></textarea>
 						</td>
					</tr>
				  <tr valign="baseline">
						<th align="left">Niet Kunnen Voorbereid:</th>
						<td>
								<textarea name="NotPrep" cols="45" rows="5" maxlength="2055"><?php echo $notprep; ?></textarea>
								(Reden erbij vermelden)
 						</td>
					</tr>
					<tr valign="baseline">
						<td>&nbsp;</td>
						<td>
							<input type="submit" name="update" value="Verstuur werklijst!" />
							<input type="submit" name="save" value="Opslaan" />
							<input type="hidden" name="form1" id="form1" value="form1" />
							<input type="hidden" name="Naam" id="Naam" value="<?php echo $username; ?>" />
							<input type="hidden" name="DePloeg" id="DePloeg" value="<?php echo $ploeg; ?>" />
							<input type="hidden" name="DeDatum" id="DeDatum" value="<?php echo $datum; ?>" />
							<input type="hidden" name="DeDienst" id="DeDienst" value="<?php echo $dienst; ?>" />
							<div class="hidden">
							<?php // strip images out from the content
										$content = get_the_content();
										$content = preg_replace("/<img[^>]+\>/i", " ", $content);          
										$content = apply_filters('the_content', $content);
										$content = str_replace(']]>', ']]>', $content);
							?>
								<input type="hidden" name="DeContent" id="DeContent" value="<?php echo $content; ?>" />
							</div>
							
						</td>
					</tr>
				</table>
		</form>
</div><!-- # entry content -->
  
<br />
<br />
    
    </div><!-- #content -->
</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
