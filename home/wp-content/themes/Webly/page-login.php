<?php 
/*
Template Name: Login Page
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
							
							<div id="et-login">
								<div class='et-protected'>
									<div class='et-protected-form'>
										<form action='<?php echo home_url(); ?>/wp-login.php' method='post'>
											<p><label><?php esc_html_e('Username','Webly'); ?>: <input type='text' name='log' id='log' value='<?php echo esc_attr($user_login); ?>' size='20' /></label></p>
											<p><label><?php esc_html_e('Password','Webly'); ?>: <input type='password' name='pwd' id='pwd' size='20' /></label></p>
											<input type='submit' name='submit' value='Login' class='etlogin-button' />
										</form> 
									</div> <!-- .et-protected-form -->
									<p class='et-registration'><?php esc_html_e('Not a member?','Webly'); ?> <a href='<?php echo site_url('wp-login.php?action=register', 'login_post'); ?>'><?php esc_html_e('Register today!','Webly'); ?></a></p>
								</div> <!-- .et-protected -->
							</div> <!-- end #et-login -->
							
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