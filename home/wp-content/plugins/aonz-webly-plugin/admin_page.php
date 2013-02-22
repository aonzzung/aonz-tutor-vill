<?php
/*
 * Tell WP about the Admin page
*/
add_action('admin_menu', 'aonz_webly_add_admin_page', 99);
function aonz_webly_add_admin_page()
{
	//Add option page
	add_options_page("Aonz Webly Plugin Options", 'Aonz Webly Plugin', 'administrator', "aonz-webly-plugin", 'aonz_webly_admin_page');
}

/*
 * Output the Admin page
*/
function aonz_webly_admin_page()
{
	global $wpdb,$table_prefix;
	$jobrows = $wpdb->get_results( "SELECT * FROM ".$table_prefix."aonz_tutor_request"." ORDER BY id DESC" );
	?>
<h3>Tutor Job Requests</h3>
<form name="form_tutor_job_request" method="post" action="">
	<table class="bordered">
		<tr>
			<th>ID</th>
			<th>Name</th>
			<th>Email</th>
			<th>Phone</th>
			<th>ระดับชั้นนักเรีบน</th>
			<th>Study Program</th>
			<th>จำนวนนักเรียน</th>
			<th>รายละเอียดวิชา</th>
			<th>สถานที่สอน</th>
			<th>อื่น</th>
			<th>ค่าสอน/ชม.</th>
			<th>ค่าแนะนำ</th>
			<th>เบอร์โทรติวเตอร์</th>
			<th>Published</th>
			<th>Save</th>
			<th>Post to Facebook and Send email</th>
		</tr>
		
		<?php foreach($jobrows as $jobrow) : ?> 
		<tr>
			<form id="form_assign_tutor_<?php echo $jobrow->id; ?>" method="post" action="">
				<td><?php echo $jobrow->id; ?></td>
				<td><?php echo $jobrow->name;?></td>
				<td><?php echo $jobrow->email; ?></td>
				<td><?php echo $jobrow->phone; ?></td>
				<td><?php echo getStudentLevelByLevelId($jobrow->level); ?></td>
				<td><?php echo $jobrow->study_program; ?></td>
				<td>
					<input type="text" name="student_num" size="5" value="<?php echo $jobrow->student_number; ?>">
				</td>
				<td>
					<textarea name="detail" rows="7" cols="18">
					<?php echo $jobrow->detail; ?>
					</textarea>
				</td>
				<td>
					<textarea name="location" rows="7" cols="18">
					<?php echo $jobrow->location; ?>
					</textarea>
				</td>
				<td>
					<textarea name="other" rows="7" cols="18">
						<?php echo $jobrow->other; ?>
					</textarea>
				</td>
				<td>
					<p>
						<input type="text" name="hour_rate" size="15" value="<?php echo $jobrow->hour_rate; ?>">
					</p>
				</td>
				<td>
					<p>
						<input type="text" name="fee" size="15" value="<?php echo $jobrow->fee; ?>">
					</p>
				</td>
				<td>
					<p>
						<input type="text" name="tutor_phonenum" size="15" value="<?php echo $jobrow->assigned_tutor; ?>">
					</p>
				</td>
				<td><?php echo intval($jobrow->published)==1?"Yes":"No"; ?></td>
				<td>
					<p>
						<input type="hidden" name="assign_req_id" value="<?php echo $jobrow->id; ?>">
						<input type="submit" value="Save" style="width:50px;">
					</p>
				</td>
				<td><input type="button" value="Post Facebook" style="width:100px;" onclick="openPublishPanel(<?php echo $jobrow->id; ?>);"/></td>
			</form>
		</tr>
		<tr style="display: none">
			<td>
				<div id="publish_panel_<?php echo $jobrow->id;?>" style="display:none" class="main_modal">
					 <div class="editable">
					 	<div>งานสอนที่  #<?php echo $jobrow->id;?></div>
					 	<div>ระดับชั้น  : <?php echo getStudentLevelByLevelId($jobrow->level); ?></div>
					 	<div>รายละเอียด : <?php echo $jobrow->detail; ?></div>
					 	<div>ดูรายละเอียดงานสอนเพิ่มเติมได้ที่     www.thetutorhut.com</div>
					 </div>
					 <div>
					 	<?php add_fb_publish_button($jobrow->id); ?>
					 	<input id="publish_button" type="button" value="Send email to all tutors" onclick="sendEmails();"/>
					 </div>
				</div>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
</form>
<script type="text/javascript">
jq(document).ready(function() {	

	jq('div.editable').each(function(){
	    this.contentEditable = true;
	});

});

function openPublishPanel(id)
{
	var panel_id = "#publish_panel_"+id;
	jq(panel_id).modal({
		overlayId : 'aonz-simplemodal-overlay',
		containerId : 'aonz-simplemodal-container',
		opacity : 85,
		// onShow: SimpleModalLogin.show,
		position : [ '15%', null ],
		zIndex : 10000
	});
}

function sendEmails() {
	alert("Unimplement feature");
}
</script>
<?php 
}

