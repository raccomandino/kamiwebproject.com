<?php

use Elementor\Core\DynamicTags\Data_Tag;
use Elementor\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

Class Product_Gallery_Image extends Data_Tag {

	/**
	* Get Name
	*
	* Returns the Name of the tag
	*
	* @since 2.0.0
	* @access public
	*
	* @return string
	*/
	public function get_name() {
		return 'woocommerce-product-image-single-tag';
	}

	/**
	* Get Title
	*
	* Returns the title of the Tag
	*
	* @since 2.0.0
	* @access public
	*
	* @return string
	*/
	public function get_title() {
		return __( 'Product Gallery Image', 'elementor-pro' );
	}
   
	/**
	* Get Group
	*
	* Returns the Group of the tag
	*
	* @since 2.0.0
	* @access public
	*
	* @return string
	*/
	public function get_group() {
		return 'woocommerce';
	}

	/**
	* Get Categories
	*
	* Returns an array of tag categories
	*
	* @since 2.0.0
	* @access public
	*
	* @return array
	*/
	public function get_categories() {
		return [ Module::IMAGE_CATEGORY ];
	}

	public function get_value( array $options = [] ) {
        $product = wc_get_product();
        
		if ( ! $product ) {
			return [];
        }

        $attachment_ids = $product->get_gallery_image_ids();
        
        if ( empty( $attachment_ids[0] ) ) {
            return [];
        }

		$src = wp_get_attachment_image_src( $attachment_ids[0], 'full' );

		return [
			'id' => $attachment_ids[0],
			'url' => $src[0],
		];
	}
}