<?php
/**
 * Created by PhpStorm.
 * User: aschuurman
 * Date: 17/10/2018
 * Time: 10:23
 */

namespace BannerManager;

function bannerAd_shortcode( $atts ) {
	$name  = '';
	$title = '';

	extract( shortcode_atts( [
		'name'  => 'template-header',
		'title' => '',
	], $atts ) );

	return bannerAd( $name, $title, true );
}

add_shortcode( 'banner-group', 'bannerAd_shortcode' );