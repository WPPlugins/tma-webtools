<?php

// This is your option name where all the Redux data is stored.
$opt_name = "tma_webtools_option";

/**
 * ---> SET ARGUMENTS
 * All the possible arguments for Redux.
 * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
 * */
$theme = wp_get_theme(); // For use with some settings. Not necessary.

$args = array(
	// TYPICAL -> Change these values as you need/desire
	'opt_name' => $opt_name,
	// This is where your data is stored in the database and also becomes your global variable name.
	'display_name' => __("TMA WebTools", "tma-webtools"),
	// Name that appears at the top of your panel
	'display_version' => TMA_VERSION,
	// Version that appears at the top of your panel
	'menu_type' => 'menu',
	//Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
	'allow_sub_menu' => true,
	// Show the sections below the admin menu item or not
	'menu_title' => __("TMA WebTools", "tma-webtools"),
	'page_title' => __("TMA WebTools", "tma-webtools"),
	// You will need to generate a Google API key to use this feature.
	// Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
	'google_api_key' => '',
	// Set it you want google fonts to update weekly. A google_api_key value is required.
	'google_update_weekly' => false,
	// Must be defined to add google fonts to the typography module
	'async_typography' => true,
	// Use a asynchronous font on the front end or font string
	//'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
	'admin_bar' => false,
	// Show the panel pages on the admin bar
	'admin_bar_icon' => 'dashicons-portfolio',
	// Choose an icon for the admin bar menu
	'admin_bar_priority' => 50,
	// Choose an priority for the admin bar menu
	'global_variable' => '',
	// Set a different name for your global variable other than the opt_name
	'dev_mode' => false,
//	'forced_dev_mode_off' => false,
	// Show the time the page took to load, etc
	'update_notice' => false,
	// If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
	'customizer' => false,
	// Enable basic customizer support
	//'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
	//'disable_save_warn' => true,                    // Disable the save warning when a user changes a field
	// OPTIONAL -> Give you extra features
	'page_priority' => null,
	// Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
	'page_parent' => 'themes.php',
	// For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
	'page_permissions' => 'manage_options',
	// Permissions needed to access the options panel.
	'menu_icon' => '',
	// Specify a custom URL to an icon
	'last_tab' => '',
	// Force your panel to always open to a specific tab (by id)
	'page_icon' => 'icon-themes',
	// Icon displayed in the admin panel next to your menu_title
	'page_slug' => 'tma_options',
	// Page slug used to denote the panel
	'save_defaults' => true,
	// On load save the defaults to DB before user clicks save or not
	'default_show' => false,
	// If true, shows the default value next to each field that is not the default value.
	'default_mark' => '',
	// What to print by the field's title if the value shown is default. Suggested: *
	'show_import_export' => false,
	// Shows the Import/Export panel when not used as a field.
	// CAREFUL -> These options are for advanced use only
	'transient_time' => 60 * MINUTE_IN_SECONDS,
	'output' => true,
	// Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
	'output_tag' => true,
	// Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
	// 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.
	// FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
	'database' => '',
	// possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
	'use_cdn' => true,
	// If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.
	//'compiler'             => true,
	// HINTS
	'hints' => array(
		'icon' => 'el el-question-sign',
		'icon_position' => 'right',
		'icon_color' => 'lightgray',
		'icon_size' => 'normal',
		'tip_style' => array(
			'color' => 'light',
			'shadow' => true,
			'rounded' => false,
			'style' => '',
		),
		'tip_position' => array(
			'my' => 'top left',
			'at' => 'bottom right',
		),
		'tip_effect' => array(
			'show' => array(
				'effect' => 'slide',
				'duration' => '500',
				'event' => 'mouseover',
			),
			'hide' => array(
				'effect' => 'slide',
				'duration' => '500',
				'event' => 'click mouseleave',
			),
		),
	)
);

Redux::setArgs($opt_name, $args);

/*
 * ---> END ARGUMENTS
 */

/*
 * ---> START HELP TABS
 */

$tabs = array(
	array(
		'id' => 'tma-webtools-help-tab-1',
		'title' => __("Requirements", "tma-webtools"),
		'content' => __('<p>Download the current webTools-Platform from bintray: <a href="https://bintray.com/thmarx/generic/webTools" target="_blank">Bintray</a>.</p>', 'tma-webtools')
	)
);
Redux::setHelpTab($opt_name, $tabs);

