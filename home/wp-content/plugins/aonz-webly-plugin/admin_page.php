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
	$jobrows = $wpdb->get_results( "SELECT * FROM ".$table_prefix."aonz_tutor_request" );
	?>
<h3>Tutor Job Requests</h3>
<form name="form_tutor_job_request" method="post" action="">
	<table border="1" style="margin: 10px;">
		<tr>
			<th>ID</th>
			<th>Name</th>
			<th>Email</th>
			<th>Phone</th>
			<th>Level</th>
			<th>Student Number</th>
			<th>Detail</th>
			<th>Location</th>
			<th>Other</th>
			<th>Hour Rate</th>
			<th>Fee</th>
			<th>Assigned Tutor</th>
			<th>Published</th>
			<th>Publish Panel</th>
		</tr>
		
		<?php foreach($jobrows as $jobrow) : ?> 
		<tr>
			<td><?php echo $jobrow->id; ?></td>
			<td><?php echo $jobrow->name;?></td>
			<td><?php echo $jobrow->email; ?></td>
			<td><?php echo $jobrow->phone; ?></td>
			<td><?php echo getStudentLevelByLevelId($jobrow->level); ?></td>
			<td><?php echo $jobrow->student_number; ?></td>
			<td><?php echo $jobrow->detail; ?></td>
			<td><?php echo $jobrow->location; ?></td>
			<td><?php echo $jobrow->other; ?></td>
			<td><?php echo $jobrow->hour_rate; ?></td>
			<td><?php echo $jobrow->fee; ?></td>
			<td><?php echo $jobrow->assigned_tutor; ?></td>
			<td><?php echo intval($jobrow->published)==1?"Yes":"No"; ?></td>
			<td><input type="button" value="Open" onclick="openPublishPanel(<?php echo $jobrow->id; ?>);"/></td>
		</tr>
		<tr style="display: none">
			<td>
				<div id="publish_panel_<?php echo $jobrow->id;?>" style="display:none" class="main_modal">
					 <div class="editable">
					 	<div>งานสอนที่  #<?php echo $jobrow->id;?></div>
					 	<div>ระดับชั้น  : <?php echo getStudentLevelByLevelId($jobrow->level); ?></div>
					 	<div>จำนวนนักเรียน  : <?php echo $jobrow->student_number; ?></div>
					 	<div>รายละเอียด : <?php echo $jobrow->detail; ?></div>
					 	<div>อัตราค่าสอน  : <?php echo $jobrow->hour_rate; ?> /คน/ชม.</div>
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
	
	table, caption, tbody, tfoot, thead, tr, th, td {
		margin:0;
		padding:0;
		border:0;
		outline:0;
		font-size:100%;
		vertical-align:baseline;
		background:transparent;
	}
	table {
		table-layout: fixed; 
		width: 100%;
		overflow:hidden;
		border:1px solid #d3d3d3;
		background:#fefefe;
/* 		width:70%; */
		margin:5% auto 0;
		-moz-border-radius:5px; /* FF1+ */
		-webkit-border-radius:5px; /* Saf3-4 */
		border-radius:5px;
		-moz-box-shadow: 0 0 4px rgba(0, 0, 0, 0.2);
		-webkit-box-shadow: 0 0 4px rgba(0, 0, 0, 0.2);
	}
	
	th, td {padding:5px 5px 5px; text-align:center; }
	
	th {padding-top:5px; text-shadow: 1px 1px 1px #fff; background:#e8eaeb;}
	
	td {border-top:1px solid #e0e0e0; border-right:1px solid #e0e0e0; word-wrap: break-word;}
	
	tr.odd-row td {background:#f6f6f6;}
	
	td.first, th.first {text-align:left}
	
	td.last {border-right:none;}
	
	/*
	Background gradients are completely unnecessary but a neat effect.
	*/
	
	td {
		background: -moz-linear-gradient(100% 25% 90deg, #fefefe, #f9f9f9);
		background: -webkit-gradient(linear, 0% 0%, 0% 25%, from(#f9f9f9), to(#fefefe));
	}
	
	tr.odd-row td {
		background: -moz-linear-gradient(100% 25% 90deg, #f6f6f6, #f1f1f1);
		background: -webkit-gradient(linear, 0% 0%, 0% 25%, from(#f1f1f1), to(#f6f6f6));
	}
	
	th {
		background: -moz-linear-gradient(100% 20% 90deg, #e8eaeb, #ededed);
		background: -webkit-gradient(linear, 0% 0%, 0% 20%, from(#ededed), to(#e8eaeb));
	}
	
	/*
	I know this is annoying, but we need additional styling so webkit will recognize rounded corners on background elements.
	Nice write up of this issue: http://www.onenaught.com/posts/266/css-inner-elements-breaking-border-radius
	
	And, since we've applied the background colors to td/th element because of IE, Gecko browsers also need it.
	*/
	
	tr:first-child th.first {
		-moz-border-radius-topleft:5px;
		-webkit-border-top-left-radius:5px; /* Saf3-4 */
	}
	
	tr:first-child th.last {
		-moz-border-radius-topright:5px;
		-webkit-border-top-right-radius:5px; /* Saf3-4 */
	}
	
	tr:last-child td.first {
		-moz-border-radius-bottomleft:5px;
		-webkit-border-bottom-left-radius:5px; /* Saf3-4 */
	}
	
	tr:last-child td.last {
		-moz-border-radius-bottomright:5px;
		-webkit-border-bottom-right-radius:5px; /* Saf3-4 */
	}
	
	div.editable {
    width: 300px;
    height: 200px;
    border: 1px solid #ccc;
    padding: 5px;
	}
	
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
?>