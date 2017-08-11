<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace TMA;

/**
 * Description of class
 *
 * @author marx
 */
abstract class Integration {

	private static $contentGroups = array();
	private $onlySingleItemPerGroup = true;

	public function __construct() {
		$options = get_option('tma_webtools_option');
		if ($options !== false && array_key_exists("webtools_shortcode_single_item_per_group", $options)) {
			$this->onlySingleItemPerGroup = $options['webtools_shortcode_single_item_per_group'];
		}
	}

	protected function singleItemPerGroup() {
		return $this->onlySingleItemPerGroup === true;
	}

	protected function addGroupContent($group) {
		self::$contentGroups[$group] = true;
	}

	protected function contentAdded($group) {
		if (array_key_exists($group, self::$contentGroups)) {
			return self::$contentGroups[$group];
		}
		return false;
	}

	protected function isActivated($args) {
		return !empty($args['tma_personalization']);
	}

	protected function getGroup($args) {
		$group = 'default';

		if (!empty($args['tma_group'])) {
			$group = $args['tma_group'];
		}

		return $group;
	}

	protected function isGroupDefault($args) {
		return !empty($args['tma_default']);
	}

	protected function matching($args) {

		$matching_mode = $args['tma_matching'];

		
		$attr_segments = [];
		if (array_key_exists("segments", $args)) {
			$attr_segments = explode(",", $args['segments']);
		} else {
			foreach ($args as $key => $value) {
				if (!empty($args[$key]) && TMAScriptHelper::startsWith($key, "tma_segment_")) {
					$attr_segments[] = substr($key, 12);
				}
			}
		}

		$uid = TMA_COOKIE_HELPER::getCookie(TMA_COOKIE_HELPER::$COOKIE_USER, UUID::v4(), TMA_COOKIE_HELPER::$COOKIE_USER_EXPIRE);
		$request = new TMA_Request();
		$response = $request->getSegments($uid);

		$segments = ["default"];
		if ($response !== NULL) {
			if (sizeof($response->user->segments) > 0) {
				$segments = $response->user->segments;
			}
		}
		$matching = false;
		$segments = array_map('trim', $segments);
		$attr_segments = array_map('trim', $attr_segments);
		if ($matching_mode === ShortCode_TMA_CONTENT::$match_mode_all) {
			$matching = ShortCode_TMA_CONTENT::matching_mode_all($segments, $attr_segments);
		} else if ($matching_mode === ShortCode_TMA_CONTENT::$match_mode_single) {
			$matching = ShortCode_TMA_CONTENT::matching_mode_single($segments, $attr_segments);
		}

		return $matching;
	}

}
