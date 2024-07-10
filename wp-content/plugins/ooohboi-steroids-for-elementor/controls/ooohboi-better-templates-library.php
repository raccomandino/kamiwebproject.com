<?php

use Elementor\Core\Settings\Manager as SettingsManager; 
use Elementor\TemplateLibrary\Source_Local;
use Elementor\Plugin;
use Elementor\DB;
use Elementor\Core\Base\Document;
use Elementor\Core\Common\Modules\Ajax\Module as Ajax;
use Elementor\TemplateLibrary\Manager;
use Elementor\Core\Files\Uploads_Manager;

defined( 'ABSPATH' ) || die(); // Exit if accessed directly.
/**
 * Main OoohBoi Better Templates Library class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 2.1.0
 */
class OoohBoi_Better_Templates_Library {

	/**
	 * Initialize 
	 *
	 * @since 2.1.0
	 *
	 * @access public
	 */
	public static function init() {

		// Editor Styles & Scripts
		add_action( 'elementor/editor/after_enqueue_scripts', function() {

			wp_enqueue_script(
				'ele-btl-editor',
				OoohBoi_Steroids::$OBS_DIR . 'assets/js/ele-btl-editor.js', 
				[ 'elementor-editor', 'jquery' ],
				OoohBoi_Steroids::VERSION . 'adgf23456',
				true
			);
			// data to JS via wp_localize_script
			$local_data = [
				'dummy_url' => OoohBoi_Steroids::$OBS_DIR . 'assets/img/ele-btl-preview-placeholder.png', 
				'icon_url' => OoohBoi_Steroids::$OBS_DIR . 'assets/img/ooohboi-gloo-icon.svg', 
				'nonce' => wp_create_nonce( 'ele-btl-ajax-nonce' ), 
				'nonce_template' => wp_create_nonce( 'ele-btl-ajax-nonce-new-template' ), 
			];
			wp_localize_script(
				'ele-btl-editor',
				'EleBTLLocalized',
				$local_data
			);

		} );
		
		add_action( 'elementor/frontend/after_enqueue_styles', function() { 
			// plugin stuff
			wp_enqueue_style( 'ele-btl-styles' ); 
		} );

		// admin styles
		add_action( 'admin_enqueue_scripts', function() {

			wp_enqueue_script(
				'ele-btl-admin-js',
				OoohBoi_Steroids::$OBS_DIR . 'assets/js/ele-btl-admin-min.js', 
				[ 'jquery' ],
				OoohBoi_Steroids::VERSION . '10012023',
				true
			);

			$local_data = [
				'export_template_nonce' => wp_create_nonce( 'ele-btl-ajax-nonce-export' ), 
				'dummy_url' => OoohBoi_Steroids::$OBS_DIR . 'assets/img/ele-btl-preview-placeholder.png', 
				'nonce' => wp_create_nonce( 'ele-btl-ajax-nonce' ), 
			];
			wp_localize_script(
				'ele-btl-admin-js',
				'EleBTLLocalized',
				$local_data
			);

		} );

		// Add featured image to Elementor Library
		add_filter( 'manage_posts_columns', [ __CLASS__, 'ele_btl_add_thumbnail' ], 2, 99 );
		add_filter( 'manage_pages_columns', [ __CLASS__, 'ele_btl_add_thumbnail' ], 2, 99 ); 
		add_filter( 'elementor/template-library/get_template', [ __CLASS__, 'ele_btl_get_item' ] );
		add_filter( 'bulk_actions-edit-elementor_library', [ __CLASS__, 'btl_bulk_export_add_action' ] );
		add_filter( 'handle_bulk_actions-edit-elementor_library', [ __CLASS__, 'btl_bulk_export_trigger' ], 10, 3 );

		// Show featured image in column
		add_action( 'manage_posts_custom_column', [ __CLASS__, 'ele_btl_show_thumbnail' ], 5, 2, 99 );
		add_action( 'manage_pages_custom_column', [ __CLASS__, 'ele_btl_show_thumbnail' ], 5, 2, 99 );

		// post_row actions
		add_filter( 'post_row_actions', [ __CLASS__, 'btl_post_row_actions' ], 10, 2, 99 );

		// hook on Elementor
		add_action( 'elementor/editor/footer', [ __CLASS__, 'ele_btl_mazed' ], 10, 99 );

		// ajax stuff
		add_action( 'wp_ajax_ele_btl_set_featured_image', [ __CLASS__, 'ele_btl_set_featured_image' ] );
		add_action( 'wp_ajax_ele_btl_delete_featured_image', [ __CLASS__, 'ele_btl_delete_featured_image' ] ); 

		add_action( 'elementor/ajax/register_actions', [ __CLASS__, 'register_ajax_actions' ] );
		add_action( 'wp_ajax_btl_library_direct_actions', [ __CLASS__, 'btl_handle_direct_actions' ] );

		// import local template 
		add_action( 'elementor/template-library/after_save_template', [ __CLASS__, 'btl_on_template_save' ], 10, 2 );

    }

