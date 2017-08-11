<?php
/*
 * Copyright (C) 2016 marx
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace TMA;

class SettingsPage {

	/**
	 * Holds the values to be used in the fields callbacks
	 */
	private $options;
	private $options_wc;
	private $translated_settings;
	private $translated_url;
	private $translated_siteid;
	private $translated_track;
	private $translated_track_logged_in_users;
	private $translated_score;
	private $translated_apikey;
	private $translated_shortcode_single_item_per_group;
	private $translated_wc_tracking;
	private static $TAB_DEFAULT = "default";
	private static $TAB_WC = "wc";

	/**
	 * Start up
	 */
	public function __construct() {
		add_action('admin_menu', array($this, 'add_plugin_page'));
		add_action('admin_init', array($this, 'page_init'));

		$this->translated_settings = __("Settings", "tma-webtools");
		$this->translated_siteid = __("Site id", "tma-webtools");
		$this->translated_url = __("Url", "tma-webtools");
		$this->translated_apikey = __("ApiKey", "tma-webtools");
		$this->translated_track = __("Enable Tracking?", "tma-webtools");
		$this->translated_track_logged_in_users = __("Track logged in users?", "tma-webtools");
		$this->translated_score = __("Enable Scoring?", "tma-webtools");
		$this->translated_shortcode_single_item_per_group = __("View only single item per group?", "tma-webtools");
		$this->translated_wc_tracking = __("Track WooCommerce events?", "tma-webtools");
	}

	/**
	 * Add options page
	 */
	public function add_plugin_page() {

		add_menu_page(
				__("TMA WebTools", "tma-webtools"), __("TMA WebTools", "tma-webtools"), 'manage_options', 'tma-webtools/pages/tma-webtools-admin.php');
		add_submenu_page('tma-webtools/pages/tma-webtools-admin.php', __("Settings", "tma-webtools"), __("Settings", "tma-webtools"), 'manage_options', 'tma-webtools-setting-admin', array($this, 'create_admin_page'));
	}

	/**
	 * Options page callback
	 */
	public function create_admin_page() {
		// Set class property
		$this->options = get_option('tma_webtools_option');
		$this->options_wc = get_option('tma_webtools_option_wc');

		$active_tab = $this->getActiveTab();
		?>
		<div class="wrap">

			<h2 class="nav-tab-wrapper">  
				<a href="?page=tma-webtools-setting-admin&tab=<?php echo self::$TAB_DEFAULT ?>" class="nav-tab <?php echo $active_tab == self::$TAB_DEFAULT ? 'nav-tab-active' : ''; ?>">Default</a>  
				<a href="?page=tma-webtools-setting-admin&tab=<?php echo self::$TAB_WC ?>" class="nav-tab <?php echo $active_tab == self::$TAB_WC ? 'nav-tab-active' : ''; ?>">WooCommerce</a>  
			</h2>  
			<form method="post" action="options.php">
				<?php
				if ($active_tab == self::$TAB_DEFAULT) {
					// This prints out all hidden setting fields
					settings_fields('tma_webtools_option_group');
					do_settings_sections('tma-webtools-setting-admin');
				} elseif ($active_tab == self::$TAB_WC) {
					// This prints out all hidden setting fields
					settings_fields('tma_webtools_option_group_wc');
					do_settings_sections('tma-webtools-wc-setting-admin');
				}
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	private function getActiveTab() {
		$active_tab = self::$TAB_DEFAULT;
		if (isset($_GET['tab'])) {
			$active_tab = $_GET['tab'];
		}
		return $active_tab;
	}

	/**
	 * Register and add settings
	 */
	public function page_init() {
		register_setting(
				'tma_webtools_option_group', // Option group
				'tma_webtools_option', // Option name
				array($this, 'sanitize') // Sanitize
		);
		register_setting(
				'tma_webtools_option_group_wc', // Option group
				'tma_webtools_option_wc', // Option name
				array($this, 'sanitize_wc') // Sanitize
		);

		add_settings_section(
				'default_setting_section_id', // ID
				$this->translated_settings, // Title
				array($this, 'print_section_info'), // Callback
				'tma-webtools-setting-admin' // Page
		);

		add_settings_field(
				'webtools_siteid', // ID
				$this->translated_siteid, // Title 
				array($this, 'webtools_siteid_callback'), // Callback
				'tma-webtools-setting-admin', // Page
				'default_setting_section_id' // Section           
		);

		add_settings_field(
				'webtools_url', // ID
				$this->translated_url, // Title 
				array($this, 'webtools_url_callback'), // Callback
				'tma-webtools-setting-admin', // Page
				'default_setting_section_id' // Section           
		);

		add_settings_field(
				'webtools_apikey', // ID
				$this->translated_apikey, // Title 
				array($this, 'webtools_apikey_callback'), // Callback
				'tma-webtools-setting-admin', // Page
				'default_setting_section_id' // Section           
		);

		add_settings_field(
				'webtools_track', $this->translated_track, array($this, 'webtools_track_callback'), 'tma-webtools-setting-admin', 'default_setting_section_id'
		);
		add_settings_field(
				'webtools_track_logged_in_users', $this->translated_track_logged_in_users, array($this, 'webtools_track_logged_in_users_callback'), 'tma-webtools-setting-admin', 'default_setting_section_id'
		);
		add_settings_field(
				'webtools_score', $this->translated_score, array($this, 'webtools_score_callback'), 'tma-webtools-setting-admin', 'default_setting_section_id'
		);
		add_settings_field(
				'webtools_shortcode_single_item_per_group', $this->translated_shortcode_single_item_per_group, array($this, 'webtools_shortcode_single_item_per_group'), 'tma-webtools-setting-admin', 'default_setting_section_id'
		);


		/** START WooCommerce Settings * */
		add_settings_section(
				'tma_wc_setting_section', // ID
				$this->translated_settings, // Title
				array($this, 'print_wc_section_info'), // Callback
				'tma-webtools-wc-setting-admin' // Page
		);

		add_settings_field('webtools_wc_tracking', $this->translated_wc_tracking, array($this, 'webtools_tc_track_callback'), 'tma-webtools-wc-setting-admin', 'tma_wc_setting_section');
		/** END WooCommerce Settings * */
	}

	/**
	 * Sanitize each setting field as needed
	 *
	 * @param array $input Contains all settings fields as array keys
	 */
	public function sanitize($input) {
		$new_input = array();


		if (isset($input['webtools_url'])) {
			$url = $input['webtools_url'];
			if (!$this->endsWith($url, "/")) {
				$url .= "/";
			}
			$new_input['webtools_url'] = sanitize_text_field($url);
		}

		if (isset($input['webtools_apikey'])) {
			$new_input['webtools_apikey'] = sanitize_text_field($input['webtools_apikey']);
		}
		if (isset($input['webtools_siteid'])) {
			$new_input['webtools_siteid'] = sanitize_text_field($input['webtools_siteid']);
		}
		if (isset($input['webtools_track'])) {
			$new_input['webtools_track'] = true;
		} else {
			$new_input['webtools_track'] = false;
		}
		if (isset($input['webtools_track_logged_in_users'])) {
			$new_input['webtools_track_logged_in_users'] = true;
		} else {
			$new_input['webtools_track_logged_in_users'] = false;
		}
		if (isset($input['webtools_score'])) {
			$new_input['webtools_score'] = true;
		} else {
			$new_input['webtools_score'] = false;
		}

		if (isset($input['webtools_shortcode_single_item_per_group'])) {
			$new_input['webtools_shortcode_single_item_per_group'] = true;
		} else {
			$new_input['webtools_shortcode_single_item_per_group'] = false;
		}


		return $new_input;
	}

	public function sanitize_wc($input) {
		$new_input = array();


		/** START WOOCOMMERCE SETTINGS * */
		if (isset($input['webtools_wc_tracking'])) {
			$new_input['webtools_wc_tracking'] = true;
		} else {
			$new_input['webtools_wc_tracking'] = false;
		}
		/** END WOOCOMMERCE SETTINGS * */
		return $new_input;
	}

	/**
	 * Print the Section text
	 */
	public function print_section_info() {
		print 'Enter your settings below:';
	}

	/**
	 * Print the Section text
	 */
	public function print_wc_section_info() {
		print 'WooCommerce settings:';
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function webtools_url_callback() {
		printf(
				'<input type="text" id="title" name="tma_webtools_option[webtools_url]" value="%s" size="50" />', isset($this->options['webtools_url']) ? esc_attr($this->options['webtools_url']) : ''
		);
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function webtools_siteid_callback() {
		printf(
				'<input type="text" id="siteid" name="tma_webtools_option[webtools_siteid]" value="%s" size="50" />', isset($this->options['webtools_siteid']) ? esc_attr($this->options['webtools_siteid']) : ''
		);
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function webtools_apikey_callback() {
		printf(
				'<input type="text" id="tma_apikey" name="tma_webtools_option[webtools_apikey]" value="%s" size="50" />', isset($this->options['webtools_apikey']) ? esc_attr($this->options['webtools_apikey']) : ''
		);
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function webtools_track_callback() {
		printf(
				'<input type="checkbox" id="track" name="tma_webtools_option[webtools_track]" %s />', isset($this->options['webtools_track']) && $this->options['webtools_track'] == true ? 'checked="checked"' : ''
		);
	}

	/**
	 * Settings for enable/disable tracking for logged in users
	 */
	public function webtools_track_logged_in_users_callback() {
		printf(
				'<input type="checkbox" id="track" name="tma_webtools_option[webtools_track_logged_in_users]" %s />', isset($this->options['webtools_track_logged_in_users']) && $this->options['webtools_track_logged_in_users'] == true ? 'checked="checked"' : ''
		);
	}

	public function webtools_shortcode_single_item_per_group() {
		printf(
				'<input type="checkbox" id="track" name="tma_webtools_option[webtools_shortcode_single_item_per_group]" %s />', isset($this->options['webtools_shortcode_single_item_per_group']) && $this->options['webtools_shortcode_single_item_per_group'] == true ? 'checked="checked"' : ''
		);
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function webtools_score_callback() {
		printf(
				'<input type="checkbox" id="score" name="tma_webtools_option[webtools_score]" %s />', isset($this->options['webtools_score']) && $this->options['webtools_score'] == true ? 'checked="checked"' : ''
		);
	}

	public function webtools_tc_track_callback() {
		printf(
				'<input type="checkbox" id="wc_tracking" name="tma_webtools_option_wc[webtools_wc_tracking]" %s />', isset($this->options_wc['webtools_wc_tracking']) && $this->options_wc['webtools_wc_tracking'] == true ? 'checked="checked"' : ''
		);
	}

	private function endsWith($haystack, $needle) {
		$length = strlen($needle);
		if ($length == 0) {
			return true;
		}

		return (substr($haystack, -$length) === $needle);
	}

}

if (is_admin()) {
//	$my_settings_page = new SettingsPage();
//	require_once( dirname( __FILE__ ) . '/../../libs/ReduxFramework/ReduxCore/framework.php' );
	require_once( 'settings/redux.settings.php' );
}