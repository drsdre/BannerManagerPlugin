<?php
/**
 * Created by PhpStorm.
 * User: aschuurman
 * Date: 17/10/2018
 * Time: 09:55
 */
namespace BannerManager;

class BannerGroup {

	var $title = '';
	var $banners = [];
	var $slug = '';

	function addHTMLBanner( $title, $html, $active ) {
		$banner = new Banner();
		$banner->makeHTMLBanner( $title, $html, $active );
		$this->addBanner( $banner );
	}

	function addBanner( $banner ) {
		$this->banners[] = $banner;
	}

	function addImageBanner( $title, $image, $href, $active ) {
		$banner = new Banner();
		$banner->makeImageBanner( $title, $image, $href, $active );
		$this->addBanner( $banner );
	}
}