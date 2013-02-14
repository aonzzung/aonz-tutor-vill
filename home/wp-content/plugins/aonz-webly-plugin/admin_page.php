<?php
/*
 * Tell WP about the Admin page
*/
add_action('admin_menu', 'aonz_webly_add_admin_page', 99);
function aonz_webly_add_admin_page()
{
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
		</tr>
		<tr>
			<td colspan="14">
				 <textarea id="content_to_publish" name="content_to_publish" cols="100" rows="4">
				 	ระดับชั้น  : <?php echo getStudentLevelByLevelId($jobrow->level); ?>
				 	จำนวนนักเรียน  : <?php echo $jobrow->student_number; ?>
				 	รายละเอียด : <?php echo $jobrow->detail; ?>
				 	อัตราค่าสอน  : <?php echo $jobrow->hour_rate; ?> /คน/ชม.
				 </textarea>
				 <input id="publish_button" type="button" value="Publish Job" />
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
</form>
<?php 
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
</style>
	<?php 
}
?>