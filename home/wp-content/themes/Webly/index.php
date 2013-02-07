<?php get_header(); ?>

<div id="main-area">
	<div id="main-top-shadow">
		<div class="container">
			<?php get_template_part('includes/breadcrumbs'); ?>
			<div id="content-area">
				<div id="content-top" class="clearfix">
					<div id="left-area">
						<?php get_template_part('includes/entry'); ?>
					</div> 	<!-- end #left-area -->
					
					<?php get_sidebar(); ?>
				</div> <!-- end #content-top -->
			</div> <!-- end #content-area -->
		</div> <!-- end .container -->
	</div> <!-- end #main-top-shadow -->
</div> <!-- end #main-area -->
		
<?php get_footer(); ?>