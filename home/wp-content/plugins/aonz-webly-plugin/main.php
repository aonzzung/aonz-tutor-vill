<?php
/* Plugin Name: aonz-webly-plugin
* Description: aonz plugin for webly theme.
* Author: Aonzzung
* Version: 1.0
* Author URI: 
* Plugin URI: 
*/
?>
<?php 
/**
 * class AonzWeblyPlugin
 */
class AonzWeblyPlugin
{
	/**
	 * Plugin Folder
	 * @access protected
	 * @var string
	 */
	protected $plugin_folder = null;

	/**
	 * Plugin Directory
	 * @access protected
	 * @var string
	 */
	protected $plugin_dir = null;

	/**
	 * Plugin Url
	 * @access protected
	 * @var string
	 */
	protected $plugin_url = null;

	/**
	 * Plugin File
	 * @access protected
	 * @var string
	 */
	protected $plugin_file = null;
	
	/**
	 * Constructor
	 * Do nothing
	 * @access public
	 */
	public function __construct()
	{
	} // function construct()
	
	public function init()
	{
		$this->plugin_folder = substr(dirname(__FILE__), strrpos(dirname(__FILE__), DIRECTORY_SEPARATOR) + 1);
		$this->plugin_url = WP_PLUGIN_URL . '/' . $this->plugin_folder;
		$this->plugin_dir = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $this->plugin_folder;
		$this->plugin_file = $this->plugin_folder . '/' . basename(__FILE__);

		// add style
		wp_enqueue_style('aonz-css', $this->plugin_url . "/css/aonz-default.css", false, $this->version, 'screen');
// 		wp_enqueue_style('wizard-css', $this->plugin_url . "/css/wizard.css", false, $this->version, 'screen');
		
		// adding javascript files
		wp_enqueue_script( 'jquery-simplemodal', $this->plugin_url . '/js/jquery.simplemodal.js', array( 'jquery' ) );
		wp_enqueue_script( 'jquery-validate-js', $this->plugin_url . '/js/jquery.validate.js', array( 'jquery' ) );
		wp_enqueue_script( 'aonz-js', $this->plugin_url . '/js/aonz-js.js', array( 'jquery' ) );
		
		// declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
		wp_localize_script( 'aonz-js', 'AonzAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		
		// Add Tutor Registration
		add_action('wp_footer', array($this, 'tutor_register_modal'));
		
		// Add Student Registration
		add_action('wp_footer', array($this, 'student_registration_empty_area'));
		add_action('wp_footer', array($this, 'student_registration_form_modal'));
		add_action('wp_footer', array($this, 'job_list_modal'));
		
		// Student Registration Callback
		add_action('wp_ajax_aonz_student_registration', array($this, 'aonz_student_registration_callback'));
		add_action('wp_ajax_nopriv_aonz_student_registration', array($this, 'aonz_student_registration_callback'));
		
		add_action('wp_ajax_get_job_list', array($this, 'get_job_list_callback'));
		
		//Captcha
		add_action('aonz_study_regis_captcha', 'display_aonz_captcha', 10, 1);
		add_filter('aonz_study_regis_check_captcha', 'cptch_check_custom_form');
	}
	
	/**
	 * This function will exploit wp-fb-autoconnect plugin
	 */
	function tutor_register_modal()
	{
		// Write fb login function
		jfb_output_facebook_instapopup();//"tutor_register_callback"
		?>
			<div id="tutor_register_modal" style="display:none;width: 500px;height: 300px;">
				<div class="title" style="width:70%;margin: 0px auto;font-size: x-large;padding-top: 30px">สมัครติวเตอร์ง่ายๆด้วย Facebook</div>
				<div style="width:70%;margin: 0px auto;font-size:medium;">ไม่ต้องกรอกแบบฟอร์มให้ยุ่งยาก เพียงคลิ๊กสมัครด้วยบัญชี Facebook เท่านั้น</div>
				<div style="width:70%;margin: 0px auto;padding-top: 30px">
					<a href="#" onclick="showInstaPopup();return false;" class="aonz-fb-login-button"></a>
				</div>
			</div>
		<?php 
	}
	
	/**
	 * Empty area for adding student_register_modal later
	 */
	function job_list_modal()
	{
		?>
			<div id="job_list_modal" style="display:none;width: 700px;height: 500px;">
			<!-- Form will be inserted here -->
			</div>
		<?php 	
	}
		
	/**
	 * Empty area for adding student_register_modal later
	 */
	function student_registration_empty_area()
	{
	?>
		<div id="student_register_empty_area">
			<div id="student_register_modal" style="display:none;width: 700px;height: 500px">
			<!-- Form will be inserted here -->
			</div>
		</div>
	<?php 	
	}
	
	/**
	 * Student registration
	 */
	function student_registration_form_modal()
	{
		?>
		<div id="student_register_modal_wrapper" style="display: none">
			<form class="cmxform" id="student_register_form" method="post" action="" style="width: 700px;height: 500px;overflow-y: scroll;">
			 <fieldset>
			   <legend class="title">กรอกแบบฟอร์มเพื่อสมัครเรียน</legend>
			   <p>
			     <label for="cname">ชื่อ-นามสกุล ผู้ติดต่อ</label>
			     <span class="required">*</span>
			     <input id="cname" name="name" type="text" size="25" class="required form_input_text" minlength="2" />
			   </p>
			   <p>
			     <label for="cphone">เบอร์โทรศัพท์ที่ติดต่อได้</label>
			     <span class="required">*</span>
			     <span style="display:block;">กรอกตัวเลขติดกันทั้งหมด ตัวอย่าง 0821234567</span>
			     <input id="cphone" name="phone" type="text" size="25"  class="phone form_input_text" value="" />
			   </p>
			   <p>
			     <label for="cemail">อีเมล์</label>
			     <input id="cemail" name="email" size="25" type="text"  class="email form_input_text" />
			   </p>
			   <p>
					
			   </p>
			   <p>
			     <label for="clevel">ระดับชั้นผู้เรียน</label>
			     <span class="required">*</span>
			     <span style="display:block">
			     	<span class="required">โปรดเลือกหลักสูตรให้ถูกต้อง</span><br />
			     	<input type="radio" name="study_program" value="th" checked="checked" />หลักสูตรภาษาไทย<br />
					<input type="radio" name="study_program" value="en" />หลักสูตรภาษาอังกฤษ
				 </span>	
			     <select id="clevel" name="level">
					  <option value="1">อนุบาล</option>
					  <option value="2">ประถมต้น</option>
					  <option value="3" selected="selected">ประถมปลาย</option>
					  <option value="4">มัธยมต้น</option>
					  <option value="5">มัธยมปลาย</option>
					  <option value="6">มหาวิทยาลัย</option>
					  <option value="7">บุคคลทั่วไป</option>
				 </select>
				 <span id="rate_hr" class="required" style="font-weight: bold;font-size: medium;">อัตราค่าเรียน 300 บาท/คน/ชม.</span>
				 <input type="hidden" id="rate" value="300"/>
			   </p>	  
			   <p>
			     <label for="cstudent_number">จำนวนผู้เรียน</label>
			     <span class="required">*</span>
		     	 <select id="cstudent_number" name="student_number">
				  	<option value="1" selected="selected">1</option>
				  	<option value="2">2</option>
				  	<option value="3">3</option>
				  	<option value="4">4</option>
				  	<option value="5">5</option>
				 </select>
			   </p>
			   <p>
			     <label for="cdetail">รายละเอียดวิชาที่ต้องการเรียน</label>
			     <span class="required">*</span>
			     <span style="display:block;">ตัวอย่างการกรอก(ระบุหลายวิชาได้) : 
			   	 	<br />วิชาคณิตศาตร์ - ป.5 - ทุกวันจันทร์ - เวลา 17.00 - 19.00 น.
			   	 	<br />วิชาวิทยาศาสตร์ - ป.5 - ทุกวันพุธ - เวลา 17.00 - 19.00 น. 
			   	 </span>
			     <textarea id="cdetail" name="detail" cols="22"  class="required"></textarea>
			   </p>
			   <p>
			     <label for="clocation">สถานที่เรียนที่สะดวก</label>
			     <span class="required">*</span>
			     <span style="display:block;">กรุณาระบุสถานที่ที่สะดวกสำหรับเรียน เช่น  บ้านเลขที่ ถนน ซอย จุดสังเกต(ถ้ามี) ให้ชัดเจน</span>	
			     <textarea id="clocation" name="location" cols="22"  class="required"></textarea>
			   </p>
			   <p>
			     <label for="cother">รายละเอียดอื่นๆ เช่น ระบุครูผู้สอน ฯลฯ</label>
			     <textarea id="cother" name="other" cols="22"></textarea>
			   </p>
			   <p>
			   	<label>ระบบป้องกันความปลอดภัย</label>
			   	<span class="required" style="display:block;">*โปรดกรอก "ตัวเลข" ที่ทำให้สมการข้างล่างถูกต้อง</span>
			   	<?php do_action('aonz_study_regis_captcha');?>
			   </p>
			   <p>
			   	 <input class="submit" type="submit" value="Submit"/>
			   	 <input type="button" class="simplemodal-close" value="Cancel"/>
			 	</p>
			 </fieldset>
			 </form>
			 
			 <form id="request_success" style="display:none">
				<p class="title" align="center">ระบบได้รับแบบฟอร์มสมัครเรียนของท่านเรียบร้อยแล้ว<br />
				ทางทีมงานจะติดต่อกลับตามเบอร์โทรศัพท์ที่ท่านได้ระบุไว้อย่างเร็วที่สุด
				</p>
				<input type="button" class="simplemodal-close" value="Close"/>
			</form>
			<form id="request_fail" style="display:none">
				<p class="title" align="center">
				ข้อมูลที่กรอกไม่ถูกต้อง กรุณากรอกแบบฟอร์มใหม่อีกครั้ง<br />
				ทางทีมงานขออภัยในความไม่สะดวก
				</p>
				<input type="button" class="simplemodal-close" value="Close"/>
			</form>
		</div>
		<?php 
	}
	
	function get_job_list_callback()
	{
		global $wpdb,$table_prefix;
		
		$jobrows = $wpdb->get_results( "SELECT * FROM ".$table_prefix."aonz_tutor_request" );
		?>
		<form style="width: 700px;height: 500px;overflow-y: scroll;">
			<h3>รายการงานสอน</h3>
			<table class="bordered">
				<thead>
				<tr>
					<th>ID</th>
					<th>ระดับชั้น</th>
					<th>จำนวนนักเรียน</th>
					<th>รายละเอียด</th>
					<th>สถานที่</th>
					<th>อื่นๆ</th>
					<th>สถานะ</th>
				</tr>
				</thead>
				<?php foreach($jobrows as $jobrow) : ?> 
				<tr>
					<td><?php echo $jobrow->id; ?></td>
					<td><?php echo getStudentLevelByLevelId($jobrow->level); ?></td>
					<td><?php echo $jobrow->student_number; ?></td>
					<td><?php echo $jobrow->detail; ?></td>
					<td><?php echo $jobrow->location; ?></td>
					<td><?php echo $jobrow->other; ?></td>
					<td><?php echo $jobrow->assigned_tutor == "0" ? "ว่าง" : "ได้ติวเตอร์แล้ว" ; ?></td>
				</tr>
				<?php endforeach; ?>
			</table>
			</form><?php 
			die();
	}
	
	function aonz_student_registration_callback()
	{
		global $wpdb,$table_prefix;
		
		$checkCaptcha = check_aonz_captcha();// apply_filters( 'aonz_study_regis_check_captcha' ); //True if matched
		
		if($checkCaptcha==true)
		{
			$name = esc_html($_POST['name']);
			$email = esc_html($_POST['email']);
			$phone = intval(esc_html($_POST['phone']));
			$level = intval(esc_html($_POST['level']));
			$study_program = esc_html($_POST['study_program']);
			$student_number = intval(esc_html($_POST['student_number']));
			$detail = esc_html($_POST['detail']);
			$location = esc_html($_POST['location']);
			$other = esc_html($_POST['other']);
			$rate = esc_html($_POST['rate']);
			
			//Insert to db
			$result = $wpdb->insert(
					$table_prefix.'aonz_tutor_request',
					array(
							'name' => $name,
							'email' => $email,
							'phone' => $phone,
							'level' => $level,
							'study_program' => $study_program,
							'student_number' => $student_number,
							'detail' => $detail,
							'location' => $location,
							'other' => $other,
							'hour_rate' => $rate
			));
			if($result)
				echo "success";
			else
				echo "fail";
		}
		else 
		{
			echo "captcha_fail";	
		}	
		die();
	}
}

function aonz_init()
{
	$aonz = new AonzWeblyPlugin();
	$aonz->init();
}

add_action('after_setup_theme', 'aonz_init');

/* When logout, redirect to home page*/
add_filter('logout_url', 'projectivemotion_logout_home', 10, 2);
function projectivemotion_logout_home($logouturl, $redir)
{
	$redir = get_option('siteurl');
	return $logouturl . '&amp;redirect_to=' . urlencode($redir);
}

function display_aonz_captcha()
{
	echo cptch_custom_form('');
}

/**
 * Powered by captcha plugin - based on cptch_check_custom_form method
 * @return boolean
 */
function check_aonz_captcha()
{
	global $str_key;
	$str_key = "bws2012";
	// If captcha doesn't entered
	if ( isset( $_REQUEST['cptch_number'] ) && "" ==  $_REQUEST['cptch_number'] ) 
	{
		return false;
	}

	// Check entered captcha
	if ( isset( $_REQUEST['cptch_result'] ) && isset( $_REQUEST['cptch_number'] ) && 0 == strcasecmp( trim( decode( $_REQUEST['cptch_result'], $str_key ) ), $_REQUEST['cptch_number'] ) ) {
		return true;
	} else {
		return false;
	}
}

require_once("admin_page.php");
?>