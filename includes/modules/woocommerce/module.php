<?php

/**
 * Description of TMA_WC_Module
 *
 * @author marx
 */
function tma_webtools_modules_woocommerce_settings($opt_name) {
	
	$recommendation_options = ["segment" => __("Segment based", "tma-webtools")];
	$recommendation_options = apply_filters("tma_init_wc_recommendations_options", $recommendation_options);
	
	Redux::setSection($opt_name, array(
		'title' => __('WooCommerce', 'tma-webtools'),
		'desc' => __('WooCommerce', 'tma-webtools'),
		'id' => 'tma-opt-woocommerce-subsection',
		'subsection' => false,
		'fields' => array(
			array(
				'id' => 'webtools_wc_tracking',
				'type' => 'checkbox',
				'title' => __("Track WooCommerce events?", "tma-webtools"),
				'subtitle' => __("Also track WooCommerce events", "tma-webtools"),
				'desc' => __("Tracked events are: order, add item to basket, remove item from basket.", "tma-webtools"),
				'default' => false,
			),
			/*
			  array(
			  'id' => 'webtools_wc_recommendation',
			  'type' => 'checkbox',
			  'title' => __("Replace Recommendations?", "tma-webtools"),
			  'subtitle' => __("Use user segments to display recommendations", "tma-webtools"),
			  'desc' => __("User segments are used to load related products.", "tma-webtools"),
			  'default' => false,
			  ), */
			
			array(
				'id' => 'webtools_wc_recommendation',
				'type' => 'select',
				'title' => __("Replace Recommendations?", "tma-webtools"),
				'subtitle' => __("Replace the default related products.", "tma-webtools"),
				'desc' => __("Method to load related products.", "tma-webtools"),
				'options' => $recommendation_options,
			),
			array(
				'id' => 'webtools_wc_recommendation_segment_match',
				'type' => 'select',
				'title' => __("Matching mode?", "tma-webtools"),
				'subtitle' => __("Should the product match all segments.", "tma-webtools"),
				'desc' => __("If &quot;single&quot;, the product must match at least one user segments. If &quot;all&quot;, the product must match all user segments.", "tma-webtools"),
				'options' => [
					"OR" => __("Single", "tma-webtools"),
					"AND" => __("All", "tma-webtools")
				],
				"default" => "or"
			),
		)
	));
}

if (\TMA\Plugins::getInstance()->woocommerce()) {

	if (is_user_logged_in() && (is_admin() || is_preview() )) {
		require_once 'class.backend.product_settings.php';
	}
	require_once 'class.product_loader.php';
	require_once 'class.frontend.wc_tracker.php';
	require_once 'class.frontend.tma_recommendation_product.php';

	add_action("tma-webtools-settings", "tma_webtools_modules_woocommerce_settings");
}