	/*
		* Add the column for Thumbnail
		*
		* @since 1.0.0
		*
		* @access public
	*/
	public static function ele_btl_add_thumbnail( $columns ) {
		// Check if post type is 'elementor_library'
		global $pagenow, $typenow;
		if( 'elementor_library' === $typenow && 'edit.php' === $pagenow ) {
			
			$new = array();
			foreach ( $columns as $key => $title ) {
				if( $key == 'title' ) { 
					$new[ 'featured_thumb' ] = '';
					$new[ 'btl_export_link' ] = '';
					if( did_action( 'elementor/init' ) && ! class_exists( 'ElementorPro\Plugin' ) ) $new[ 'column-shortcode' ] = 'Shortcode';
				} 
				$new[ $key ] = $title;
			}
			return $new;
	
		}
		else return $columns;
	}

	/*
		* Display Thumbnail in column
		*
		* @since 1.0.0
		*
		* @access public
	*/
	public static function ele_btl_show_thumbnail( $theme_columns, $theme_id ) {
	
		// Check if post type is 'elementor_library'
		global $pagenow, $typenow;
		if( 'elementor_library' === $typenow && 'edit.php' === $pagenow ) {

			switch ( $theme_columns ) {
				case 'featured_thumb':
					if( function_exists( 'the_post_thumbnail' ) ) {
		
						$permalink = get_edit_post_link();
						$thumb = get_the_post_thumbnail_url( null, 'full' );
						$img_set = ( ! $thumb ) ? OoohBoi_Steroids::$OBS_DIR . 'assets/img/ele-btl-preview-placeholder.png' : $thumb; 
						$remove_thumb = ( ! $thumb || ! current_user_can( 'delete_post', get_the_ID() ) ) ? '' : '<em id="ele-btl-delete-media_' . get_the_ID() . '" data-elebtlid="' . get_the_ID() . '" title="' . esc_html__( 'Delete?', 'ooohboi-steroids' ) . '"></em>'; 
						
						$add_thumb_link = ( ! current_user_can( 'edit_post', get_the_ID() ) ) ? '<img class="ele-btl-img" src="%3$s" alt="%5$s">' : '%4$s<a id="ele-btl-insert-media_%1$s" class="ele-btl-img-link" href="%2$s" data-elebtlid="%1$s"><span></span><img class="ele-btl-img" src="%3$s" alt="%5$s"></a>';
						printf( 
							$add_thumb_link, 
							get_the_ID(), 
							'#', 
							$img_set, 
							$remove_thumb, 
							get_the_title()
						);
		
					} else esc_html_e( 'Your theme doesn\'t support Featured Image…', 'ooohboi-steroids' );
		
					break;

				case 'btl_export_link' : 

					printf( 
					'<a href="%1$s" class="btl-export-button"><img src="%2$s" alt="OoohBoi TM" />%3$s</a>', 
					self::btl_get_export_link( get_the_ID() ), 
					OoohBoi_Steroids::$OBS_DIR . 'assets/img/ooohboi-tm.svg', 
					esc_html__( 'Export with BTL', 'ooohboi-steroids' ) 
					);

					break;

				case 'column-shortcode' : 

					printf( 
					'<input class="elementor-shortcode-input" type="text" readonly="" onfocus="%1$s" value="%2$s">', 
					'this.select()', 
					'[elementor-template id=&quot;' . get_the_ID() . '&quot;]'
					);
	
					break;
			}
	
		}
		else return $theme_columns;
		
	}

