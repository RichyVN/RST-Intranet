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

//Noordzijde             
$args1 = array (
    'post_type'              => array( 'kranen' ),
    'order'                  => 'ASC',
    'orderby'                => 'title',
    'meta_query'             => array(
       array(
        'key'       => 'kade',
        'value'     => 'NZ',
        'compare'   => '=',
       ),
    ),
);
$kranennz = new WP_Query( $args1 ); 

//Zuidzijde 
$args2 = array (
    'post_type'              => array( 'kranen' ),
    'order'                  => 'ASC',
    'orderby'                => 'title',
    'meta_query'             => array(
       array(
        'key'       => 'kade',
        'value'     => 'ZZ',
        'compare'   => '=',
       ),
    ),
);
$kranenzz = new WP_Query( $args2 );

get_header(); ?>
	<div id="primary-kranen" class="site-content">
		<div id="content" role="main">
			<header class="archive-header">
				<h1 class="kranen-title">
          <i class="fa fa-lg fa-gears fa-ico-default"></i> Beschikbare Kranen</h1>
          	<div class="legenda" align="center">
            	<i class="fa fa-lg fa-exclamation-circle fa-ico-info"></i> = Extra Informatie
              <i class="fa fa-lg fa-check-circle fa-ico-okay"></i> = In bedrijf
              <i class="fa fa-lg fa-wrench fa-ico-default"></i> = In onderhoud
              <i class="fa fa-lg fa-ban fa-ico-error"></i> = In storing
            </div>
			</header><!-- .archive-header -->
            <div class="entry-content">
                <div class="kranen">
                    <h4>Zuidzijde kranen</h4>
                    <table class="kraan-table" cellpadding="3" cellspacing="3">
                         <thead>               
                             <tr valign="baseline">
                                <th width="8%" scope="col">Kraan </th>
                                <th width="2%" scope="col">St</th>
                                <th width="60%" scope="col">Toelichting</th>
                                <th width="10%" scope="col">Rijen</th>
                                <th width="10%" scope="col">GeMeld sinds</th>
                                <th width="6%" scope="col">Klant ingelicht?</th>
                            </tr>
                             
                        </thead>
                        <tbody>
                            <!-- start loop hier -->
                            <?php while ($kranenzz->have_posts()) : $kranenzz->the_post(); 
																$kraanid = get_the_ID();			
																$kraan = get_the_title();    
                                $status = get_field('status');
                                $rijen = get_field('rijen');
                                $kade = get_field('kade');
                                $tijd = get_field('tijdstip');
                                $omsch = get_field('omschrijving');
                                $extra = get_field('extra_info');
                                $door = get_field('ingevuld_door');
													      $klanten = get_field('klanten');
													      $params = array('kraanid' => $kraanid);									
                            ?>
                            <tr>
                                <td class="col1" scope="col">
                                 	<?php if (is_user_logged_in()) { ?>
                                  	<a href="<?php echo add_query_arg($params, '/wp-test/edit-kraan'); ?>"><?php echo $kraan;?></a>
                                 	<?php } else {
																		echo $kraan; 
																	} ?>
                                </td>
                                <td class="col2" scope="col">
                                    <?php if ($status == "In bedrijf") {
                                        echo "<i class='fa fa-lg fa-check-circle fa-ico-okay'></i>";
                                    } elseif ($status == "In onderhoud") {
                                        echo "<i class='fa fa-lg fa-wrench fa-ico-default'></i>";
                                    } else {
                                        echo "<i class='fa fa-lg fa-ban fa-ico-error'></i>";
                                    } ?>
                                </td>
                                <td class="col3" scope="col">
                                    <?php 
																		if ($status == "In bedrijf" && $omsch != "") { 
                                    	echo "<i class='fa fa-lg fa-exclamation-circle fa-ico-info'></i><span class='info'> " . $omsch . "</span><br />"; 
                                    } else { 
                                    	echo $omsch ." <br />";
																		} ?>
                                                         
                                </td>
                                <td class="col4" scope="col"><?php echo $rijen; ?></td>
                                <td class="col5" scope="col"><?php echo $tijd; ?></td>
                                <td class="col5" scope="col"><?php if ($klanten == "1") {echo "Ja";} else {echo ""; } ?></td>
                            </tr>
                            <?php endwhile ?> <!-- stop loop hier -->
                        </tbody>
                     </table>

                    <h4>Noordzijde kranen</h4>
                    <table class="kraan-table" cellpadding="3" cellspacing="3">
                         <thead>               
                             <tr valign="baseline">
                                <th width="8%" scope="col">Kraan </th>
                                <th width="2%" scope="col">St</th>
                                <th width="60%" scope="col">Toelichting</th>
                                <th width="10%" scope="col">Rijen</th>
                                <th width="10%" scope="col">GeMeld sinds</th>
                                <th width="6%" scope="col">Klant ingelicht?</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- start loop hier -->
                            <?php while ($kranennz->have_posts()) : $kranennz->the_post(); 
																$kraanid = get_the_ID();			
																$kraan = get_the_title();    
                                $status = get_field('status');
                                $rijen = get_field('rijen');
                                $kade = get_field('kade');
                                $klanten = get_field('klanten');      
													      $tijd = get_field('tijdstip');
                                $omsch = get_field('omschrijving');
                                $extra = get_field('extra_info');
                                $door = get_field('ingevuld_door');
													      $params = array('kraanid' => $kraanid);
                            ?>
                            <tr>
                                <td class="col1" scope="col">
                                	
                                 	<?php if (is_user_logged_in()) { ?>
                                  	<a href="<?php echo add_query_arg($params, '/wp-test/edit-kraan'); ?>"><?php echo $kraan;?></a>
                                 	<?php } else {
																		echo $kraan; 
																	} ?>
                                </td>
                                <td class="col2" scope="col">
                                    <?php if ($status == "In bedrijf") {
                                        echo "<i class='fa fa-lg fa-check-circle fa-ico-okay'></i>";
                                    } elseif ($status == "In onderhoud") {
                                        echo "<i class='fa fa-lg fa-wrench fa-ico-default'></i>";
                                    } else {
                                        echo "<i class='fa fa-lg fa-ban fa-ico-error'></i>";
                                    }
                                    ?>
                                </td>
                                <td class="col3" scope="col">
                                    <?php if ($omsch != "") { 
                                        echo $omsch ." <br />"; } ?>
                                    <?php if ($extra != "") { 
                                        echo "<i class='fa fa-lg fa-exclamation-circle fa-ico-info'></i><span class='info'> " . $extra . "</span><br />"; } ?>                       
                                </td>
                                <td class="col4" scope="col"><?php echo $rijen; ?></td>
                                <td class="col5" scope="col"><?php echo $tijd; ?></td>
                                <td class="col5" scope="col"><?php if ($klanten == "1") {echo "Ja";} else {echo ""; } ?></td>
                            </tr>
                            <?php endwhile ?> <!-- stop loop hier -->
                        </tbody>
                     </table>
<p>Let op: Voor status verandering aan een van de kranen moet je eerst inloggen!</p>
                </div><!-- #kranen -->
            </div><!-- #entry-content -->
				

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>

	