/**
 * Apply from Simple Facebook Connect Plugin - sfc_publish_meta_box method
 */
function add_fb_publish_button($id) {

	$feed['app_id'] = "575385019158258";
	$feed['method'] = "feed";
	$feed['display'] = 'iframe';
	$feed['scrape'] = 'true';
	//$permalink = apply_filters('sfc_publish_permalink',wp_get_shortlink($post->ID),$post->ID);
	$feed['link'] = "http://localhost";//$permalink;
	//if ($images) $feed['picture'] = $images[0];
	//if ($video) $feed['source'] = $video['og:video'];

// 	$title = get_the_title($post->ID);
// 	$title = strip_tags($title);
// 	$title = html_entity_decode($title, ENT_QUOTES, 'UTF-8');
// 	$title = htmlspecialchars_decode($title);

	$feed['message'] = "my message";
	$feed['name'] = "Aonz Test";//$title;
	$feed['description'] = "Click for more detail";//sfc_base_make_excerpt($post);
	$feed['caption'] = 'My Caption';
	$actions[0]['name'] = 'Share';
	$actions[0]['link'] = 'http://www.facebook.com/share.php?u='.urlencode("http://localhost"/*$permalink*/);

	$feed['actions'] = json_encode($actions);

	$attachment = apply_filters('sfc_publish_manual', $feed, $post);

	// personal publish
	$ui = $feed;

	$html = '<input type="button" class="button-primary" onclick="sfcPersonalPublish'.$id.'(); return false;" value="Publish to your Facebook Profile" />';
	?>
	<div id="aonz-publish-buttons-<?php echo $id; ?>"></div>
	<script type="text/javascript">
	function sfcPersonalPublish<?php echo $id;?>() {
		FB.ui(<?php echo json_encode($ui); ?>);
	}

	<?php
	//Fan Page Id
		$ui['from'] = "513729291999408";
		$ui['to'] = "513729291999408";

	?>
	function sfcPublish<?php echo $id;?>() {
		FB.ui(<?php echo json_encode($ui); ?>);
	}
	<?php
		$html = '<input type="button" class="button-primary" onclick="sfcPublish'.$id.'(); return false;" value="Publish to Facebook Fan Page" />'.$html;
	?>

		jq('#aonz-publish-buttons-<?php echo $id;?>').html(<?php echo json_encode($html); ?>);
		
	</script>
	
	<?php

// 	add_action('sfc_async_init','sfc_publish_show_buttons');
}

function getStudentLevelByLevelId($id)
{
	switch(intval($id))
	{
		case 1:
			return "อนุบาล";
		case 2:
			return "ประถมต้น";
		case 3:
			return "ประถมปลาย";
		case 4:
			return "มัธยมต้น";
		case 5:
			return "มัธยมปลาย";
		case 6:
			return "มหาวิทยาลัย";
		case 7:
			return "บุคคลทั่วไป";
	}
	return "";
}

/**
 * Styles
 */
