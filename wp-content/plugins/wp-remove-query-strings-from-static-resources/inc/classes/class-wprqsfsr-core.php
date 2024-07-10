<?php
/**
 * Main class of plugin.
 *
 * @package 	WP Remove Query Strings From Static Resources
 */

if ( ! class_exists( 'Wprqsfsr_Core' ) ) {
	/**
	 * Core class of plugin.
	 */
	class Wprqsfsr_Core {

		/**
		 * Store link of assist.
		 *
		 * @var string | array
		 */
		public $link;

		/**
		 * Store action links.
		 *
		 * @var array
		 */
		public $custom_actions;

		/**
		 * Construct method of class.
		 */
		public function __construct() {
			// Let do not apply in Dashboard.
			if ( ! is_admin() ) {
				add_filter( 'script_loader_src', array( $this, 'check_for_question' ), 15, 1 );
				add_filter( 'style_loader_src', array( $this, 'check_for_question' ), 15, 1 );

				add_filter( 'script_loader_src', array( $this, 'check_for_and' ), 15, 1 );
				add_filter( 'style_loader_src', array( $this, 'check_for_and' ), 15, 1 );
			}
			// Add plugin action link.
			add_filter( 'plugin_action_links_' . WPRQSFSR_BASE_FILE, array( $this, 'plugin_action_links' ), 10, 4 );
		}

		/**
		 * Pick only url from full link.
		 *
		 * @param string $src full path of file.
		 */
		public function check_for_question( $src ) {
			$this->link = explode( '?ver', $src );
			return $this->link[0];
		}

		/**
		 * Pick only url from full link.
		 *
		 * @param string $src full path of file..
		 */
		public function check_for_and( $src ) {
			$this->link = explode( '&ver', $src );
			return $this->link[0];
		}

		/**
		 * Call back for action links.
		 *
		 * @param array $actions links.
		 */
		public function plugin_action_links( $actions ) {
			$this->custom_actions = array(
				'configure' => sprintf( '<a target="_blank" href="%s">%s</a>', 'https://www.paypal.me/RinkuYadav', __( 'Donate to Author', 'wprqsfsr' ) ),
				);
			return array_merge( $this->custom_actions, $actions );
		}

	}
} // End if().
