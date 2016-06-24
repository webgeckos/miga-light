<?php
/**
 * Template Name: Home
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Miga
 */
// remove empty p-tags
remove_filter ('the_content', 'wpautop');
remove_filter ('the_excerpt', 'wpautop');
/*********************************************
* Displaying the selected menu style
**********************************************/
$menu_style = get_theme_mod( 'menu_style' );
if ( $menu_style === 'fullpage' ) {
	get_header('fullpage');
} else {
	get_header();
}
/*********************************************
* Displaying the selected intro section (header)
**********************************************/
$bg_attachment = get_theme_mod( 'parallax' );
$header_layout = get_theme_mod( 'header_layout' );

if ( $header_layout === 'jumbo') {?>
<section class="jumbotron text-xs-center" style="background: url('<?php echo get_theme_mod( 'header_bg_img' );?>') no-repeat; background-size: cover; <?php if ($bg_attachment == '1'){?>background-attachment:fixed; transform: scale(1.1);<?php }?>">
	<div class="header-bg-color">
	  <div class="container">
	  	<?php if ( get_theme_mod( 'header_img' ) ) { ?>
	  		<img src="<?php echo get_theme_mod( 'header_img' ); ?>" alt="Logo"/>
	  	<?php } else { ?>
		    <h1 class="jumbotron-heading"><?php echo get_theme_mod( 'headline_header' );?></h1>
		<?php } ?>
		    <p class="lead text-muted"><?php echo get_theme_mod( 'header_subtitle' );?></p>
		    <p>
		    	<?php if ( get_theme_mod( 'first_btn_text' )) {?>
		      	<a href="<?php echo get_theme_mod( 'first_btn_link' );?>" class="first-btn page-scroll"><?php echo get_theme_mod( 'first_btn_text' );?></a>
		      	<?php } if ( get_theme_mod( 'second_btn_text' )) {?>
		      	<a href="<?php echo get_theme_mod( 'second_btn_link' );?>" class="second-btn page-scroll"><?php echo get_theme_mod( 'second_btn_text' );?></a>
		      	<?php } ?>
		    </p>
	  </div>
	</div>
</section>
<?php } else {?>
<!-- Fullpage Intro Section -->
<section class="intro" style="background: url('<?php echo get_theme_mod( 'header_bg_img' );?>') no-repeat; background-size: cover; <?php if ($bg_attachment == '1'){?>background-attachment:fixed;<?php }?>">
	<div class="intro-body header-bg-color">
	    <div class="container">
	      <div class="row">
	        <div class="col-md-12">
	        	<?php if ( get_theme_mod( 'header_img' ) ) { ?>
			  		<img src="<?php echo get_theme_mod( 'header_img' ); ?>" alt="Logo"/>
			  	<?php } else { ?>
		          	<h1 class="brand-heading"><?php echo get_theme_mod( 'headline_header' );?></h1>
		        <?php } ?>
		          	<p class="intro-text"><?php echo get_theme_mod( 'header_subtitle' );?></p>
		          	<p>
			    		<?php if ( get_theme_mod( 'first_btn_text' )) {?>
			      		<a href="<?php echo get_theme_mod( 'first_btn_link' );?>" class="btn-lg first-btn page-scroll"><?php echo get_theme_mod( 'first_btn_text' );?></a>
			      		<?php } if ( get_theme_mod( 'second_btn_text' )) {?>
			      		<a href="<?php echo get_theme_mod( 'second_btn_link' );?>" class="btn-lg second-btn page-scroll"><?php echo get_theme_mod( 'second_btn_text' );?></a>
			      		<?php } ?>
		    	  	</p>
	        </div>
	      </div>
	    </div>
	</div>
</section>
<?php } ?>
<?php
/*********************************************
* Get site layout values from theme customizer
**********************************************/
$sidebar = get_theme_mod('sidebar_layout');
//if fullwidth is selected then display col-lg-12 if not (with sidebar) display col-lg-9
if ($sidebar === 'fullwidth') {
	$col_nr = 12;
} else {
	$col_nr = 9;
}
?>
<!-- display box layout -> full-layout is handled via miga_customizer_css in customizer.php -->
<div id="primary" class="content-area">

	<!-- if left sidebar was selected display left sidebar-->
	<?php if ($sidebar === 'sidebar-left') {?>
		<div class="col-lg-3 side-left">
			<?php get_sidebar();?>
		</div>
	<?php } ?>

	<!-- main content area-->
	<div class="col-lg-<?php echo $col_nr; ?> content-fullwidth">
		<?php
		/**********************************************
		* STARTING THE SECTION LOOP
		***********************************************/
			$sections_args = array(
		        'tax_query' => array(
		            array(
		                'taxonomy' => 'display-locations',
		                'field' => 'slug',
		                'terms' => 'home'
		            )
		        ),
		        'post_type' => 'sections',
		        'post_status' => 'publish',
						'posts_per_page' => 3, // if 'post_per_page' is used 'nopaging' has to be set to default which is false
		        //'nopaging' => true,
		        'order' => 'ASC',
		        'orderby' => 'menu_order'
	    	);

	    $sections = new WP_Query( $sections_args );

	    if ( $sections->have_posts() ) :
	    	$section_nr = 0;
	    	//start the Loop
	    	while ( $sections->have_posts() ) : $sections->the_post();
	    		// declaring variables
	    		$display_option = get_theme_mod( 'display_'.$section_nr );
	    		$posts_nr = get_theme_mod( 'nr_'.$section_nr );
				$content_type = get_theme_mod( 'content_type_'.$section_nr );
				$section_bg_attachment = get_theme_mod( 'parallax_'.$section_nr );
				// apply cssgram filters to the sections images
				$img_filter = get_theme_mod( 'filter_'.$section_nr );
	    		// assigning bootstrap column numbering to a variable for using in a bootstrap col-class
	    		$columns_nr = get_theme_mod('col_'.$section_nr);
		        if ( $columns_nr === '1-column') {
		          $columns = 'col-lg-12';
		        } elseif ($columns_nr === '2-columns') {
		          $columns = 'col-md-6 col-lg-6';
		        } else {
		          $columns = 'col-md-6 col-lg-4';
		        }
		        // choose default display option which is '' if static content type was selected -> otherwise when selecting static content type the display option will still be the last selected option
		        if ( $content_type === 'option-5' || $content_type === 'option-6' || $content_type === 'option-7' ) {
		        	$display_option = 'option-1';
		        }
		        // assigning specific section class to a variable for individual styling
		        if ($display_option === 'option-1') {
		          $section_class = 'static-section';
		        } elseif ($display_option === 'option-2') {
		          $section_class = 'flipcard-section';
		        } elseif ($display_option === 'option-3') {
		          $section_class = 'decks-section';
		        } elseif ($display_option === 'option-4') {
		          $section_class = 'overlay-section';
		        } elseif ($display_option === 'option-5') {
		          $section_class = 'mansory-section';
		        } elseif ($display_option === 'option-6') {
		          $section_class = 'timeline-section';
		        } elseif ($display_option === 'option-7') {
		          $section_class = 'gallery-section';
		        } elseif ($display_option === 'option-8') {
		          $section_class = 'tabs-section';
		        } elseif ($display_option === 'option-9') {
		          $section_class = 'carousel-section';
		        }
		        ?>

	    		<section id="section-<?php echo $section_nr;?>" class="miga-section <?php echo $section_class;?>" style="background: url('<?php echo get_theme_mod( 'bg_img_'.$section_nr );?>') no-repeat; background-size: cover; <?php if ($section_bg_attachment == '1'){?>background-attachment:fixed;<?php }?>">
	    			<div id="section-bg-<?php echo $section_nr;?>" class="section-bg-color" style="background-color: <?php echo get_theme_mod('bg_'.$section_nr);?>;">
		    			<div class="container">
		    			<?php if ( get_theme_mod( 'title_'.$section_nr )) {?>
						  <div class="row headline">
						    <h2 style="color: <?php echo get_theme_mod( 'headline_color_'.$section_nr );?>;"><?php the_title(); ?></h2>
						  </div>

						  <div class="row">
						    <div class="col-xs-12 divider-img">
						      <hr class="divider" style="border-color: <?php echo get_theme_mod( 'headline_color_'.$section_nr );?>">
						    </div>
						  </div>
						<?php } ?>



						  <div class="row section-padding">

						  	<!-- THE FOLLOWING CONTENT TYPES HAVE ONLY ONE DISPLAY OPTION -->
						  	<?php
						  	// first check if one of the static content types was selected -> important in order to display either static or dynamic content type and not both!
						  	if ( $content_type === 'option-5' || $content_type === 'option-6' || $content_type === 'option-7' ) {

					    		if ( $content_type === 'option-5' ) { ?>

										<div class="col-md-8 col-md-offset-2" style="text-align:center;">
										  <h3 class="newsletter-headline" style="color:<?php echo get_theme_mod( 'post_title_color_'.$section_nr );?>;"><?php echo get_theme_mod( 'newsletter_text'.$section_nr );?></h3>
										  <br>
										  <div class="input-group">
										      <input type="email" class="form-control form-news" placeholder="email">
										      <span class="input-group-btn">
										        <a class="btn" style="border-radius: 0;" type="button"><?php _e( 'send', 'miga' ); ?></a>
										      </span>
										  </div>
										  <br>
										</div>

					    		<?php } elseif ( $content_type === 'option-6') {

					    			get_template_part('template-parts/content','contact');

					    		// if content type text/image was selected
									} elseif ( $content_type === 'option-7') {

					    			if ( get_theme_mod( 'img_position_'.$section_nr ) === 'img_left' ) { ?>
						    			<div class="col-md-8">
											<figure class="wow slideInUp <?php echo $img_filter;?>">
												<img src="<?php echo get_theme_mod( 'img_'.$section_nr ); ?>" alt="<?php echo get_theme_mod( 'subtitle_'.$section_nr );?>" />
											</figure>
										</div>
										<div class="col-md-4">
											<div class="card">
												<div class="card-block" style="background-color: <?php echo get_theme_mod('bg_cards_'.$section_nr);?>;">
													<h3 style="color: <?php echo get_theme_mod( 'post_title_color_'.$section_nr );?>"><?php echo get_theme_mod( 'subtitle_'.$section_nr );?></h3>
													<p style="color: <?php echo get_theme_mod( 'text_color_'.$section_nr );?>"><?php echo get_theme_mod( 'textarea_'.$section_nr );?></p>
												</div>
											</div>
										</div>
									<?php } else { ?>
										<div class="col-md-4">
											<div class="card">
												<div class="card-block" style="background-color: <?php echo get_theme_mod('bg_cards_'.$section_nr);?>;">
													<h3 style="color: <?php echo get_theme_mod( 'post_title_color_'.$section_nr );?>"><?php echo get_theme_mod( 'subtitle_'.$section_nr );?></h3>
													<p style="color: <?php echo get_theme_mod( 'text_color_'.$section_nr );?>"><?php echo get_theme_mod( 'textarea_'.$section_nr );?></p>
												</div>
											</div>
										</div>
										<div class="col-md-8">
											<figure class="wow slideInUp <?php echo $img_filter;?>">
												<img src="<?php echo get_theme_mod( 'img_'.$section_nr ); ?>" alt="<?php echo get_theme_mod( 'subtitle_'.$section_nr );?>" />
											</figure>
										</div>
									<?php }

								} // end of content type text/image

					    	} else { // if no static content type was selected display dynamic content type?>
				    		<!-- END OF CONTENT TYPES WITH ONE DISPLAY OPTION -->

				    		<!-- DEPENDING ON THE DISPLAY OPTION SELECTED SOME OPENING TAGS HAS TO BE INCLUDED OUTSIDE THE POST LOOP-->

						  	<!-- if mansory display option is selected card-columns need to be displayed.-->
						  	<?php if ( $display_option === 'option-5' ) {?>
				    			<div class="card-columns">
				    		<?php } ?>

				    		<!-- if filtered gallery display option is selected include the filter categories-->
						  	<?php if ( $display_option === 'option-7' ) {

						  		if ( $content_type === 'option-7' ){
						  			// if content type "portfolio" is selected take taxonomies from portfolio-categories
						  			$taxonomies = array('portfolio-categories');
						  		} elseif ( $content_type === 'option-11' ) {
						  			// take the images category
						  			$taxonomies = array('category-attachment');
						  		} else {
						  			// otherwise take the categories from the post category
						  			$taxonomies = array('category');
						  		}

							 //Set arguments - hide empty terms.
							 $args = array(
							     'hide_empty' => 1
							 );

							 $terms = get_terms( $taxonomies, $args); ?>

						    	<div id='filter'>
						        	<button class='all active'>All</button>

						    <?php if ( $terms ) {

						        foreach ( $terms as $term ) {
						            $terms_name = $term->name;
						            $terms_slug = $term->slug; ?>

						        	<button class='<?php echo $terms_slug; ?>'><?php echo $terms_name; ?></button>

						    	<?php } ?>
						        </div>

						        <div id='filter-cards'>
				    		<?php } // end if?>
				    		<?php } // end if?>
				    		<!-- if slider display option is selected include opening tag and id for slider-->
						  	<?php if ( $display_option === 'option-9' ) {?>
				    			<div id="miga-slider">
				    		<?php } ?>
				    		<!-- END OF INCLUDING OPENING TAGS -->

				    		<?php
				    		/**********************************************
				    		* STARTING THE POST LOOP
				    		***********************************************/

				    		// Set parameters for wp query depending on the content type
				    		// Initiate variables to avoid error: "variable is not defined"
				    		$post_type = '';
							$order = '';
							$orderby = '';
							$cat = '';
							$meta_key = '';
							// Recent posts
							if ( $content_type === 'option-1') {
								$post_type = 'post';
								$order = 'DESC';
								$orderby = 'date';
								$cat = '';
								$meta_key = '';
							// Random posts
							} elseif ( $content_type === 'option-2') {
								$post_type = 'post';
								$order = 'ASC';
								$orderby = 'rand';
								$cat = '';
								$meta_key = '';
							// Posts by category
							} elseif ( $content_type === 'option-3') {
								$post_type = 'post';
								$order = 'ASC';
								$orderby = 'date';
								$cat = get_theme_mod( 'cat_'.$section_nr );
								$meta_key = '';
							// Popular posts (views)
							} elseif ( $content_type === 'option-4') {
								$post_type = 'post';
								$order = 'meta_value_num';
								$orderby = 'date';
								$cat = '';
								$meta_key = 'miga_post_views_count';
							// Popular posts (comments)
							} elseif ( $content_type === 'option-5') {
								$post_type = 'post';
								$order = 'DESC';
								$orderby = 'comment_count';
								$cat = '';
								$meta_key = '';
							// Testimonials
							} elseif ( $content_type === 'option-6') {
								$post_type = 'testimonials';
								$order = 'DESC';
								$orderby = 'date';
								$cat = '';
								$meta_key = '';
							// Portfolio
							} elseif ( $content_type === 'option-7') {
								$post_type = 'portfolio';
								$order = 'DESC';
								$orderby = 'date';
								$cat = '';
								$meta_key = '';
							}
							/**
							* The WordPress Query class.
							* @link http://codex.wordpress.org/Function_Reference/WP_Query
							*
							*/
								// if flipcards, cards decks, cards overlay or filter gallery was selected as display option then exclude post formats link, quote, video and image from args
								if ( $display_option === 'option-2' || $display_option === 'option-3' || $display_option === 'option-4' || $display_option === 'option-7' || $display_option === 'option-8') {

									$args = array(
										//Category Parameters
										'cat'            => $cat,

										//Type & Status Parameters
										'post_type'      => $post_type,
										'post_status'    => 'publish',

										//Order & Orderby Parameters
										'order'          => $order,
										'orderby'        => $orderby,

										//Pagination Parameters
										'posts_per_page' => $posts_nr,

										//Custom Field Parameters
										'meta_key'       => $meta_key,

										//Taxonomy Parameters
										'tax_query' => array(
											'relation' => 'AND',
											array(
												'taxonomy' => 'post_format',
												'field'    => 'slug',
												'terms'    => array( 'post-format-link' ),
												'operator' => 'NOT IN',
											),
											array(
												'taxonomy' => 'post_format',
												'field'    => 'slug',
												'terms'    => array( 'post-format-quote' ),
												'operator' => 'NOT IN',
											),
											array(
												'taxonomy' => 'post_format',
												'field'    => 'slug',
												'terms'    => array( 'post-format-video' ),
												'operator' => 'NOT IN',
											),
											array(
												'taxonomy' => 'post_format',
												'field'    => 'slug',
												'terms'    => array( 'post-format-image' ),
												'operator' => 'NOT IN',
											),
										),
									);
								} else {
								// if mansory or timeline display option selected -> don't exclude post formats
									$args = array(

										//Category Parameters
										'cat'            => $cat,

										//Type & Status Parameters
										'post_type'      => $post_type,
										'post_status'    => 'publish',

										//Order & Orderby Parameters
										'order'          => $order,
										'orderby'        => $orderby,

										//Pagination Parameters
										'posts_per_page' => $posts_nr,

										//Custom Field Parameters
										'meta_key'       => $meta_key,
									);
								}

				    		// Display option Tabs requires two loops one for the navigation and one for the content -> first loop for the navigation of the tabs
				    		if ( $display_option === 'option-8' ) { ?>

				    			<div class="row tabs-container" style="min-height: 500px;">

				                    <?php if ( get_theme_mod( 'tabs_position_'.$section_nr ) === 'tabs_left' ) { ?>

									  <div class="col-lg-2" style="padding:0; height: 100%;"> <!-- required for floating -->
									      <!-- Nav tabs -->
									      <ul class="nav nav-tabs tabs-left" id="miga-tabs">
									        <?php
									        $tabs = new WP_Query( $args );
									        if ( $tabs->have_posts() ) :
									        // get the url of thumbnail image
									        $tabs_item_number = 0;
									            while ( $tabs->have_posts() ) : $tabs->the_post();
									            $tabs_img_id = get_post_thumbnail_id();
									        	$tabs_thumb_url = wp_get_attachment_url( $tabs_img_id, 'miga-thumb', true );?>
									                <li role="presentation" class="text-center <?php if( $tabs_item_number == 0) echo 'active'; ?>" style="background: url('<?php echo $tabs_thumb_url;?>') no-repeat; background-size: cover;">
									                    <a href="<?php echo '#tab_'.$tabs_item_number ?>" role="tab" data-toggle="tab">
									                        <h3 style="color: <?php echo get_theme_mod( 'post_title_color_'.$section_nr );?>;"><?php the_title(); ?></h3>
									                    </a>
									                </li>
									                <?php $tabs_item_number++;
									            endwhile;
									            wp_reset_postdata();
									        endif;
									        ?>
									      </ul>
									  </div>

									  <div class="col-lg-10" style="padding:0; height: 100%;">
									      <!-- Tab panes -->
									      <div class="tab-content">
									        <?php
									        // second loop for tab content
									        $tabs = new WP_Query( $args );
									        if ( $tabs->have_posts() ) :
									        $tabs_item_number = 0;
									            while ( $tabs->have_posts() ) : $tabs->the_post();
									            $tabs_img_id = get_post_thumbnail_id();
									        	$tabs_img_url = wp_get_attachment_url( $tabs_img_id, 'full', true );?>
									                <div role="tabpanel" class="tab-pane fade<?php if( $tabs_item_number == 0) echo ' in active'; ?>" id="<?php echo 'tab_'.$tabs_item_number ?>" style="background: url('<?php echo $tabs_img_url; ?>') no-repeat; background-size: cover;">
									                	<p class="cards-bg <?php echo get_theme_mod( 'animation_'.$section_nr );?>" style="color: <?php echo get_theme_mod( 'text_color_'.$section_nr );?>; background-color: <?php echo get_theme_mod('bg_cards_'.$section_nr);?>;">

									                		<?php if ( true == get_theme_mod( 'excerpt_'.$section_nr, true ) ) :
																echo the_excerpt();
															else :
																echo the_content();
															endif; ?>

									                	</p>
									                </div>
									              <?php $tabs_item_number++;
									            endwhile;
									            wp_reset_postdata();
									        endif;
									        ?>
									      </div>
									  </div>

									<?php } else { ?>

									  <div class="col-lg-10" style="padding:0;">
									  <!-- Tab panes -->
									    <div class="tab-content">
									        <?php
									        // second loop for tab content
									        $tabs = new WP_Query( $args );
									        if ( $tabs->have_posts() ) :
									        $tabs_item_number = 0;
									            while ( $tabs->have_posts() ) : $tabs->the_post();
									            $tabs_img_id = get_post_thumbnail_id();
									        	$tabs_img_url = wp_get_attachment_url( $tabs_img_id, 'full', true );?>
									                <div role="tabpanel" class="tab-pane fade<?php if( $tabs_item_number == 0) echo ' in active'; ?>" id="<?php echo 'tab_'.$tabs_item_number ?>" style="background: url('<?php echo $tabs_img_url; ?>') no-repeat; background-size: cover;">
									                	<p class="cards-bg <?php echo get_theme_mod( 'animation_'.$section_nr );?>" style="color: <?php echo get_theme_mod( 'text_color_'.$section_nr );?>; background-color: <?php echo get_theme_mod('bg_cards_'.$section_nr);?>;">

									                		<?php if ( true == get_theme_mod( 'excerpt_'.$section_nr, true ) ) :
																echo the_excerpt();
															else :
																echo the_content();
															endif; ?>

									                	</p>
									                </div>
									              <?php $tabs_item_number++;
									            endwhile;
									            wp_reset_postdata();
									        endif;
									        ?>
									    </div>
									</div>

									<div class="col-lg-2" style="padding:0;"> <!-- required for floating -->
									  <!-- Nav tabs -->
									  <ul class="nav nav-tabs tabs-right" id="miga-tabs">
									    <?php
									    $tabs = new WP_Query( $args );
									    if ( $tabs->have_posts() ) :
									    $tabs_item_number = 0;
									        while ( $tabs->have_posts() ) : $tabs->the_post();
									        $tabs_img_id = get_post_thumbnail_id();
									    	$tabs_thumb_url = wp_get_attachment_url( $tabs_img_id, 'miga-thumb', true ); ?>
									            <li role="presentation" class="<?php if( $tabs_item_number == 0) echo 'active'; ?>" style="background: url('<?php echo $tabs_thumb_url;?>') no-repeat; background-size: cover;">
									                <a href="<?php echo '#tab_'.$tabs_item_number ?>" role="tab" data-toggle="tab">
									                    <?php the_title(); ?>
									                </a>
									            </li>
									            <?php $tabs_item_number++;
									        endwhile;
									        wp_reset_postdata();
									    endif;
									    ?>
									  </ul>
									</div>

									<?php } ?>
								</div><!-- /.tabs-container -->
                    		<?php }
                    		// End of tabs display option
                    		// Starting the loop for the other display options
					        $query = new WP_Query( $args );
					        while( $query->have_posts() ) : $query->the_post();

					        // get the url of thumbnail image
					        $image_id = get_post_thumbnail_id();
					        $image_url = wp_get_attachment_url( $image_id, 'medium', true );
					        $image_thumb_url = wp_get_attachment_url( $image_id, 'miga-thumb', true );
					        // get the alt-tag of thumbnail image
					        $image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
					        //apply_filters( 'the_content', get_post_meta($post->ID, '_wenc_content_inhoud', true));

				    		/*********************************************
				    		* if option 2 was selected display flipcards
				    		**********************************************/
				    		if ( $display_option === 'option-2' ) {?>

				    			<?php if ( !has_post_format( 'quote' ) && !has_post_format( 'link' ) && !has_post_format( 'video' ) && !has_post_format( 'image' )) { ?>

				    			<div class="<?php echo $columns; ?> flipcard effect-hover">
						          <div class="card-front">
						            <?php
						            if ( has_post_thumbnail() ) {
						              the_post_thumbnail( 'large' );
						            }
						            ?>
						            <h3 class="cards-bg" style="color: <?php echo get_theme_mod( 'post_title_color_'.$section_nr );?>; background-color: <?php echo get_theme_mod('bg_cards_'.$section_nr);?>;"><?php echo the_title(); ?></h3>
						          </div>
						          <div class="card-back cards-bg" style="background-color: <?php echo get_theme_mod('bg_cards_'.$section_nr);?>;">
						            <div class="flipcard-text">
						              <div class="flipcontent" style="color: <?php echo get_theme_mod( 'text_color_'.$section_nr );?>;">

						                <?php if ( true == get_theme_mod( 'excerpt_'.$section_nr, true ) ) :
															echo the_excerpt();
														else :
															echo the_content();
														endif; ?>

						              </div>
						            </div>
						          </div>
						        </div><!-- /.col -->

						        <?php } ?>

						    <?php
				    		}
				    		/*********************************************
				    		* if option 3 was selected display cards decks
				    		**********************************************/
				    		elseif ( $display_option === 'option-3' ) {?>

				    			<?php if ( !has_post_format( 'quote' ) && !has_post_format( 'link' ) && !has_post_format( 'video' ) && !has_post_format( 'image' )) { ?>

				    			<div class="<?php echo $columns; ?>">
						          <div class="card">
						          	<figure class="<?php echo $img_filter;?>">
						            	<img class="card-img-top" src="<?php echo $image_url; ?>" alt="<?php echo $image_alt; ?>">
						            </figure>
						            <div class="card-block cards-bg" style="background-color: <?php echo get_theme_mod('bg_cards_'.$section_nr);?>;">
						              <h3 class="card-title" style="color: <?php echo get_theme_mod( 'post_title_color_'.$section_nr );?>"><?php the_title(); ?></h3>
						              <p class="card-text" style="color: <?php echo get_theme_mod( 'text_color_'.$section_nr );?>">

						              	<?php if ( true == get_theme_mod( 'excerpt_'.$section_nr, true ) ) :
											echo the_excerpt();
										else :
											echo the_content();
										endif; ?>

						              </p>
						              <?php if ( get_theme_mod( 'date_'.$section_nr ) === 1 ) {?>
									  <p class="card-text" style="color: <?php echo get_theme_mod( 'text_color_'.$section_nr );?>"><small class="text-muted"><?php the_time( get_option( 'date_format' ) ); ?></small></p><!-- if multiple post are posted on the same day the_date function only displays the first one-->
									  <?php } ?>
						            </div>
						          </div>
						        </div>

						        <?php } ?>

						    <?php
				    		}
				    		/*********************************************
				    		* if option 4 was selected display cards overlay
				    		**********************************************/
				    		elseif ( $display_option === 'option-4' ) {?>

				    			<?php if ( !has_post_format( 'quote' ) && !has_post_format( 'link' ) && !has_post_format( 'video' ) && !has_post_format( 'image' )) { ?>

				    			<div class="<?php echo $columns; ?>">

				    				<figure class="card-overlay cards-bg <?php echo $img_filter;?>" style="background-color: <?php echo get_theme_mod('bg_cards_'.$section_nr);?>;">
									  <img src="<?php echo $image_url; ?>" alt="<?php echo $image_alt; ?>"/>
									  <div class="image"><img src="<?php echo $image_url; ?>" alt="<?php echo $image_alt; ?>" /></div>
									  <h3 class="header-overlay" style="color: <?php echo get_theme_mod( 'post_title_color_'.$section_nr );?>"><?php echo the_title(); ?></h3>
									  <div class="text-overlay" style="color: <?php echo get_theme_mod( 'text_color_'.$section_nr );?>">

									  	<?php if ( true == get_theme_mod( 'excerpt_'.$section_nr, true ) ) : ?>
												<p><?php echo the_excerpt(); ?></p>
											<?php else : ?>
												<p><?php echo the_content(); ?></p>
											<?php endif; ?>

									</div>
									</figure>

						        </div>

						        <?php } ?>

						    <?php
				    		}
				    		/*********************************************
				    		* if option 5 was selected display cards masonry
				    		**********************************************/
				    		elseif ( $display_option === 'option-5' ) {?>

				    				<?php
				    				if ( has_post_format( 'quote' )) { ?>

									    <div class="card card-block card-inverse card-primary text-xs-center">
							              <blockquote class="card-blockquote">
							                <?php echo the_content(); ?>
							              </blockquote>
							            </div>

									<?php } else if (has_post_format('video')) { ?>

									   	<div class="card">
							              <?php echo the_content(); ?>
							            </div>

									<?php } else if (has_post_format('image')) { ?>

									   	<div class="card">
									   		<figure class="<?php echo $img_filter;?>">
							              		<img class="card-img" src="<?php echo $image_url; ?>" alt="<?php echo $image_alt; ?>">
							              	</figure>
							            </div>

									<?php } else if (has_post_format('link')) {?>

									   	<div class="card card-block">
							              <h3 class="card-title" style="color: <?php echo get_theme_mod( 'post_title_color_'.$section_nr );?>"><?php echo the_title(); ?></h3>
							              <p class="card-text" style="color: <?php echo get_theme_mod( 'text_color_'.$section_nr );?>"><?php echo the_content(); ?></p>
							            </div>

									<?php } else { //standard post format?>

									   <div class="card card-decks">
									   	<figure class="<?php echo $img_filter;?>">
							            	<img class="card-img-top" src="<?php echo $image_url; ?>" alt="<?php echo $image_alt; ?>">
							            </figure>
							            <div class="card-block">
							              <h3 class="card-title" style="color: <?php echo get_theme_mod( 'post_title_color_'.$section_nr );?>"><?php echo the_title(); ?></h3>
							              <p class="card-text" style="color: <?php echo get_theme_mod( 'text_color_'.$section_nr );?>">

							              	<?php if ( true == get_theme_mod( 'excerpt_'.$section_nr, true ) ) :
												echo the_excerpt();
											else :
												echo the_content();
											endif; ?>

							              </p>
							            </div>
							          </div>

									<?php } ?>


				    		<?php
				    		}
				    		/*********************************************
				    		* if option 6 was selected display timeline
				    		**********************************************/
				    		elseif ( $display_option === 'option-6' ) {?>

				    			<div class="timeline-block">
						          <div class="timeline-icon">
						          	<?php
				    				if ( has_post_format( 'quote' )) { ?>

									    <i class="fa fa-commenting fa-2x"></i>

									<?php } else if (has_post_format('video')) { ?>

									   	<i class="fa fa-video-camera fa-2x"></i>

									<?php } else if (has_post_format('image')) { ?>

									   	<i class="fa fa-image fa-2x"></i>

									<?php } else if (has_post_format('link')) {?>

									    <i class="fa fa-link fa-2x"></i>

									<?php } else { //standard post format?>

									   <i class="fa fa-indent fa-2x"></i>

									<?php } ?>
						          </div> <!-- timeline-icon -->

						          <div class="timeline-content">
						            <h3 style="color: <?php echo get_theme_mod( 'post_title_color_'.$section_nr );?>"><?php echo the_title(); ?></h3>
						            <?php if ( has_post_thumbnail() ) {?>
						            	<figure class="<?php echo $img_filter;?>">
						            		<img class="card-img" src="<?php echo $image_url; ?>" alt="<?php echo $image_alt; ?>">
						            	</figure>
						            <?php }?>
						            <p style="color: <?php echo get_theme_mod( 'text_color_'.$section_nr );?>">

						            	<?php if ( true == get_theme_mod( 'excerpt_'.$section_nr, true ) ) :
											echo the_excerpt();
										else :
											echo the_content();
										endif; ?>

						            </p>
						            <?php if ( get_theme_mod( 'date_'.$section_nr ) === 1 ) {?>
						            <span class="date" style="color: <?php echo get_theme_mod( 'text_color_'.$section_nr );?>"><i class="fa fa-clock-o"></i> <?php the_time( get_option( 'date_format' ) ); ?></span><!-- if multiple post are posted on the same day the_date function only displays the first one-->
						            <?php } ?>
						          </div> <!-- timeline-content -->
						        </div> <!-- timeline-block -->

				    		<?php }
				    		/*********************************************
				    		* if option 7 was selected display filtered gallery
				    		**********************************************/
				    		elseif ( $display_option === 'option-7' ) {
				    			// if portfolio was selected as dynamic content
				    			if ( $content_type === 'option-7' ) {
				    				$cat = wp_get_post_terms( get_the_ID(), 'portfolio-categories' );
				    			} else {
				    				$cat = wp_get_post_terms( get_the_ID(), 'category' );
				    			}

				    			$cat_slug = $cat[0]->slug;?>

				    			<?php if ( !has_post_format( 'link' ) && !has_post_format( 'quote' )) { ?>

							        <figure class="card <?php echo $cat_slug; ?>">
							        	<?php the_post_thumbnail('miga-thumb'); ?>
										  <figcaption class="cards-bg" style="background-color: <?php echo get_theme_mod('bg_cards_'.$section_nr);?>;">
										    <h3 style="color: <?php echo get_theme_mod( 'post_title_color_'.$section_nr );?>"><?php echo the_title(); ?></h3>
										  </figcaption><i class="fa fa-eye fa-2x"></i>
										  <a href="<?php echo the_permalink(); ?>"></a>
									</figure>

				    		<?php }
				    		}
				    		/*********************************************
				    		* Display option 8 (tabs) takes place outside of this loop
				    		**********************************************/

				    		/*********************************************
				    		* Display option 9 - Carousel
				    		**********************************************/
				    		elseif ( $display_option === 'option-9' ) {?>

			    				<?php
			    				if ( has_post_format( 'quote' )) { ?>

								    <div class="item">
						              	<div class="col-md-8 col-md-offset-2 item-content">
										  	<blockquote>
											  <p><?php echo the_content(); ?></p>
											  <footer><cite title="Source Title"><?php echo the_title(); ?></cite></footer>
											</blockquote>
										</div>
						            </div>

								<?php } else if (has_post_format('video')) { ?>

								   	<div class="item">
						              <?php echo the_content(); ?>
						            </div>

								<?php } else if (has_post_format('image')) { ?>

								   	<div class="item">
								   		<figure class="<?php echo $img_filter;?>">
						              		<img class="slider-img" src="<?php echo $image_url; ?>" alt="<?php echo $image_alt; ?>">
						              	</figure>
						            </div>

								<?php } else if (has_post_format('link')) {?>

									<div class="item">
								   		<div class="col-md-8 col-md-offset-2 item-content">
											<h3 style="color: <?php echo get_theme_mod( 'post_title_color_'.$section_nr );?>"><?php echo the_title(); ?></h3>
							             	<p style="color: <?php echo get_theme_mod( 'text_color_'.$section_nr );?>"><?php echo the_content(); ?></p>
										</div>
									</div>

								<?php } else { //standard post format?>

								   	<div class="item">
								   		<div class="card">
										  	<figure class="<?php echo $img_filter;?>">
										    	<img class="card-img-top" src="<?php echo $image_url; ?>" alt="<?php echo $image_alt; ?>">
										    </figure>
										    <div class="card-block item-content cards-bg" style="background-color: <?php echo get_theme_mod('bg_cards_'.$section_nr);?>;">
										      <h3 class="card-title" style="color: <?php echo get_theme_mod( 'post_title_color_'.$section_nr );?>"><?php the_title(); ?></h3>
										      <p class="card-text" style="color: <?php echo get_theme_mod( 'text_color_'.$section_nr );?>">

										      	<?php if ( true == get_theme_mod( 'excerpt_'.$section_nr, true ) ) :
													echo the_excerpt();
												else :
													echo the_content();
												endif; ?>

										      </p>
										      <?php if ( get_theme_mod( 'date_'.$section_nr ) === 1 ) {?>
											  <p class="card-text" style="color: <?php echo get_theme_mod( 'text_color_'.$section_nr );?>"><small class="text-muted"><?php the_time( get_option( 'date_format' ) ); ?></small></p><!-- if multiple post are posted on the same day the_date function only displays the first one-->
											  <?php } ?>
										    </div>
										</div>
									</div>

								<?php }
				    		}
								endwhile;
						    wp_reset_query(); ?>

						    <!-- INCLUDING CLOSING DIV TAGS -->

						    <!-- closing div if mansory display option is selected -->
						    <?php if ( $display_option === 'option-5' ) {?>
				    			</div><!-- .card-columns -->
				    		<?php } ?>

				    		<!-- closing div if filtered gallery display option is selected -->
						    <?php if ( $display_option === 'option-7' ) {?>
						    		<div class="hidden"></div>
				    			</div><!-- #filter-cards -->
				    		<?php } ?>

				    		<!-- closing div for carousel display option -->
						    <?php if ( $display_option === 'option-9' ) {?>
				    			</div><!-- .card-columns -->
				    		<?php }

							} // end of the if loop for dynamic content type ?>

						  </div><!-- .row -->

						</div><!-- .container -->
					</div><!-- .section-bg-color -->
				</section>
			<?php
			$section_nr++;
			endwhile;

			the_posts_navigation();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>

	</div><!-- end main content area-->

	<!-- if right sidebar was selected display right sidebar-->
	<?php if ($sidebar === 'sidebar-right') {?>
		<div class="col-lg-3 side-right">
			<?php get_sidebar();?>
		</div>
	<?php } ?>

</div><!-- #primary -->
<?php get_footer();?>
