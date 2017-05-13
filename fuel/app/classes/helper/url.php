<?php
/**
 * Helper Class for URL
 * 
 *
 * @category  Classes
 * @package   Classes
 * @author    Peter
 * @copyright 2015 Microad Indonesia
 */
class Helper_Url
{
	/**
	* make URL internal or external
	* @return
	* string value
	*/
	public static function make_url($url) {
		$result = '';
		if (self::is_external_link($url)) {
			$result = $url;
		} else {
			$result = base_url().$url;
		}
		return $result;
	}
	
	/**
	* check if the anchor is external link
	* @return
	* string boolean
	*/
	public static function is_external_link($url) {
		$result = FALSE;
		if (\Str::starts_with($url, 'http://') || \Str::starts_with($url, 'https://')) {
			$result = TRUE;
		}
		return $result;
	}
}