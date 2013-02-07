<?php get_header(); ?>

<div id="main-area">
	<div id="main-top-shadow">
		<div class="container">
			<?php if ( get_option('webly_blog_style') == 'false' ){ ?>
				<?php if ( get_option('webly_display_blurbs') == 'on' ){ ?>
					<div id="blurbs" class="clearfix">
						<?php for ($i=1; $i <= 3; $i++) { ?>
							<?php query_posts('page_id=' . get_pageId(html_entity_decode(get_option('webly_home_page_'.$i)))); while (have_posts()) : the_post(); ?>
								<?php 
									global $more; $more = 0;
								?>
								<div class="service<?php if ( $i == 3 ) echo ' last'; ?>">
									<h3 class="title"><?php the_title(); ?></h3>
									<?php 
										$width = 56;
										$height = 56;
										$titletext = get_the_title();

										$thumbnail = get_thumbnail($width,$height,'item-image',$titletext,$titletext,false,'Blurb');
										$thumb = $thumbnail["thumb"];
										$et_service_link = get_post_meta($post->ID,'etlink',true) ? get_post_meta($post->ID,'etlink',true) : get_permalink();
									?>
									<?php if ($thumb <> '') { ?>
										<div class="thumb">
											<a href="<?php echo $et_service_link; ?>">
												<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height, 'item-image'); ?>
												<span class="overlay"></span>
											</a>
										</div> 	<!-- end .thumb -->
									<?php } ?>
									<?php the_content(''); ?>
									<a href="<?php echo $et_service_link; ?>" class="readmore"><span><?php esc_html_e('Read More','Webly'); ?></span></a>
								</div> <!-- end .service -->
							<?php endwhile; wp_reset_query(); ?>
						<?php } ?>
					</div> <!-- #blurbs -->
				<?php } ?>
				
				<?php if ( get_option('webly_display_media') == 'on' ){ ?>
					<div id="recent-projects">
						<div id="recent-projects-right">
							<div id="recent-projects-content" class="clearfix">
								<?php 
									$args=array(
										'showposts' => '5',
										'category__not_in' => (array) get_option('webly_exlcats_media')
									);
									query_posts($args);
								?>
								<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
									<?php 
										$width = 140;
										$height = 72;
										$titletext = get_the_title();
										$thumbnail = get_thumbnail($width,$height,'project-image',$titletext,$titletext,true,'Media');
										$thumb = $thumbnail["thumb"];
										$et_medialink = get_post_meta($post->ID,'et_medialink',true) ? get_post_meta($post->ID,'et_medialink',true) : '';
										$et_videolink = get_post_meta($post->ID,'et_videolink',true) ? get_post_meta($post->ID,'et_videolink',true) : '';
									?>
									<div class="project">
										<?php if ( $et_medialink <> '' ) { ?>
											<a href="<?php echo esc_url($et_medialink); ?>">
										<?php } elseif ( $et_videolink <> '' ) { ?>
											<a href="<?php echo esc_url($et_videolink); ?>" class="et-video et_video_lightbox" title="<?php echo esc_attr($titletext); ?>">
										<?php } else { ?>
											<a href="<?php echo esc_url($thumbnail["fullpath"]); ?>" class="fancybox" rel="media" title="<?php echo esc_attr($titletext); ?>">
										<?php } ?>
												<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height, 'project-image'); ?>
												<span class="zoom-icon"></span>
												<span class="project-overlay"></span>
											</a>
									</div> 	<!-- end .project -->
								<?php endwhile; ?>
								<?php endif; wp_reset_query(); ?>
								
							</div> <!-- end #recent-projects-content -->
						</div> <!-- end #recent-projects-right -->
					</div> <!-- end #recent-projects -->
				<?php } ?>
			<?php } else { ?>
				<div id="content-area">
					<div id="content-top" class="clearfix">
						<div id="left-area">
							<?php get_template_part('includes/entry','home'); ?>
						</div> 	<!-- end #left-area -->
						
						<?php get_sidebar(); ?>
					</div> <!-- end #content-top -->
				</div> <!-- end #content-area -->
			<?php } ?>
		</div> <!-- end .container -->
	</div> <!-- end #main-top-shadow -->
</div> <!-- end #main-area -->

<?php if ( get_option('webly_blog_style') == 'false' && get_option('webly_footer_quote') == 'on' ){ ?>
	<div id="call-to-action-top">
		<div id="call-to-action">
			<div class="container clearfix">
				<p><?php echo get_option('webly_footer_quote_text'); ?>
					<a href="<?php echo esc_url(get_option('webly_footer_quote_url')); ?>" class="readmore"><span><?php esc_html_e('Read More','Webly'); ?></span></a>
				</p>
				<span id="down-arrow"></span>
			</div> <!-- end .container -->	
		</div> <!-- end #call-to-action -->
	</div> <!-- end #call-to-action-top -->
<?php } ?>

<?php get_footer(); ?>