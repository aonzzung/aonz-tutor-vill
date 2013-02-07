<?php 
/*
Template Name: Search Page
*/
?>
<?php 
	$et_ptemplate_settings = array();
	$et_ptemplate_settings = maybe_unserialize( get_post_meta($post->ID,'et_ptemplate_settings',true) );
	
	$fullwidth = isset( $et_ptemplate_settings['et_fullwidthpage'] ) ? (bool) $et_ptemplate_settings['et_fullwidthpage'] : false;
?>

<?php get_header(); ?>

<div id="main-area" <?php if($fullwidth) echo ('class="fullwidth"');?>>
	<div id="main-top-shadow">
		<div class="container">
			<?php get_template_part('includes/breadcrumbs'); ?>
			<div id="content-area">
				<div id="content-top" class="clearfix">
					<div id="left-area">
					<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
						<div class="entry post clearfix">							
							<?php if (get_option('webly_page_thumbnails') == 'on') { ?>
								<?php 
									$thumb = '';
									$width = 200;
									$height = 200;
									$classtext = 'post-thumb';
									$titletext = get_the_title();
									$thumbnail = get_thumbnail($width,$height,$classtext,$titletext,$titletext,false,'Entry');
									$thumb = $thumbnail["thumb"];
								?>
								
								<?php if($thumb <> '') { ?>
									<div class="post-thumbnail">
										<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height, $classtext); ?>
										<span class="post-overlay"></span>
									</div> 	<!-- end .post-thumbnail -->
								<?php } ?>
							<?php } ?>
							
							<?php the_content(); ?>
							<?php wp_link_pages(array('before' => '<p><strong>'.esc_html__('Pages','Webly').':</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
							
							<div id="et-search">
								<div id="et-search-inner" class="clearfix">
									<p id="et-search-title"><span><?php esc_html_e('search this website','Webly'); ?></span></p>
									<form action="<?php echo home_url(); ?>" method="get" id="et_search_form">
										<div id="et-search-left">
											<p id="et-search-word"><input type="text" id="et-searchinput" name="s" value="<?php esc_attr_e('search this site...','Webly'); ?>" /></p>
																			
											<p id="et_choose_posts"><label><input type="checkbox" id="et-inc-posts" name="et-inc-posts" /> <?php esc_html_e('Posts','Webly'); ?></label></p>
											<p id="et_choose_pages"><label><input type="checkbox" id="et-inc-pages" name="et-inc-pages" /> <?php esc_html_e('Pages','Webly'); ?></label></p>
											<p id="et_choose_date">
												<select id="et-month-choice" name="et-month-choice">
													<option value="no-choice"><?php esc_html_e('Select a month','Webly'); ?></option>
													<?php 
														global $wpdb, $wp_locale;
														
														$selected = '';
														$query = "SELECT YEAR(post_date) AS `year`, MONTH(post_date) AS `month`, count(ID) as posts FROM $wpdb->posts GROUP BY YEAR(post_date), MONTH(post_date) ORDER BY post_date DESC";
														
														$arcresults = $wpdb->get_results($query);
																															
														foreach ( (array) $arcresults as $arcresult ) {
															if ( isset($_POST['et-month-choice']) && ( $_POST['et-month-choice'] == ($arcresult->year . $arcresult->month) ) ) {
																$selected = ' selected="selected"';
															}
															echo "<option value='{$arcresult->year}{$arcresult->month}'{$selected}>{$wp_locale->get_month($arcresult->month)}" . ", {$arcresult->year}</option>";
															if ( $selected <> '' ) $selected = '';
														}
													?>
												</select>
											</p>
										
											<p id="et_choose_cat"><?php wp_dropdown_categories('show_option_all=Choose a Category&show_count=1&hierarchical=1&id=et-cat&name=et-cat'); ?></p>
										</div> <!-- #et-search-left -->
										
										<div id="et-search-right">
											<input type="hidden" name="et_searchform_submit" value="et_search_proccess" />
											<input class="et_search_submit" type="submit" value="<?php esc_attr_e('Submit','Webly'); ?>" id="et_search_submit" />
										</div> <!-- #et-search-right -->
									</form>
								</div> <!-- end #et-search-inner -->
							</div> <!-- end #et-search -->
							
							<div class="clear"></div>
							
							<?php edit_post_link(esc_html__('Edit this page','Webly')); ?>
						
						</div> <!-- end .entry -->
												
						<?php if (get_option('webly_show_pagescomments') == 'on') comments_template('', true); ?>
					<?php endwhile; endif; ?>
					</div> 	<!-- end #left-area -->
					
					<?php if (!$fullwidth) get_sidebar(); ?>
				</div> <!-- end #content-top -->
			</div> <!-- end #content-area -->
		</div> <!-- end .container -->
	</div> <!-- end #main-top-shadow -->
</div> <!-- end #main-area -->
		
<?php get_footer(); ?>