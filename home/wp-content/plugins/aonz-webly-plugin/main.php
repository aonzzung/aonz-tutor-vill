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
		
		// Student Registration Callback
		add_action('wp_ajax_aonz_student_registration', array($this, 'aonz_student_registration_callback'));
		add_action('wp_ajax_nopriv_aonz_student_registration', array($this, 'aonz_student_registration_callback'));
	}
	
	/**
	 * This function will exploit wp-fb-autoconnect plugin
	 */
	function tutor_register_modal()
	{
		// Write fb login function
		jfb_output_facebook_instapopup();
		?>
			<div id="tutor_register_modal" style="display:none;width: 370px;">
					<a href="#" onclick="showInstaPopup();return false;" class="aonz-fb-login-button"></a>
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
		<div id="student_register_modal_wrapper">
			<form class="cmxform" id="student_register_form" method="post" action="" style="width: 700px;height: 500px;overflow-y: scroll;">
			 <fieldset>
			   <legend class="title">กรอกแบบฟอร์มเพื่อสมัครเรียน</legend>
			   <p>
			     <label for="cname">ชื่อ-นามสกุล ผู้ติดต่อ</label>
			     <span class="required">*</span>
			     <input id="cname" name="name" type="text" size="25" class="required" minlength="2" />
			   </p>
			   <p>
			     <label for="cphone">เบอร์โทรศัพท์ที่ติดต่อได้</label>
			     <span class="required">*</span>
			     <span style="display:block;">กรอกตัวเลขติดกันทั้งหมด ตัวอย่าง 0821234567</span>
			     <input id="cphone" name="phone" type="text" size="25"  class="phone" value="" />
			   </p>
			   <p>
			     <label for="cemail">อีเมล์</label>
			     <input id="cemail" name="email" size="25" type="text"  class="email" />
			   </p>
			   <p>
			     <label for="clevel">ระดับชั้นผู้เรียน</label>
			     <span class="required">*</span>
			     <select id="clevel" name="level">
					  <option value="1">อนุบาล</option>
					  <option value="2">ประถมต้น</option>
					  <option value="3" selected="selected">ประถมปลาย</option>
					  <option value="4">มัธยมต้น</option>
					  <option value="5">มัธยมปลาย</option>
					  <option value="6">มหาวิทยาลัย</option>
					  <option value="7">บุคคลทั่วไป</option>
				 </select>
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
	
	function aonz_student_registration_callback()
	{
		global $wpdb,$table_prefix;
		
		$name = esc_html($_POST['name']);
		$email = esc_html($_POST['email']);
		$phone = intval(esc_html($_POST['phone']));
		$level = intval(esc_html($_POST['level']));
		$student_number = intval(esc_html($_POST['student_number']));
		$detail = esc_html($_POST['detail']);
		$location = esc_html($_POST['location']);
		$other = esc_html($_POST['other']);
		
		//Insert to db
		$result = $wpdb->insert(
				$table_prefix.'aonz_tutor_request',
				array(
						'name' => $name,
						'email' => $email,
						'phone' => $phone,
						'level' => $level,
						'student_number' => $student_number,
						'detail' => $detail,
						'location' => $location,
						'other' => $other
		));

		if($result)
			echo "success";
		else
			echo "fail";
		die();
	}
}

function aonz_init()
{
	$aonz = new AonzWeblyPlugin();
	$aonz->init();
}

add_action('after_setup_theme', 'aonz_init');
?>