<?php get_header(); ?>

<div id="main-area">
	<div id="main-top-shadow">
		<div class="container">
			<?php get_template_part('includes/breadcrumbs'); ?>
			<div id="content-area">
				<div id="content-top" class="clearfix">
					<div id="left-area">
					<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
						<div class="entry post clearfix">
							<?php if (get_option('webly_integration_single_top') <> '' && get_option('webly_integrate_singletop_enable') == 'on') echo(get_option('webly_integration_single_top')); ?>
														
							<?php if (get_option('webly_thumbnails') == 'on') { ?>
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
							<?php edit_post_link(esc_html__('Edit this page','Webly')); ?>
						
						</div> <!-- end .entry -->
						
						<?php if (get_option('webly_integration_single_bottom') <> '' && get_option('webly_integrate_singlebottom_enable') == 'on') echo(get_option('webly_integration_single_bottom')); ?>		
						
						<?php if (get_option('webly_468_enable') == 'on') { ?>
								  <?php if(get_option('webly_468_adsense') <> '') echo(get_option('webly_468_adsense'));
								else { ?>
								   <a href="<?php echo esc_url(get_option('webly_468_url')); ?>"><img src="<?php echo esc_url(get_option('webly_468_image')); ?>" alt="468 ad" class="foursixeight" /></a>
						   <?php } ?>   
						<?php } ?>
						
						<?php if (get_option('webly_show_postcomments') == 'on') comments_template('', true); ?>
					<?php endwhile; endif; ?>
					</div> 	<!-- end #left-area -->
					
					<?php get_sidebar(); ?>
				</div> <!-- end #content-top -->
			</div> <!-- end #content-area -->
		</div> <!-- end .container -->
	</div> <!-- end #main-top-shadow -->
</div> <!-- end #main-area -->
		
<?php get_footer(); ?>