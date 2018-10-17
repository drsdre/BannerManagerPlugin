<?php
/**
 * Created by PhpStorm.
 * User: aschuurman
 * Date: 17/10/2018
 * Time: 09:56
 */
namespace BannerManager;

class Banner {

	var $title = '';
	var $html = '';
	var $image = '';
	var $href = '';
	var $alt = '';
	var $active = true;
	var $hits = 0;
	var $timestamp = 0;

	function makeHTMLBanner( $title, $html, $active ) {
		$this->title  = $title;
		$this->html   = $html;
		$this->active = $active;
	}

	function makeImageBanner( $title, $image, $link, $active ) {
		$this->title  = $title;
		$this->image  = $image;
		$this->href   = $link;
		$this->active = $active;
	}

	function getHTML() {
		if ( $this->html != '' ) {
			return stripslashes( $this->html );
		} else {
			return '<a ' . ( get_theme_option( 'redirect-banner-window' ) != '' ? 'target="_blank"' : '' ) .
			       ' title="' . htmlentities( $this->alt ) . '"  href="' . $this->getLinkURL() . '">' .
			       '<img class="bannerAd" alt="' . htmlentities( $this->alt ) . '" src="' . $this->image . '" />' .
			       '</a>';
		}
	}

	function getLinkURL() {
		return get_bloginfo( 'url' ) . '/banner/' . $this->getKey();
	}

	function getKey() {
		return substr( sanitize_title( $this->title ), 0, 50 ) . ( $this->timestamp > 0 ? substr( $this->timestamp, - 4,
				4 ) : '' );
	}

	function getType() {
		if ( $this->html == '' ) {
			return 'Image';
		} else {
			return 'HTML';
		}
	}

	function getHits() {
		return $this->hits;
	}
}