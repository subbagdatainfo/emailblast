<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/*
 * File Handles replacing custom shortcodes for file paths etc
*/

global $sola_nl_template_shortcode_array;  //holds list of template shortcodes along with their replacements

$sola_nl_template_shortcode_array = array(
	"sola_nl_theme_url" => "",
	"sola_nl_install_path" => plugins_url() . "/sola-newsletters/"
);

/* 
 * Function for registering a new template shortcode
*/
function sola_nl_register_template_shortcode($shortcode, $replacement){
	global $sola_nl_template_shortcode_array;
	$sola_nl_template_shortcode_array[$shortcode] = $replacement;
}

/*
 * Function to remove a template shortcode
*/
function sola_nl_remove_template_shortcode($shortcode){
	global $sola_nl_template_shortcode_array;
	
	if(isset($sola_nl_template_shortcode_array[$shortcode])){
		unset($sola_nl_template_shortcode_array[$shortcode]);
	}
}

/*
 * Filters Content
*/
function sola_nl_replace_template_shortcode($content){
	global $sola_nl_template_shortcode_array;

	foreach ($sola_nl_template_shortcode_array as $shortcode => $replacement) {
		$content = str_replace("{" . $shortcode . "}", $replacement, $content);
	}

	return $content;
}

add_filter("sola_nl_filter_theme_html_before_insert", "sola_nl_replace_template_shortcode");