	/*
		* Set the post thumbnail via Ajax
		*
		* @since 1.0.0
		*
		* @access public
	*/
	public static function ele_btl_set_featured_image() {
	
		if( ! wp_verify_nonce( $_POST[ 'nonce' ], 'ele-btl-ajax-nonce' ) ) die( esc_html__( 'You are not allowed to perform this action!', 'ooohboi-steroids' ) ); // bail if nonce-busted!
		
		$ele_post_id  = intval( $_POST[ 'post_id' ] ); 
		$ele_thumb_id = intval( $_POST[ 'thumbnail_id' ] );
		if( ! $ele_post_id || ! $ele_thumb_id ) die( esc_html__( 'Either the Template ID is incorrect or the Media Library file does not exist.', 'ooohboi-steroids' ) ); // bail for non-existing post_id or thumb_id
		
		if( ! current_user_can( 'publish_post', $ele_post_id ) ) die( esc_html__( 'You are not allowed to perform this action!', 'ooohboi-steroids' ) ); // bail if lack of credentials 05.03.2023 | Security Issue patch
		$set_thumb_response = update_post_meta( $ele_post_id, '_thumbnail_id', $ele_thumb_id );

		if ( is_wp_error( $set_thumb_response ) ) {
			$error_string = $set_thumb_response->get_error_message();
			echo $error_string; // can't
		} else echo( 'ele-btl-ok' );

		wp_die();

	}

	/*
		* Remove the post thumbnail via Ajax
		*
		* @since 1.0.0
		*
		* @access public
	*/
	public static function ele_btl_delete_featured_image() {
	
		if( ! wp_verify_nonce( $_POST[ 'nonce' ], 'ele-btl-ajax-nonce' ) ) die( esc_html__( 'You are not allowed to perform this action!', 'ooohboi-steroids' ) ); // bail if nonce-busted!
		
		$ele_post_id  = intval( $_POST[ 'post_id' ] ); 
		if( ! $ele_post_id ) die( esc_html__( 'Either the Template ID is incorrect or the Media Library file does not exist.', 'ooohboi-steroids' ) ); // bail for non-existing post_id or thumb_id
		
		if( ! current_user_can( 'delete_post', $ele_post_id ) ) die( esc_html__( 'You are not allowed to perform this action!', 'ooohboi-steroids' ) ); // bail if lack of credentials 05.03.2023 | Security Issue patch
		$delete_thumb_response = delete_post_thumbnail( $ele_post_id );

		if ( is_wp_error( $delete_thumb_response ) ) {
			$error_string = $delete_thumb_response->get_error_message();
			echo $error_string; // can't
		} else echo( 'ele-btl-ok' );

		wp_die();

	}

	/*
		* The only way to hack Elementor's template.php
		*
		* @since 1.0.0
		*
		* @access public 
	*/
	public static function ele_btl_mazed() { 

		?>

		<script>
		jQuery( '#tmpl-elementor-template-library-template-local' ).remove(); 
		</script>
	
		<script type="text/template" id="tmpl-elementor-template-library-template-local">

			<# if( thumbnail ) { #>
				<div class="ele-btl-media-wrapper"><a id="ele-btl-insert-media_{{ template_id }}" data-elebtlid="{{ template_id }}" href="#"><span></span><img src="{{ thumbnail }}" /></a><em id="ele-btl-delete-media_{{ template_id }}" data-elebtlid="{{ template_id }}" title="<?php esc_html_e( 'Delete?', 'ooohboi-steroids' ); ?>"></em></div>
			<# } else { #>
				<div class="ele-btl-media-wrapper default"><a id="ele-btl-insert-media_{{ template_id }}" data-elebtlid="{{ template_id }}" href="#"><span></span><img src="<?php echo OoohBoi_Steroids::$OBS_DIR . 'assets/img/ele-btl-preview-placeholder.png'; ?>" /></a></div>
			<# } #>
	
			<div class="elementor-template-library-template-name elementor-template-library-local-column-1 ele-btl-data">{{{ title }}}</div>
			<div class="elementor-template-library-template-meta elementor-template-library-template-type elementor-template-library-local-column-2 ele-btl-data"><span class="ele-btl-label"><?php esc_html_e( 'Type:', 'ooohboi-steroids' ); ?></span> {{{ elementor.translate( type ) }}}, <?php esc_html_e( 'By:', 'ooohboi-steroids' ); ?> {{{ author }}}</div>
			<div class="elementor-template-library-template-meta elementor-template-library-template-author elementor-template-library-local-column-3 ele-btl-data ele-flex"><span class="ele-btl-label"><?php esc_html_e( 'Shortcode:', 'ooohboi-steroids' ); ?></span> <input type="text" onfocus="this.select()" value='[elementor-template id="{{ template_id }}"]'></div>
			<div class="elementor-template-library-template-meta elementor-template-library-template-date elementor-template-library-local-column-4 ele-btl-data"><span class="ele-btl-label"><?php esc_html_e( 'Created:', 'ooohboi-steroids' ); ?></span> {{{ human_date }}}</div>
			<div class="elementor-template-library-template-controls elementor-template-library-local-column-5">
				<button class="elementor-template-library-template-preview ele-btl-button">
					<span class="elementor-button-title"><?php esc_html_e( 'Preview', 'ooohboi-steroids' ); ?></span>
				</button>
				<button class="elementor-template-library-template-action elementor-template-library-template-insert ele-btl-button">
					<span class="elementor-button-title"><?php esc_html_e( 'Insert', 'ooohboi-steroids' ); ?></span>
				</button> 
				<div class="elementor-template-library-template-more-toggle ele-btl-toggle">
					<i class="eicon-ellipsis-h" aria-hidden="true"></i>
					<span class="elementor-screen-only"><?php esc_html_e( 'More actions', 'ooohboi-steroids' ); ?></span>
				</div>
				<div class="elementor-template-library-template-more">
					<div class="elementor-template-library-template-delete">
					<span class="elementor-template-library-template-control-title"><?php esc_html_e( 'Delete', 'ooohboi-steroids' ); ?></span>
					</div>
					<div class="elementor-template-library-template-export">
					<a href="{{ btl_export_link }}">
						<span class="elementor-template-library-template-control-title"><?php esc_html_e( 'Export', 'ooohboi-steroids' ); ?></span>
					</a>
					</div>
				</div>
			</div>

		</script>

	<?php
	}