// Set the help sidebar
//$content = __('<p>This is the sidebar content, HTML is allowed.</p>', 'redux-framework-demo');
//Redux::setHelpSidebar($opt_name, $content);


/*
 * <--- END HELP TABS
 */


/*
 *
 * ---> START SECTIONS
 *
 */

/*

  As of Redux 3.5+, there is an extensive API. This API can be used in a mix/match mode allowing for


 */

Redux::setSection($opt_name, array(
	'title' => __('Default', 'tma-webtools'),
	'desc' => __('Default configuration options', 'tma-webtools'),
	'id' => 'tma-opt-default-subsection',
	'subsection' => false,
	'icon' => 'el el-home',
	'fields' => array(
		array(
			'id' => 'webtools_siteid',
			'type' => 'text',
			'title' => __("Site id", "tma-webtools"),
			'subtitle' => __("Unique id of your site", "tma-webtools"),
			'desc' => __("The id should be unique and is used to filter in the webTools-Platform.", "tma-webtools"),
			'default' => '',
		),
		array(
			'id' => 'webtools_url',
			'type' => 'text',
			'title' => __("Url", "tma-webtools"),
			'subtitle' => __("The webTools-Platform url", "tma-webtools"),
			'desc' => __("The url whrer the webTools-Platform is installed.", "tma-webtools"),
			'default' => '',
		),
		array(
			'id' => 'webtools_apikey',
			'type' => 'text',
			'title' => __("ApiKey", "tma-webtools"),
			'subtitle' => __("The webTools-Platform apikey", "tma-webtools"),
			'desc' => __("The apikey to use the webTools-Platform.", "tma-webtools"),
			'default' => '',
		),
		array(
			'id' => 'webtools_cookiedomain',
			'type' => 'text',
			'title' => __("Cookie domain", "tma-webtools"),
			'subtitle' => __("The cookie domain", "tma-webtools"),
			'desc' => __("Share the webTools cookie with subdomains. e.q. .your_domain.com", "tma-webtools"),
			'default' => '',
		),
	)
));
Redux::setSection($opt_name, array(
	'title' => __('Tracking', 'tma-webtools'),
	'desc' => __('Tracking configuration', 'tma-webtools'),
	'id' => 'tma-opt-tracking-subsection',
	'subsection' => true,
	'fields' => array(
		array(
			'id' => 'webtools_track',
			'type' => 'checkbox',
			'title' => __("Enable Tracking?", "tma-webtools"),
			'subtitle' => __("Enable the tracking of events", "tma-webtools"),
			'desc' => __("Tracked events are: pageview", "tma-webtools"),
			'default' => '',
		),
		array(
			'id' => 'webtools_track_logged_in_users',
			'type' => 'checkbox',
			'title' => __("Track logged in users?", "tma-webtools"),
			'subtitle' => __("Activate tracking of logged in users", "tma-webtools"),
//			'desc' => __("", "tma-webtools"),
			'default' => false,
		),
		array(
			'id' => 'webtools_score',
			'type' => 'checkbox',
			'title' => __("Enable Scoring?", "tma-webtools"),
			'subtitle' => __("Use scoring", "tma-webtools"),
			'desc' => __("If enabled, you can user the scoring metabox to set scorings for all your post types.", "tma-webtools"),
			'default' => '',
		),
	)
));
Redux::setSection($opt_name, array(
	'title' => __('Options', 'tma-webtools'),
	'desc' => __('Options', 'tma-webtools'),
	'id' => 'tma-opt-options-subsection',
	'subsection' => true,
	'fields' => array(
		array(
			'id' => 'webtools_shortcode_single_item_per_group',
			'type' => 'checkbox',
			'title' => __("View only single item per group?", "tma-webtools"),
			'subtitle' => __("Shortcode option", "tma-webtools"),
			'desc' => __("If enabled, only the first matching group is delivered.", "tma-webtools"),
			'default' => '',
		),
	)
));

do_action( 'tma-webtools-settings', $opt_name);
/*
 * <--- END SECTIONS
*/
