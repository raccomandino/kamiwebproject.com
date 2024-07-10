<?php

use Elementor\Plugin;
use Elementor\Modules\Usage\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Emage_Upgrades {

    public function __construct() {
		$this->do_upgrade = false;
		$this->version = get_option('emage_version');
		add_action( 'elementor/widgets/widgets_registered', array($this, 'init') );
	}
	
	public function init() {
		if (version_compare($this->version, '4.3.0', '<')) {
			$this->_v_4_3_0();
		}
		if (version_compare($this->version, '4.3.3', '<')) {
			$this->_v_4_3_3();
		}
		if (version_compare($this->version, EHE_VERSION, '<')) {
			update_option('emage_version', EHE_VERSION);
			if ($this->do_upgrade) {
				Plugin::$instance->posts_css_manager->clear_cache();
				wp_redirect($_SERVER['REQUEST_URI']);
				die;
			}
		}
	}

	public function _v_4_3_3() {
		Plugin::$instance->files_manager->clear_cache();
		$this->do_upgrade = true;
	}

    public function _v_4_3_0() {
        global $wpdb;

		$post_ids = $wpdb->get_col(
			'SELECT `post_id` FROM `' . $wpdb->postmeta . '` WHERE `meta_key` = "_elementor_data" AND (`meta_value` LIKE \'%"widgetType":"emage_hover_effects"%\' OR `meta_value` LIKE \'%"widgetType":"emage_post_grid"%\');'
		);

		if ( empty( $post_ids ) ) {
			return;
        }
        
        foreach ( $post_ids as $post_id ) {

			$document = Plugin::$instance->documents->get( $post_id );

			if ( $document ) {
				$data = $document->get_elements_data();
			}

			if ( empty( $data ) ) {
				continue;
            }

            $data = Plugin::$instance->db->iterate_data( $data, function( $element ) use ( &$do_update ) {
                if (empty( $element['widgetType'] )) {
                    return $element;
                }

				if ('emage_hover_effects' !== $element['widgetType'] && 'emage_post_grid' !== $element['widgetType']) {
					return $element;
                }
                
                if (!isset($element['settings']['image_size']) || $element['settings']['image_size'] === 'full') {
					$element['settings']['image_size'] = 'full';
                }
				                
                $element['settings']['width']['unit'] = '%';
				$element['settings']['width']['size'] = 100;

				if (!isset($element['settings']['match_height']) || $element['settings']['match_height'] == '100%') {
					$element['settings']['match_height'] = '100%';
				} else {
					$element['settings']['match_height'] = '';
					$element['settings']['height']['unit'] = isset($element['settings']['image_height']['unit']) ? $element['settings']['image_height']['unit'] : 'px';
					$element['settings']['height']['size'] = isset($element['settings']['image_height']['size']) ? $element['settings']['image_height']['size'] : 400;
				}

				return $element;
			} );

			// We need the `wp_slash` in order to avoid the unslashing during the `update_post_meta`
			$json_value = wp_slash( wp_json_encode( $data ) );

			update_metadata( 'post', $post_id, '_elementor_data', $json_value );
		}
		
		$this->do_upgrade = true;

    }

}

new Emage_Upgrades();
