<?php
/*
  Plugin Name: TMA WebTools
  Plugin URI: http://webtools.thorstenmarx.com/
  Description: The integration for webtools user segmentation.
  Author: Thorsten Marx
  Version: 1.2.0
  Author URI: http://thorstenmarx.com/
 */
if (!defined('ABSPATH')) {
	exit;
}
/*
  if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
  exit;
  } */

load_plugin_textdomain('tma-webtools', FALSE, basename(dirname(__FILE__)) . '/languages/');

add_action("init", "tma_webtools_init");

require_once 'includes/tma_request.php';
require_once 'includes/class.integration.php';
require_once 'includes/class.plugins.php';

define("TMA_VERSION", "1.2.0");
define("TMA_SEGMENT_MATCHING_ALL", "all");
define("TMA_SEGMENT_MATCHING_SINGLE", "single");

if (is_admin()) {
	require_once( dirname(__FILE__) . '/modules/redux-framework/ReduxCore/framework.php' );
	//require_once( dirname( __FILE__ ). '/modules/redux-framework/sample/sample-config.php' );	
}

function tma_webtools_init() {
	wp_register_style('tma-webtools', plugins_url('css/tma-webtools.css', __FILE__));
	wp_enqueue_style('tma-webtools');
	// has to be global
	// Settings
	require_once 'includes/class.constants.php';
	require_once 'includes/tma_cookie_helper.php';
	require_once 'includes/frontend/tma_script_helper.php';
	require_once 'includes/frontend/class.shortcode_tma_content.php';
	require_once 'includes/frontend/template_tags.php';

	/*
	 * load modules
	 * 
	 * modules must be laoded first so hooks are called correctly
	 */
	require_once 'includes/modules/woocommerce/module.php';

	if (is_user_logged_in() && (is_admin() || is_preview() )) {
		require_once 'includes/backend/class.tma_metabox.php';
		require_once 'includes/backend/class.tma_shortcodes_plugin.php';
		require_once 'includes/backend/class.tma_wpadminbar.php';

		require_once 'includes/backend/class.tma_ajax.php';

		require_once 'includes/backend/tma_settings_page.php';
	}

	add_action('wp_head', 'tma_webtools_hook_js');
	add_action('admin_head', 'tma_js_variables');

	tma_init_cookie();
}

/*
  add_filter("tma_config", 'tma_recommendation_tma_config');
  function tma_recommendation_tma_config($tmaConfig) {
  $recConfig = array();
  $recConfig['plugin_url'] = plugins_url('', __FILE__);

  $tmaConfig['recommendation'] = $recConfig;

  return $tmaConfig;
  }
 */

function tma_js_variables() {

	$options = get_option('tma_webtools_option');
	$siteid = get_option('blogname');
	if (isset($options['webtools_siteid'])) {
		$siteid = $options['webtools_siteid'];
	}
	$apikey = $options["webtools_apikey"];
	$url = $options['webtools_url'];

	$tma_config = [];
	$tma_config['plugin_url'] = plugins_url('', __FILE__);
	$tma_config['apikey'] = $apikey;
	$tma_config['site'] = $siteid;
	$tma_config['url'] = $url;
	$tma_config = apply_filters("tma_config", $tma_config);
	?>
	<script type="text/javascript">
		var TMA_CONFIG = <?php echo json_encode($tma_config); ?>;
	</script><?php
}

function tma_webtools_hook_js() {
	$scriptHelper = new \TMA\TMAScriptHelper();
	echo $scriptHelper->getCode();
}

function tma_init_cookie() {
	TMA\TMA_COOKIE_HELPER::getCookie(TMA\TMA_COOKIE_HELPER::$COOKIE_USER, TMA\UUID::v4(), TMA\TMA_COOKIE_HELPER::$COOKIE_USER_EXPIRE, true);
	TMA\TMA_COOKIE_HELPER::getCookie(TMA\TMA_COOKIE_HELPER::$COOKIE_REQUEST, TMA\UUID::v4(), TMA\TMA_COOKIE_HELPER::$COOKIE_REQUEST_EXPIRE, true);
	TMA\TMA_COOKIE_HELPER::getCookie(TMA\TMA_COOKIE_HELPER::$COOKIE_VISIT, TMA\UUID::v4(), TMA\TMA_COOKIE_HELPER::$COOKIE_VISIT_EXPIRE, true);
}

//add_action('init', 'tma_init_cookie');
//new \TMA\TMA_ShortCodes_Plugin();
// initialice siteorigin pagebuidler extension
//if (in_array('siteorigin-panels/siteorigin-panels.php', apply_filters('active_plugins', get_option('active_plugins')))) {
if (\TMA\Plugins::getInstance()->siteoriginPanels()) {

	// add siteorigin widgets
	/*
	  function tma_widgets_siteorigin_add_folder($folders) {
	  $folders[] = plugin_dir_path(__FILE__) . 'includes/widgets/siteorigin/';
	  return $folders;
	  }
	  add_filter('siteorigin_widgets_widget_folders', 'tma_widgets_siteorigin_add_folder');
	 */

	require_once 'includes/class.siteorigin_integration.php';
}

//if (in_array('js_composer/js_composer.php', apply_filters('active_plugins', get_option('active_plugins')))) {
if (\TMA\Plugins::getInstance()->visualComposer()) {
	require_once 'includes/class.vc_integration.php';
}
