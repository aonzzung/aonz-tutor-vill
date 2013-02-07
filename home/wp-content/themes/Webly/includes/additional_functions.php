<?php 

/* Meta boxes */

function webly_add_custom_panels(){
	add_meta_box("et_post_meta", "ET Settings", "webly_post_meta", "page", "normal", "high");
	add_meta_box("et_post_meta", "ET Settings", "webly_post_meta", "post", "normal", "high");
}
add_action("admin_init", "webly_add_custom_panels");

function webly_post_meta($callback_args) {
	global $post;
	
	$post_type = $callback_args->post_type;
	$temp_array = array();

	$temp_array = maybe_unserialize(get_post_meta($post->ID,'et_webly_settings',true));
			
	$et_is_featured = isset( $temp_array['et_is_featured'] ) ? (bool) $temp_array['et_is_featured'] : false;
	$et_fs_variation = isset( $temp_array['et_fs_variation'] ) ? $temp_array['et_fs_variation'] : 1;
	$et_fs_video = isset( $temp_array['et_fs_video'] ) ? $temp_array['et_fs_video'] : '';
	$et_fs_video_embed = isset( $temp_array['et_fs_video_embed'] ) ? $temp_array['et_fs_video_embed'] : '';
	$et_fs_title = isset( $temp_array['et_fs_title'] ) ? $temp_array['et_fs_title'] : '';
	$et_fs_description = isset( $temp_array['et_fs_description'] ) ? $temp_array['et_fs_description'] : '';
	$et_fs_link = isset( $temp_array['et_fs_link'] ) ? $temp_array['et_fs_link'] : ''; ?>
	
	<div id="et_custom_settings" style="margin: 13px 0 17px 4px;">
		<label class="selectit" for="et_is_featured" style="font-weight: bold;">
			<input type="checkbox" name="et_is_featured" id="et_is_featured" value=""<?php checked( $et_is_featured ); ?> /> This <?php echo $post_type; ?> is Featured</label><br/>
		
		<div id="et_settings_featured_options" style="margin-top: 12px;">
			
			<div class="et_fs_setting" style="display: none; margin: 13px 0 26px 4px;">
				<label for="et_fs_variation" style="color: #000; font-weight: bold;"> Featured Slider: </label>				
				<select id="et_fs_variation" name="et_fs_variation">
					<option value="1"<?php if ($et_fs_variation == 1) echo ' selected="selected"'; ?>>Image/Video on the left</option>
					<option value="2"<?php if ($et_fs_variation == 2) echo ' selected="selected"'; ?>>Png Image on the left</option>
					<option value="3"<?php if ($et_fs_variation == 3) echo ' selected="selected"'; ?>>Image/Video on the right</option>
					<option value="4"<?php if ($et_fs_variation == 4) echo ' selected="selected"'; ?>>Description Only</option>
				</select>
				<br />
			</div>
			
			<div class="et_fs_setting" style="display: none; margin: 13px 0 26px 4px;">
				<label for="et_fs_video" style="color: #000; font-weight: bold;"> Video url: </label>
				<input type="text" style="width: 30em;" value="<?php echo esc_url($et_fs_video); ?>" id="et_fs_video" name="et_fs_video" size="67" />
				<br />
				<small style="position: relative; top: 8px;">ex: <code><?php echo htmlspecialchars("http://www.youtube.com/watch?v=WkuHbkaieZ4");?></code></small>
			</div>
			
			<div class="et_fs_setting" style="display: none; margin: 13px 0 26px 4px;">
				<label for="et_fs_video_embed" style="color: #000; font-weight: bold;"> Video Embed Code: </label>
				<br />
				<textarea id="et_fs_video_embed" name="et_fs_video_embed" cols="40" rows="1" tabindex="6" style="display: inline; position: relative; top: 5px; width: 490px; height: 125px;"><?php echo esc_textarea($et_fs_video_embed); ?></textarea>
				<br />
				<small style="position: relative; top: 8px;">Paste embed code if video link cannot be used</small>
			</div>
			
			<div class="et_fs_setting" style="display: none; margin: 13px 0 26px 4px;">
				<label for="et_fs_title" style="color: #000; font-weight: bold;"> Custom Title: </label>
				<input type="text" style="width: 30em;" value="<?php echo esc_attr($et_fs_title); ?>" id="et_fs_title" name="et_fs_title" size="67" />
				<br />
				<small style="position: relative; top: 8px;">ex: <code><?php echo htmlspecialchars("Bringing Ideas To Life");?></code></small>
			</div>
			
			<div class="et_fs_setting" style="display: none; margin: 13px 0 26px 4px;">
				<label for="et_fs_description" style="color: #000; font-weight: bold;"> Description Text: </label>
				<input type="text" style="width: 30em;" value="<?php echo esc_attr($et_fs_description); ?>" id="et_fs_description" name="et_fs_description" size="67" />
				<br />
				<small style="position: relative; top: 8px;">ex: <code><?php echo htmlspecialchars("Join today");?></code></small>
			</div>
						
			<div class="et_fs_setting" style="display: none; margin: 13px 0 26px 4px;">
				<label for="et_fs_link" style="color: #000; font-weight: bold;"> Custom Link: </label>
				<input type="text" style="width: 30em;" value="<?php echo esc_url($et_fs_link); ?>" id="et_fs_link" name="et_fs_link" size="67" />
				<br />
			</div>
			
		</div> <!-- #et_settings_featured_options -->
	</div> <!-- #et_custom_settings -->
		
	<?php
}

add_action('save_post', 'webly_custom_panel_save');
function webly_custom_panel_save($post_id){
	global $pagenow;
	
	if ( 'post.php' != $pagenow ) return $post_id;
		
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
		return $post_id;
		
	$temp_array = array();
		
	if ( !isset($_POST['et_is_featured']) ) {
		if ( get_post_meta( $post_id, "et_webly_settings", true ) ) $temp_array = maybe_unserialize( get_post_meta( $post_id, "et_webly_settings", true ) ); 
		$temp_array['et_is_featured'] = 0;
		update_post_meta( $post_id, "et_webly_settings", $temp_array );
		
		return $post_id;
	}
	
	$temp_array['et_is_featured'] = isset( $_POST["et_is_featured"] ) ? 1 : 0;
	$temp_array['et_fs_variation'] = isset($_POST["et_fs_variation"]) ? (int) $_POST["et_fs_variation"] : '';
	$temp_array['et_fs_video'] = isset($_POST["et_fs_video"]) ? esc_url($_POST["et_fs_video"]) : '';
	$temp_array['et_fs_video_embed'] = isset($_POST["et_fs_video_embed"]) ? stripslashes($_POST["et_fs_video_embed"]) : '';
	$temp_array['et_fs_title'] = isset($_POST["et_fs_title"]) ? esc_html($_POST["et_fs_title"]) : '';
	$temp_array['et_fs_description'] = isset($_POST["et_fs_description"]) ? stripslashes($_POST["et_fs_description"]) : '';
	$temp_array['et_fs_link'] = isset($_POST["et_fs_link"]) ? esc_url($_POST["et_fs_link"]) : '';
		
	update_post_meta( $post_id, "et_webly_settings", $temp_array );
}

add_action( 'admin_enqueue_scripts', 'upload_webly_etsettings_scripts' );
function upload_webly_etsettings_scripts( $hook_suffix ) {
	if ( 'post.php' == $hook_suffix || 'post-new.php' == $hook_suffix ) {
		wp_register_script('et-categories', get_bloginfo('template_directory').'/js/et-categories.js', array('jquery'));
		wp_enqueue_script('et-categories');
	}
}

?>