	/* templates import-export ---------------------------------------------------- */

	/*
		* Register ajax action for custom export template
		*
		* @since 1.0.0
		*
		* @access public 
	*/
	public static function register_ajax_actions( Ajax $ajax_manager ) {

		$ajax_manager->register_ajax_action( 'btl_library_direct_actions', [ __CLASS__, 'btl_handle_direct_actions' ] );
		
	}

	/*
		* Handler for btl_library_direct_actions ajax action
		*
		* @since 1.0.0
		*
		* @access public 
	*/
	public static function btl_handle_direct_actions() {

		$ajax = Plugin::$instance->common->get_component( 'ajax' );

		if ( ! $ajax->verify_request_nonce() ) {
			self::btl_handle_direct_action_error( 'Access Denied' );
		}

		$action = $_REQUEST[ 'the_action' ];

		$result = self::$action( $_REQUEST );

		if ( is_wp_error( $result ) ) {
			self::btl_handle_direct_action_error( $result->get_error_message() . '.' );
		}

		die;

	}

	/*
		* Remove post row action to WP Admin for elementor_library post type
		*
		* @since 1.0.0
		*
		* @access public 
	*/
	public static function btl_post_row_actions( $actions, \WP_Post $post ) {

		if ( Source_Local::is_base_templates_screen() ) {

			unset( $actions[ 'inline hide-if-no-js' ] );

		}

		return $actions;
	}

	/*
		* Post row export link params - direct ajax action
		*
		* @since 1.0.0
		*
		* @access public 
	*/
	public static function btl_get_export_link( $template_id ) {

		return add_query_arg(
			[
				'action' => 'btl_library_direct_actions',
				'the_action' => 'btl_export_template',
				'source' => 'local',
				'_nonce' => wp_create_nonce( 'elementor_ajax' ),
				'template_id' => $template_id,
			],
			admin_url( 'admin-ajax.php' )
		);

	}

	/*
		* Export local template content
		*
		* @since 1.0.0
		*
		* @access public 
	*/
    public static function btl_export_template( array $args ) {

		$validate_args = self::btl_ensure_args( [ 'source', 'template_id' ], $args );

		if ( is_wp_error( $validate_args ) ) {
			return $validate_args;
		}

		$manager_source = new Manager();
		$source = $manager_source->get_source( $args[ 'source' ] );

		if ( ! $source ) {
			return new \WP_Error( 'template_error', 'Template source not found' );
		}

		return self::btl_download_template( $args[ 'template_id' ] );

    }

