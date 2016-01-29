<?php

/* Template Name: Page kraan edit */

// Declare static variables 
$kraanid = get_query_var('kraanid');
$post_id = $kraanid;
$url = site_url();
if ($kraanid == "") {
	header("Location: ".$url."/kranen");
}
$datum = date("d-m-Y");
$time = date_i18n( get_option('time_format') );
$current_user = wp_get_current_user();
$username = $current_user->display_name;

// Get Kranen where postid = url id
$args = array (
  'post_type'              => array( 'kranen' ),
	'p'                      => $kraanid,
);

$kranen = new WP_Query( $args ); 

// Update statement in table from form 1 
if (isset($_POST['update'])  && (isset($_POST['form1']) )) {
		global $wpdb;
		// Declare form variables
		$FormNaam = sanitize_text_field($_POST['Naam']);
		$FormDatum = sanitize_text_field($_POST['Datum']);	
		$FormStatus = $_POST['Status'];
	  $FormKraan = $_POST['Kraannr'];
	  $FormKlanten = $_POST['Klanten'];
	  $FormRijen = sanitize_text_field($_POST['Rijen']);
	  $FormOpm = sanitize_text_field($_POST['Opm']);
		$FormTijd = sanitize_text_field($_POST['Tijd']);
		$table = $wpdb->postmeta;
	
// update velden
		$result = $wpdb->query("
			UPDATE $table
			SET meta_value = '$FormNaam'	
			WHERE post_id = $kraanid AND meta_key = 'ingevuld_door'");
		$result = $wpdb->query("
			UPDATE $table
			SET meta_value = '$FormRijen'
			WHERE post_id = $kraanid AND meta_key = 'rijen'");
		$result = $wpdb->query("
			UPDATE $table
			SET meta_value = '$FormOpm'	
			WHERE post_id = $kraanid AND meta_key = 'omschrijving'");
	  $result = $wpdb->query("
			UPDATE $table
			SET meta_value = '$FormTijd'	
			WHERE post_id = $kraanid AND meta_key = 'tijdstip'");
	  $result = $wpdb->query("
			UPDATE $table
			SET meta_value = '$FormStatus'	
			WHERE post_id = $kraanid AND meta_key = 'status'");
	  $result = $wpdb->query("
			UPDATE $table
			SET meta_value = '$FormKlanten'	
			WHERE post_id = $kraanid AND meta_key = 'klanten'");
		
			if($result){
				//echo "Updated success";
				header("Location: ".$url."/kranen");
			}	else {
				//echo "Nothing updated!";
				header("Location: ".$url."/kranen");
			}
			
	$wpdb->flush();
//  verstuur interne email
		$to = "r.vnaamen@rstbv.nl";
	  //$to = "kraanstoring-intern@rstbv.nl";
		$subject = "Melding voor kraanbeschikbaarheid - " . $FormKraan ;
		$body = "<body style=\"font-family:Verdana, Verdana, Geneva, sans-serif; font-size:14px; background-color:#fff; color:#000;\">\n";	
		$body .= "Geachte collega,";
		$body .= "<br \><br \>\n";  
		$body .= $FormKraan . ' is net <strong>' .$FormStatus. '</strong> gemeld door: ' .$FormNaam ;
	  $body .= "<br \><br \>\n";  
	  if ($FormRijen == "") {  
	  $body .= "";
	  } else {
		$body .= "De volgende rijen zijn (tijdelijk) niet beschikbaar: <strong>" . $FormRijen. "</strong>" ;
    $body .= "<br \><br \>\n";
		} // endif
	  if ($FormOpm == "") {
    	$body .= "Er is geen melding ingevuld";	
		} else {
			$body .= "De melding hierbij is:<br \>\n";
			$body .=  "<strong>" .$FormOpm . "</strong>";
		} // endif
		if ($_POST['Klanten'] == true) {
			
		  $body .= "<br \><br \><br \>\n";
			$body .= "<p style=\"font-size:12px; font-style:italic; background-color:#fff; color:#666;\">\n";
			$body .= "LET OP: Deze melding is ook verstuurd naar de klanten!</p> \n";
			$body .= "</body>\n";
			$headers = array(
			'Content-Type: text/html; charset=UTF-8',
			'From: RSTBV <no-reply@rstbv.nl>',
			//'BCC: Kraanstoringen <automatisering@rstbv.nl>',
		);
		} else {
		 
			$body .= "</body>\n";
			$headers = array(
			'Content-Type: text/html; charset=UTF-8',
			'From: RSTBV <no-reply@rstbv.nl>',
		);
		}
	wp_mail( $to, $subject, $body, $headers );
}
     
get_header(); ?>

<link href="<?php echo THEMEPATH; ?>/css/jquery.alerts.css" rel="stylesheet" type="text/css" media="screen" />

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
while ($kranen->have_posts()) : $kranen->the_post(); 
	$postid = get_the_ID();
	$kraan = get_the_title();    
	$status = get_field('status');
	$klanten = get_field('klanten');
	$rijen = get_field('rijen');
	$kade = get_field('kade');
	$omsch = get_field('omschrijving');
	$extra = get_field('extra_info');
	$door = get_field('ingevuld_door');
			
endwhile ?> 
<!-- stop loop hier -->
		
<!-- hier komt het custom gedeelte van de form -->
<div class="entry-content">
		<h1><?php echo $kraan; ?></h1>

		<form action="<?php echo site_url(); ?>/edit-kraan?kraanid=<?php echo $kraanid; ?>" method="POST" name="form1" onsubmit="return checkForm()">
			<table class="kraan-table" width="100%" cellpadding="3" cellspacing="3">
					<tr valign="baseline">
						<th align="left">Datum:</th>
						<td><input name="Datum" readonly="true" id="Datum" type="text" size="10" value="<?php echo $datum; ?>" /></td>
					</tr>
					<tr valign="baseline">
						<th align="left">Status:</th>
						<td>
								<input type="radio" name="Status" id="Status" value="In bedrijf" <?php if ($status == "In bedrijf"){echo "checked";	} ?>>In bedrijf
								<input type="radio" name="Status" id="Status" value="In onderhoud" <?php if ($status == "In onderhoud"){echo "checked";	} ?>>In onderhoud
								<input type="radio" name="Status" id="Status" value="In storing" <?php if ($status == "In storing"){echo "checked";	} ?>>In storing
						</td>
					</tr>
					
						<tr valign="baseline">
							<th align="left" >Tijdstip:</th>
							<td>
								<input name="Tijd" type="text" value="<?php echo $time; ?>" size="10" maxlength="5" />
								</td>
						</tr>
					<tr valign="baseline">
						<th align="left">Rijen:</th>
						<td><input name="Rijen" type="text" value="<?php echo $rijen; ?>" size="10"/>
									 (Deze rijen zijn tijdelijk niet beschikbaar)</td>
					</tr>
					<tr valign="baseline">
						<th align="left" valign="top">Omschrijving:</th>
						<td>
								<textarea name="Opm" cols="45" rows="5" maxlength="255"><?php echo $omsch; ?></textarea> 
						</td>
					</tr>
					<tr valign="baseline">
						<th align="left" valign="top">Verstuur ook naar klanten?:</th>
						<td>
								<input type="radio" name="Klanten" id="checkbox1" value="1" >Ja
								<input type="radio" name="Klanten" id="checkbox2" value="0" >Nee
								<span class="rood" id="radiowarn"></span>
								<br />
								Is de laatste melding naar de klant gegaan? : <?php if ($klanten == "1"){echo "Ja";	}	else { echo "Nee";} ?>
 						</td>
					</tr>
				
					<tr valign="baseline">
						<td>&nbsp;</td>
						<td>
						<input type="submit" name="update" value="verstuur" />
						<input type="hidden" name="form1" id="form1" value="form1" />
						<input type="hidden" name="Kraannr" id="Kraannr" value="<?php echo $kraan; ?>" />
						<input type="hidden" name="Naam" id="Naam" value="<?php echo $username; ?>" />
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

<script src="<?php echo THEMEPATH; ?>/js/jquery.alerts.js" type="text/javascript"></script>
<script type="text/javascript">

function checkForm() {
    var valid = false;
    var radios = document.getElementsByName("Klanten");

    var i = 0;
    while (!valid && i < radios.length) {
       if (radios[i].checked) {
       valid = true;
        }
        i++;
    }

    if (!valid) {
        document.getElementById("radiowarn").innerHTML = " < U dient een van de opties te selecteren!";
        document.getElementById("radiowarn").style.display = "inline-block";
    }
		
    return valid;
}
	
jQuery("#checkbox1").click( function() {
		if (jQuery(this).prop('checked')) {				
			jAlert('Deze melding gaat nu <u>OOK</u> naar de klanten! Weet u dat zeker?', 'Let op!');
			jQuery( "#radiowarn" ).hide();
		  }
		});	
jQuery("#checkbox2").click( function() {
		if (jQuery(this).prop('checked')) {				
			jAlert('Deze melding gaat nu <u>NIET</u> naar de klanten! Weet u dat zeker?', 'Let op!');
			jQuery( "#radiowarn" ).hide();
		  } 
		});		
</script>