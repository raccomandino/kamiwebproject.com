<?php
namespace Elementor;

if (!defined('ABSPATH')) { exit; }

class Elementor_Html5Sortable_Control extends Base_Data_Control {

	public function get_type() {
		return 'html5sortable';
    }
    
    public function enqueue() {
        wp_enqueue_style('html5sortable-css', EHE_URL . 'assets/css/html5sortable.css', array(), EHE_VERSION);
		wp_enqueue_script('html5sortable-js', EHE_URL . 'assets/js/html5sortable.min.js', array(), EHE_VERSION);
    }
    
    protected function get_default_settings() {
		return [
			'label_block' => true,
			'options' => [],
		];
    }
    
    public function content_template() {

        $control_uid = $this->get_control_uid();
        
		?>
		<div class="elementor-control-field">
			<label for="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-title">{{{ data.label }}}</label>
			
			<div class="elementor-control-input-wrapper">
				<ul id="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-html5sortable" data-setting="{{ data.name }}">
					<# _.each(options, function(title, value) { #>
					<li data-value="{{ value }}"><i class="fa fa-bars"></i> {{ title }}</li>
					<# }); #>
                </ul>
			</div>
		</div>
		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
        
		<?php
	}

}