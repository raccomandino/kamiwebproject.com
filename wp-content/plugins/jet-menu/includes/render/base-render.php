<?php
namespace Jet_Menu\Render;

abstract class Base_Render {

	/**
	 * [$settings description]
	 * @var null
	 */
	private $settings = null;

	/**
	 * [__construct description]
	 * @param array $settings [description]
	 */
	public function __construct( $settings = array() ) {
		$this->settings = $this->get_parsed_settings( $settings );

		$this->init();
	}

	/**
	 * [init description]
	 * @return [type] [description]
	 */
	public function init() {}

	/**
	 * Returns parsed settings
	 *
	 * @param  array $settings
	 * @return array
	 */
	public function get_parsed_settings( $settings = array() ) {

		$defaults = $this->default_settings();

		return wp_parse_args( $settings, $defaults );

	}

	/**
	 * Returns plugin default settings
	 *
	 * @return array
	 */
	public function default_settings() {
		return array();
	}

	/**
	 * [get_settings description]
	 * @param  [type] $setting [description]
	 * @return [type]          [description]
	 */
	public function get_settings( $setting = null ) {

		if ( $setting ) {
			return isset( $this->settings[ $setting ] ) ? $this->settings[ $setting ] : false;
		} else {
			return $this->settings;
		}
	}

	/**
	 * Returns required settings
	 *
	 * @return array
	 */
	public function get_required_settings() {
		$required = array();
		$settings = $this->get_settings();
		$default  = $this->default_settings();

		foreach ( $default as $key => $value ) {

			if ( isset( $settings[ $key ] ) ) {
				$required[ $key ] = $settings[ $key ];
			}
		}

		return $required;
	}

	/**
	 * [get description]
	 * @param  [type]  $setting [description]
	 * @param  boolean $default [description]
	 * @return [type]           [description]
	 */
	public function get( $setting = null, $default = false ) {

		if ( isset( $this->settings[ $setting ] ) ) {
			return $this->settings[ $setting ];
		} else {
			$defaults = $this->default_settings();

			return isset( $defaults[ $setting ] ) ? $defaults[ $setting ] : $default;
		}
	}

	/**
	 * [get_content description]
	 * @return [type] [description]
	 */
	public function get_content() {
		ob_start();

		$this->render();

		return ob_get_clean();
	}

	/**
	 * @return false
	 */
	public function get_render_data() {
		return false;
	}

	/**
	 * [get_name description]
	 * @return [type] [description]
	 */
	abstract public function get_name();

	/**
	 * Render content
	 *
	 * @return [type] [description]
	 */
	abstract public function render();

	/**
	 * Is editor context
	 *
	 * @return boolean
	 */
	public function is_editor() {
		return isset( $_REQUEST['context'] ) && $_REQUEST['context'] === 'edit' ? true : false;
	}

	/**
	 * @param $handler
	 *
	 * @return \_WP_Dependency|false
	 */
	public function get_registered_style_obj_by_handler( $handler ) {

		if ( isset( wp_styles()->registered[ $handler ] ) ) {
			$src = wp_styles()->registered[ $handler ]->src;

			if ( ! preg_match( '|^(https?:)?//|', $src ) && ! ( wp_styles()->content_url && 0 === strpos( $src, wp_styles()->content_url ) ) ) {
				wp_styles()->registered[ $handler ]->src = wp_styles()->base_url . $src;
			}

			return wp_styles()->registered[ $handler ];
		}

		return false;
	}

	/**
	 * [get_script_uri_by_handler description]
	 * @param  [type] $handler [description]
	 * @return [type]          [description]
	 */
	public function get_registered_script_obj_by_handler( $handler ) {

		if ( isset( wp_scripts()->registered[ $handler ] ) ) {
			$src = wp_scripts()->registered[ $handler ]->src;

			if ( ! preg_match( '|^(https?:)?//|', $src ) && ! ( wp_scripts()->content_url && 0 === strpos( $src, wp_scripts()->content_url ) ) ) {
				wp_scripts()->registered[ $handler ]->src = wp_scripts()->base_url . $src;
			}

			return wp_scripts()->registered[ $handler ];
		}

		return false;
	}
}