add_action('admin_head', 'aonz_webly_admin_styles');
function aonz_webly_admin_styles()
{
	?>
	<style type="text/css">
	.fb_dialog {z-index: 1900200 !important;}
	
	/* Rounded Table for job list */
	/* rounded border css3 */
	table.bordered {
	    *border-collapse: collapse; /* IE7 and lower */
	    border-spacing: 0;
	    width: 100%;    
	}
	
	.bordered {
	    border: solid #ccc 1px;
	    -moz-border-radius: 6px;
	    -webkit-border-radius: 6px;
	    border-radius: 6px;
	    -webkit-box-shadow: 0 1px 1px #ccc; 
	    -moz-box-shadow: 0 1px 1px #ccc; 
	    box-shadow: 0 1px 1px #ccc;         
	}
	
	.bordered tr:hover {
	    background: #fbf8e9;
	    -o-transition: all 0.1s ease-in-out;
	    -webkit-transition: all 0.1s ease-in-out;
	    -moz-transition: all 0.1s ease-in-out;
	    -ms-transition: all 0.1s ease-in-out;
	    transition: all 0.1s ease-in-out;     
	}    
	    
	.bordered td, .bordered th {
	    border-left: 1px solid #ccc;
	    border-top: 1px solid #ccc;
	    padding: 10px;
	    text-align: left;    
	    max-width: 100px;
	}
	
	.bordered th {
	    background-color: #dce9f9;
	    background-image: -webkit-gradient(linear, left top, left bottom, from(#ebf3fc), to(#dce9f9));
	    background-image: -webkit-linear-gradient(top, #ebf3fc, #dce9f9);
	    background-image:    -moz-linear-gradient(top, #ebf3fc, #dce9f9);
	    background-image:     -ms-linear-gradient(top, #ebf3fc, #dce9f9);
	    background-image:      -o-linear-gradient(top, #ebf3fc, #dce9f9);
	    background-image:         linear-gradient(top, #ebf3fc, #dce9f9);
	    -webkit-box-shadow: 0 1px 0 rgba(255,255,255,.8) inset; 
	    -moz-box-shadow:0 1px 0 rgba(255,255,255,.8) inset;  
	    box-shadow: 0 1px 0 rgba(255,255,255,.8) inset;        
	    border-top: none;
	    text-shadow: 0 1px 0 rgba(255,255,255,.5); 
	}
	
	.bordered td:first-child, .bordered th:first-child {
	    border-left: none;
	}
	
	.bordered th:first-child {
	    -moz-border-radius: 6px 0 0 0;
	    -webkit-border-radius: 6px 0 0 0;
	    border-radius: 6px 0 0 0;
	}
	
	.bordered th:last-child {
	    -moz-border-radius: 0 6px 0 0;
	    -webkit-border-radius: 0 6px 0 0;
	    border-radius: 0 6px 0 0;
	}
	
	.bordered th:only-child{
	    -moz-border-radius: 6px 6px 0 0;
	    -webkit-border-radius: 6px 6px 0 0;
	    border-radius: 6px 6px 0 0;
	}
	
	.bordered tr:last-child td:first-child {
	    -moz-border-radius: 0 0 0 6px;
	    -webkit-border-radius: 0 0 0 6px;
	    border-radius: 0 0 0 6px;
	}
	
	.bordered tr:last-child td:last-child {
	    -moz-border-radius: 0 0 6px 0;
	    -webkit-border-radius: 0 0 6px 0;
	    border-radius: 0 0 6px 0;
	}
	
	tr.disable
	{
		color:grey;
		text-decoration: line-through;
	}
	
	/* Editable Job Detail */
	div.editable {
    width: 300px;
    height: 200px;
    border: 1px solid #ccc;
    padding: 5px;
	}
	
	/* Simple Modal */
	#aonz-simplemodal-overlay {background-color:#ccc;}
	#aonz-simplemodal-container {/*width:370px;*/}
	#aonz-simplemodal-container .message,
	#aonz-simplemodal-container #login_error {background-color: #ffebe8; border:1px solid #c00; margin-bottom:8px; padding:6px; -moz-border-radius:3px; -webkit-border-radius:3px; border-radius:3px;}
	#aonz-simplemodal-container .message {background-color:#ffffe0; border-color:#e6db55;}
	#aonz-simplemodal-container form,
	#aonz-simplemodal-container div.main_modal
	 {background:#fff; border:1px solid #e5e5e5; font-weight:normal; margin-left:0 auto; padding:16px; text-align:left; -moz-border-radius:11px; -webkit-border-radius:11px; border-radius:5px; -moz-box-shadow:rgba(153,153,153,1) 0 4px 18px; -webkit-box-shadow:rgba(153,153,153,1) 0 4px 18px; box-shadow:rgba(153,153,153,1) 0 4px 18px;}
	#aonz-simplemodal-container form label {color:#777; font-size:13px;}
	#aonz-simplemodal-container form p {margin:0;}
	#aonz-simplemodal-container form .forgetmenot {font-size:11px; font-weight:normal; float:left; line-height:19px; margin-bottom:0;}
	#aonz-simplemodal-container form .submit input {background-color:#257ea8; border:none; border:1px solid; color:#fff; font-weight:bold; padding:3px 10px; font-size:12px; -moz-border-radius:11px; -webkit-border-radius:11px; border-radius:11px; cursor:pointer; text-decoration:none; margin-top:-3px;}
	#aonz-simplemodal-container form .submit {float:right;}
	#aonz-simplemodal-container form .submit input.simplemodal-close {background-color:#c00;}
	#aonz-simplemodal-container .title {color:#257ea8; font-size:18px; padding-bottom:12px;}
	#aonz-simplemodal-container .nav {clear:both; color:#888; padding-top:16px; text-align:center;}
	#aonz-simplemodal-container .nav a {color:#888;}
	#aonz-simplemodal-container .reg_passmail {clear:both; color:#666; font-weight:bold; padding-bottom:16px; text-align:center;}
	#aonz-simplemodal-container .user_pass,
	#aonz-simplemodal-container .user_login,
	#aonz-simplemodal-container .user_email {font-size:24px; width:97%; padding:3px; margin-top:2px; margin-right:6px; margin-bottom:16px; border:1px solid #e5e5e5; background:#fbfbfb;}
	#aonz-simplemodal-container .rememberme {vertical-align:middle;}
	.aonz-simplemodal-activity {background:url(../img/default/loading.gif) center no-repeat; height:16px; margin-bottom:12px;}
</style>
	<?php 
}

/**
 * ================================
 * Form Submit Process
 * ================================
 */
global $wpdb;
if( isset($_POST['assign_req_id']))
{
	$wpdb->update($table_prefix."aonz_tutor_request", 
		array(
		'assigned_tutor' => $_POST['tutor_phonenum'],	// tutor phone num
		),
		array( 'ID' => $_POST['assign_req_id'] ));
}
?>