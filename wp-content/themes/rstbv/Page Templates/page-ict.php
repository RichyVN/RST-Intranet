<?php

/* Template Name: Page ICT */

get_header(); ?>
<div id="primary" class="site-content ict-page">
	<div id="content" role="main">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <div class="post" id="post-<?php the_ID(); ?>">
       <div class="entry-content hentry">
				<?php the_content(); ?>
		
			<?php endwhile; ?>
		<?php endif; wp_reset_postdata(); ?>
        <!-- hier komt het custom gedeelte -->               
		
		<?php
			// define weeknumber 
			$weekNumber = date("W");
			
			// check week nr
				if($weekNumber&1) { 
					$week_even = false; 
					//echo '<span>Week is Oneven:</span><br />';
				} else {
					$week_even = true;
					//echo '<span>Week is Even: </span><br /><br />';
				}

			// WP_User_Query arguments
			// Define Rvn
			$args_rvn = array (
				'search'         => 'r.vnaamen@rstbv.nl',
				'search_columns' => array( 'user_email' ),
			);
			// Define Rene
			$args_reb = array (
				'search'         	=> 'r.bolhuis@rstbv.nl',
				'search_columns' => array( 'user_email' ),
			);
			// Define Sander
			$args_sco = array (
				'search'         	=> 's.cornet@rstbv.nl',
				'search_columns' => array( 'user_email' ),
			);
			// Define PeterO
			$args_pol = array (
				'search'         	=> 'p.olislagers@rstbv.nl',
				'search_columns' => array( 'user_email' ),
			);
			// Define Petervb
			$args_pvb = array (
				'search'         	=> 'p.vbokhoven@rstbv.nl',
				'search_columns' => array( 'user_email' ),
			);
			// Define Henk
			$args_hjl = array (
				'search'         	=> 'h.leentfaar@rstbv.nl',
				'search_columns' => array( 'user_email' ),
			);

			// The User Querys
			$user_rvn = new WP_User_Query( $args_rvn );
			$user_reb = new WP_User_Query( $args_reb );
			$user_sco = new WP_User_Query( $args_sco );
			$user_pol = new WP_User_Query( $args_pol );
			$user_pvb = new WP_User_Query( $args_pvb );
			$user_hjl = new WP_User_Query( $args_hjl );
				
			
			// The User Loop Rvn
			if ( ! empty( $user_rvn->results ) ) {
				foreach ( $user_rvn->results as $user ) {
				$rvn_is_vrij = get_user_meta($user->ID, 'custom1', true);
				$rvn_name = $user->display_name;
				$rvn_tel = get_user_meta($user->ID, 'phone', true);
				$rvn_mob = get_user_meta($user->ID, 'mobilephone', true);
				$rvn = $rvn_name . ' - ' . $rvn_mob;
				}
			}		
			// The User Loop Reb
			if ( ! empty( $user_reb->results ) ) {
				foreach ( $user_reb->results as $user ) {
				$reb_is_vrij = get_user_meta($user->ID, 'custom1', true);
				$reb_name = $user->display_name;
				$reb_tel = get_user_meta($user->ID, 'phone', true);
				$reb_mob = get_user_meta($user->ID, 'mobilephone', true);
				$reb = $reb_name . ' - ' . $reb_mob;
				}
			}
			// The User Loop sco
			if ( ! empty( $user_sco->results ) ) {
				foreach ( $user_sco->results as $user ) {
				$sco_is_vrij = get_user_meta($user->ID, 'custom1', true);
				$sco_name = $user->display_name;
				$sco_tel = get_user_meta($user->ID, 'phone', true);
				$sco_mob = get_user_meta($user->ID, 'mobilephone', true);
				$sco = $sco_name . ' - ' . $sco_mob;
				}
			}
			// The User Loop Pol
			if ( ! empty( $user_pol->results ) ) {
				foreach ( $user_pol->results as $user ) {
				$pol_is_vrij = get_user_meta($user->ID, 'custom1', true);
				$pol_name = $user->display_name;
				$pol_tel = get_user_meta($user->ID, 'phone', true);
				$pol_mob = get_user_meta($user->ID, 'mobilephone', true);
				$pol = $pol_name . ' - ' . $pol_mob . ' of 0181 638522';
				}
			}
			// The User Loop pvb
			if ( ! empty( $user_pvb->results ) ) {
				foreach ( $user_pvb->results as $user ) {
				$pvb_is_vrij = get_user_meta($user->ID, 'custom1', true);
				$pvb_name = $user->display_name;
				$pvb_tel = get_user_meta($user->ID, 'phone', true);
				$pvb_mob = get_user_meta($user->ID, 'mobilephone', true);
				$pvb = $pvb_name . ' - ' . $pvb_mob;
				}
			}
			// The User Loop hjl
			if ( ! empty( $user_hjl->results ) ) {
				foreach ( $user_hjl->results as $user ) {
				$hjl_is_vrij = get_user_meta($user->ID, 'custom1', true);
				$hjl_name = $user->display_name;
				$hjl_tel = get_user_meta($user->ID, 'phone', true);
				$hjl_mob = get_user_meta($user->ID, 'mobilephone', true);
				$hjl = $hjl_name . ' - ' . $hjl_mob;
				}
			}
			
            ?> 
      <h2>NOORDZIJDE</h2>
      <div class="table">
			<span>Voor LXE, Software NZ (Tracing), Klanten website RST</span><br /><br />
			<?php
			// display the stuff
			if ($hjl_is_vrij == 'No' && $week_even == true && $pvb_is_vrij == 'No'){
				echo '<strong>Man van dienst: ' . $hjl . ' </strong><br />';
				echo '<span class="greyedout">Bij geen gehoor: ' . $pvb . ' </span><br />';
				
			} elseif ($pvb_is_vrij == 'No' && $week_even == false && $hjl_is_vrij == 'No'){
				echo '<strong>Man van dienst: ' . $pvb . ' </strong><br />';
				echo '<span class="greyedout">Bij geen gehoor: ' . $hjl . ' </span><br />';
			
			} elseif ($hjl_is_vrij == 'No' && $week_even == true && $pvb_is_vrij == 'Yes' || 
						$hjl_is_vrij == 'No' && $week_even == false && $pvb_is_vrij == 'Yes') { 
				echo '<strong>Man van dienst: ' . $hjl . ' </strong><br />';
				if($pvb_is_vrij == 'Yes'){
					echo '<span class="greyedout">Bij geen gehoor: ' . $pvb . ' </span> (is vrij)<br />';
				} else {
					echo '<span class="greyedout">Bij geen gehoor: ' . $pvb . ' </span><br />';
				}
			} elseif ($pvb_is_vrij == 'No' && $week_even == false && $hjl_is_vrij == 'Yes' ||
						$pvb_is_vrij == 'No' && $week_even == true && $hjl_is_vrij == 'Yes'){
				echo '<strong>Man van dienst: ' . $pvb . ' </strong><br />';
				if($hjl_is_vrij == 'Yes'){
					echo '<span class="greyedout">Bij geen gehoor: ' . $hjl . ' </span> (is vrij)<br />';
				} else {
					echo '<span class="greyedout">Bij geen gehoor: ' . $hjl . ' </span><br />';
				}
			} else {
				echo '<i class="fa fa-lg fa-info fa-ico-error"></i> <span class="fa-ico-error">Let op heren: Jullie kunnen niet allebij vrij zijn!!!</span>';
			} 
			?>
		  </div>
			<br /><br />
			
			<h2>ZUIDZIJDE</h2>
			<div class="table">
			<span>Voor Balies, Software ZZ, EDI, Bassysteem</span><br /><br />
			<?php
			// display the stuff
			if ($pol_is_vrij == 'No' && $week_even == true && $sco_is_vrij == 'No'){
				echo '<strong>Man van dienst: ' . $pol . ' </strong><br />';
				echo '<span class="greyedout">Bij geen gehoor: ' . $sco . ' </span><br />';
				
			} elseif ($sco_is_vrij == 'No' && $week_even == false && $pol_is_vrij == 'No'){
				echo '<strong>Man van dienst: ' . $sco . ' </strong><br />';
				echo '<span class="greyedout">Bij geen gehoor: ' . $pol . ' </span><br />';
			
			} elseif ($pol_is_vrij == 'No' && $week_even == true && $sco_is_vrij == 'Yes' || 
						$pol_is_vrij == 'No' && $week_even == false && $sco_is_vrij == 'Yes') { 
				echo '<strong>Man van dienst: ' . $pol . ' </strong><br />';
				if($sco_is_vrij == 'Yes'){
					echo '<span class="greyedout">Bij geen gehoor: ' . $sco . ' </span> (is vrij)<br />';
				} else {
					echo '<span class="greyedout">Bij geen gehoor: ' . $sco . ' </span><br />';
				}
			} elseif ($sco_is_vrij == 'No' && $week_even == false && $pol_is_vrij == 'Yes' ||
						$sco_is_vrij == 'No' && $week_even == true && $pol_is_vrij == 'Yes'){
				echo '<strong>Man van dienst: ' . $sco . ' </strong><br />';
				if($pol_is_vrij == 'Yes'){
					echo '<span class="greyedout">Bij geen gehoor: ' . $pol . ' </span> (is vrij)<br />';
				} else {
					echo '<span class="greyedout">Bij geen gehoor: ' . $pol . ' </span><br />';
				}
			} else {
					echo '<i class="fa fa-lg fa-info fa-ico-error"></i> <span class="fa-ico-error">Let op heren: Jullie kunnen niet allebij vrij zijn!!!</span>';
			}
			?>
		</div>
			<br /><br />
			
			<h2>SYSTEEMBEHEER</h2>
			<div class="table">
      <span>Voor PC's, Printers, Netwerkzaken, Telefoons, Email</span><br /><br />
			<?php
			// display the stuff
			if ($rvn_is_vrij == 'No' && $week_even == true && $reb_is_vrij == 'No'){
				echo '<strong>Man van dienst: ' . $rvn . ' </strong><br />';
				echo '<span class="greyedout">Bij geen gehoor: ' . $reb . ' </span><br />';
				
			} elseif ($reb_is_vrij == 'No' && $week_even == false && $rvn_is_vrij == 'No'){
				echo '<strong>Man van dienst: ' . $reb . ' </strong><br />';
				echo '<span class="greyedout">Bij geen gehoor: ' . $rvn . ' </span><br />';
			
			} elseif ($rvn_is_vrij == 'No' && $week_even == true && $reb_is_vrij == 'Yes' || 
						$rvn_is_vrij == 'No' && $week_even == false && $reb_is_vrij == 'Yes') { 
				echo '<strong>Man van dienst: ' . $rvn . ' </strong><br />';
				if($reb_is_vrij == 'Yes'){
					echo '<span class="greyedout">Bij geen gehoor: ' . $reb . ' </span> (is vrij)<br />';
				} else {
					echo '<span class="greyedout">Bij geen gehoor: ' . $reb . ' </span><br />';
				}
			} elseif ($reb_is_vrij == 'No' && $week_even == false && $rvn_is_vrij == 'Yes' ||
						$reb_is_vrij == 'No' && $week_even == true && $rvn_is_vrij == 'Yes'){
				echo '<strong>Man van dienst: ' . $reb . ' </strong><br />';
				if($rvn_is_vrij == 'Yes'){
					echo '<span class="greyedout">Bij geen gehoor: ' . $rvn . ' </span> (is vrij)<br />';
				} else {
					echo '<span class="greyedout">Bij geen gehoor: ' . $rvn . ' </span><br />';
				}
			} else {
				echo '<i class="fa fa-lg fa-info fa-ico-error"></i> <span class="fa-ico-error">Let op heren: Jullie kunnen niet allebij vrij zijn!!!</span>';
			}
			?>
       </div>        
            <br /><br />
			<h3>Het ICT Reglement.</h3>

			<p>In dit document staat beschreven wat uw rechten, plichten en gebruikersrechten zijn.
			De PC waar u achter zit is en blijft eigendom van RST. Het volledig reglement is hier te vinden. 
			ICT reglement.</p>

			<h3>Tips en Trucs.</h3>

			<p>Hoe doe ik dat? Is een veel gestelde vraag. 
			Voor deze reden is er nu een pagina met Tips en Trucs waar een aantal zaken uitgelegd worden m.b.t. bijvoorbeeld het Microsoft Office pakket.</p>

			<p>Dit wordt regelmatige aangevuld met nieuwe onderwerpen die voor iedereen handig zou kunnen zijn. 
			Heeft u zelf een handige tip geef die dan even door aan de helpdesk via automatisering@rstbv.nl.</p>

		</div><!-- #entry-content -->
      </div><!-- #post -->
  	</div><!-- #content -->
</div><!-- #primary -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>