	/**
	 * Download target template
	 *
	 * @since 1.0.0
	 */
	public static function btl_download_template( $template_id ) {

		$file_data = self::btl_prepare_template_export( $template_id );

		if ( is_wp_error( $file_data ) ) {
			return $file_data;
		}

		self::btl_send_file_headers( $file_data[ 'name' ], strlen( $file_data[ 'content' ] ) );

		@ob_end_clean();

		flush();

		// Export widget json
		echo $file_data[ 'content' ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		die;
		
	}

	/*
		* Prepare export local template content
		*
		* @since 1.0.0
		*
		* @access private 
	*/
    private static function btl_prepare_template_export( $template_id ) {

        $document = Plugin::$instance->documents->get( $template_id );

        $template_data = $document->get_export_data();
        
        if ( empty( $template_data[ 'content' ] ) ) {
			// allow export on behalf of the site settings
            //return new \WP_Error( 'empty_template', 'The template is empty' ); 
        }
		/* append thumb_id path to content */
		$template_data[ 'content' ][ 0 ][ 'thumb_URL' ] = get_the_post_thumbnail_url( $template_id, 'full' ); 

		$export_data = [
			'content' => $template_data[ 'content' ],
			'page_settings' => $template_data['settings'],
			'version' => DB::DB_VERSION,
			'title' => $document->get_main_post()->post_title,
			'type' => Source_Local::get_template_type( $template_id ),
		];
		$template_name = get_post_field( 'post_name', $template_id );
		return [
			'name' => 'OB-' . $template_name . '.json',
			'content' => json_encode( $export_data ),
		];
        
    }

	/*
		* Send file headers for the template exported
		*
		* @since 1.0.0
		*
		* @access private 
	*/
    private static function btl_send_file_headers( $file_name, $file_size ) {

        header( 'Content-Type: application/octet-stream' );
        header( 'Content-Disposition: attachment; filename=' . $file_name );
        header( 'Expires: 0' );
        header( 'Cache-Control: must-revalidate' );
        header( 'Pragma: public' );
        header( 'Content-Length: ' . $file_size );

    }

	/*
	 	* Get item for export
	 	*
	 	* Retrieve the template type from the post meta.
		*
		* @since 1.0.0
		*
		* @access public 
	*/
	public static function ele_btl_get_item( $data ) {
		
		$data[ 'btl_export_link' ] = self::btl_get_export_link( $data[ 'template_id' ] );
		return $data;

	}

	/*
	 	* Get local template type. 
	 	*
	 	* Retrieve the template type from the post meta.
		*
		* @since 1.0.0
		*
		* @access public 
	*/
	public static function btl_get_template_type( $template_id ) {

		return get_post_meta( $template_id, Document::TYPE_META_KEY, true );

	}

	/*
	 	* Direct library action error
		*
		* @since 1.0.0
		*
		* @access public 
	*/
	private static function btl_handle_direct_action_error( $message ) {

		_default_wp_die_handler( $message, 'Elementor Library' );

	}

	/*
	 	* Arguments mismatch chck
		*
		* @since 1.0.0
		*
		* @access public 
	*/
	private static function btl_ensure_args( array $required_args, array $specified_args ) {

		$not_specified_args = array_diff( $required_args, array_keys( $specified_args ) );

		if ( $not_specified_args ) {
			return new \WP_Error( 'arguments_not_specified', sprintf( 'The required argument(s) "%s" not specified.', implode( ', ', $not_specified_args ) ) );
		}

		return true;

	}

	/**
	 * Add thumbnail to elementor_library post type
	 *
	 * ignore if "thumb_id" not being part of the JSON
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public static function btl_on_template_save( $template_id, $template_data ) {

		// bail out if this is an autosave
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		// bail if no thumb_id exists
		if( ! isset( $template_data[ 'content' ][ 0 ][ 'thumb_URL' ] ) ) return;

		// _thumbnail_id URL
		$thumb_file = $template_data[ 'content' ][ 0 ][ 'thumb_URL' ];
		$filename = basename( $thumb_file );

		// Get the path to the upload directory.
		$wp_upload_dir = wp_upload_dir();

		// download from remote
		$request = wp_safe_remote_get( $thumb_file );
		// Make sure the request returns a valid result.
		if ( is_wp_error( $request ) || ( ! empty( $request[ 'response' ][ 'code' ] ) && 200 !== ( int ) $request[ 'response' ][ 'code' ] ) ) {
			return false;
		}
		$file_content = wp_remote_retrieve_body( $request );
		if ( empty( $file_content ) ) {
			return false; // bail if none!
		}
		$filetype = wp_check_filetype( $filename );
		// exit here if file type not recognized by WordPress - avoid creation of an empty attachment document.
		if ( ! $filetype[ 'ext' ] ) {
			return false;
		}
		// if unfiltered-files upload is not enabled, SVG images should not be imported
		if ( 'svg' === $filetype[ 'ext' ] ) {
			// disabled?
			if ( ! Uploads_Manager::are_unfiltered_uploads_enabled() ) {
				return false;
			}
			// enabled!
			$svg_handler = Plugin::$instance->uploads_manager->get_file_type_handlers( 'svg' );
			$file_content = $svg_handler->sanitizer( $file_content );
		};
		$upload = wp_upload_bits( $filename, null, $file_content );
		$post = [
			'post_title' => $filename,
			'guid' => $upload[ 'url' ],
		];
		$info = wp_check_filetype( $upload[ 'file' ] );
		if ( $info ) {
			$post[ 'post_mime_type' ] = $info[ 'type' ];
		} else {
			return new \WP_Error( 'attachment_processing_error', esc_html__( 'Invalid file type.', 'ooohboi-steroids' ) );
		}
		$post_id = wp_insert_attachment( $post, $upload[ 'file' ], $template_id );
		if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
			require_once ABSPATH . '/wp-admin/includes/image.php';
		}
		wp_update_attachment_metadata( $post_id, wp_generate_attachment_metadata( $post_id, $upload[ 'file' ] ) );
		update_post_meta( $template_id, '_thumbnail_id', $post_id );

	}

	/**
	 * Export multiple action to WP Admin table drop-down
	 *
	 * @since 1.0.0
	 *
	 * @return array new array of actions
	 */
	public static function btl_bulk_export_add_action( $drop_actions ) {

		$drop_actions[ 'btl_bulk_export' ] = esc_html__( 'Export with BTL', 'ooohboi-steroids' );
		return $drop_actions;

	}

	/**
	 * Add the Export multiple action call
	 *
	 * @since 1.0.0
	 */
	public static function btl_bulk_export_trigger( $redirect, $action, $selected_posts ) { 

		if ( 'btl_bulk_export' === $action ) {

			$result = self::btl_export_multiple( $selected_posts );
			wp_die( $result->get_error_message() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		}

	}

	/**
	 * Export multiple templates to ZIP
	 *
	 * @since 1.0.0
	 *
	 * @return \WP_Error on failure
	 */
	public static function btl_export_multiple( array $all_template ) {

		$selected_files = []; 
		$wp_upload_dir = wp_upload_dir();
		$temp_loc = $wp_upload_dir[ 'basedir' ] . '/elementor/tmp';
		wp_mkdir_p( $temp_loc );

		foreach( $all_template as $template_id ) {

			$template_data = self::btl_prepare_template_export( $template_id );
			if( is_wp_error( $template_data ) ) continue; // ignore!
			$the_path = $temp_loc . '/' . $template_data[ 'name' ];
			$put_contents = file_put_contents( $the_path, $template_data[ 'content' ] );

			if( ! $put_contents ) return new \WP_Error( '404', sprintf( 'Cannot create file "%s".', $template_data['name'] ) );

			$selected_files[] = [
				'path' => $the_path,
				'name' => $template_data[ 'name' ],
			];
		}

		if( ! $selected_files ) return new \WP_Error( 'btl_missing_files', 'There is nothing to export, please check back!' );

		$zipped_files = 'OB-templates-library-' . time() . '.zip';
		$zip_archive = new \ZipArchive();
		$zip_complete_path = $temp_loc . '/' . $zipped_files;
		$zip_archive->open( $zip_complete_path, \ZipArchive::CREATE );
		foreach( $selected_files as $file ) $zip_archive->addFile( $file[ 'path' ], $file[ 'name' ] );
		$zip_archive->close();
		foreach ( $selected_files as $file ) unlink( $file[ 'path' ] );

		self::btl_send_file_headers( $zipped_files, filesize( $zip_complete_path ) );
		@ob_end_flush();

		@readfile( $zip_complete_path );

		unlink( $zip_complete_path );

		die;
	}

}