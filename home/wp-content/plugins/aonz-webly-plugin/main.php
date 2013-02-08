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
		
		// adding javascript files
// 		wp_enqueue_script( 'jquery-simplemodal', $this->plugin_url . '/js/jquery.simplemodal.js', array( 'jquery' ) );
		wp_enqueue_script( 'aonz-js', $this->plugin_url . '/js/aonz-js.js', array( 'jquery' ) );
		
		// test ajax
		add_action('wp_ajax_aonz_ajax_test_callback', array($this, 'aonz_ajax_test_callback'));
		
		// add facebook 
		add_action('wp_footer', array($this, 'tutor_registor_modal'));
	}
	
	/**
	 * This function will exploit wp-fb-autoconnect plugin
	 */
	function tutor_registor_modal()
	{
		// Write fb login function
		jfb_output_facebook_instapopup();?>
		
			<div id="tutor_register_modal" style="display:none;">
					<a href="#" onclick="showInstaPopup();return false;" class="aonz-fb-login-button"></a>
			</div>
			<?php 
		}
		
}

function aonz_init()
{
	$aonz = new AonzWeblyPlugin();
	$aonz->init();
}

function aonz_ajax_test_callback()
{
	//*** Add comment post activity already exist functionality
// 	global $post;
// 	$content = $_POST['content'];
// 	// Now write the values
// 	// Record this on the user's profile
// 	$user_id = bp_loggedin_user_id();
// 	$from_user_link   = bp_core_get_userlink( $user_id );
// 	$activity_action  = sprintf( __( '%s posted an update', 'buddypress' ), $from_user_link );
// 	$activity_content = $content;
// 	$primary_link     = bp_core_get_userlink( $user_id, false, true );

// 	// Now write the values
// 	$activity_id = bp_activity_add( array(
// 		'user_id'      => $user_id,
// 		'action'       => apply_filters( 'bp_activity_new_update_action', $activity_action ),
// 		'content'      => apply_filters( 'bp_activity_new_update_content', $activity_content ),
// 		'primary_link' => apply_filters( 'bp_activity_new_update_primary_link', $primary_link ),
// 		'component'    => $bp->activity->id,
// 		'type'         => 'activity_comment_post'
// 	) );
	
// 	bp_activity_update_meta($activity_id, "post_id", $post->ID );
}


	
add_action('after_setup_theme', 'aonz_init');
?>