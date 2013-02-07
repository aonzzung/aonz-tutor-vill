<?php 
/*
Template Name: Sitemap Page
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
							
							<div id="sitemap">
								<div class="sitemap-col">
									<h2><?php esc_html_e('Pages','Webly'); ?></h2>
									<ul id="sitemap-pages"><?php wp_list_pages('title_li='); ?></ul>
								</div> <!-- end .sitemap-col -->
								
								<div class="sitemap-col">
									<h2><?php esc_html_e('Categories','Webly'); ?></h2>
									<ul id="sitemap-categories"><?php wp_list_categories('title_li='); ?></ul>
								</div> <!-- end .sitemap-col -->
								
								<div class="sitemap-col<?php if (!$fullwidth) echo ' last'; ?>">
									<h2><?php esc_html_e('Tags','Webly'); ?></h2>
									<ul id="sitemap-tags">
										<?php $tags = get_tags();
										if ($tags) {
											foreach ($tags as $tag) {
												echo '<li><a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '">' . esc_html( $tag->name ) . '</a></li> ';
											}
										} ?>
									</ul>
								</div> <!-- end .sitemap-col -->
								
								<?php if (!$fullwidth) { ?>
									<div class="clear"></div>
								<?php } ?>
								
								<div class="sitemap-col<?php if ($fullwidth) echo ' last'; ?>">
									<h2><?php esc_html_e('Authors','Webly'); ?></h2>
									<ul id="sitemap-authors" ><?php wp_list_authors('show_fullname=1&optioncount=1&exclude_admin=0'); ?></ul>
								</div> <!-- end .sitemap-col -->
							</div> <!-- end #sitemap -->
							
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