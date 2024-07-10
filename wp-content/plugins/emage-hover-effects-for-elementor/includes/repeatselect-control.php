<?php
namespace Elementor;

if (!defined('ABSPATH')) { exit; }

class Elementor_RepeatSelect_Control extends Base_Data_Control {

	public function get_type() {
		return 'repeatselect';
    }
    
    public function enqueue() {
        wp_enqueue_style('selectize-css', EHE_URL . 'assets/css/selectize.css', array(), EHE_VERSION);
		wp_enqueue_script('selectize-js', EHE_URL . 'assets/js/selectize.js', array(), EHE_VERSION);
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
                <select multiple id="<?php echo esc_attr( $control_uid ); ?>" data-setting="{{ data.name }}">
                    <# _.each(options, function(title, value) { #>
					<option value="{{ value }}">{{ title }}</option>
                    <# }); #>
                </select>
			</div>
		</div>
		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
        
		<?php
	}

}