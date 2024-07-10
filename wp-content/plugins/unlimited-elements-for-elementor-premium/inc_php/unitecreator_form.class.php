<?php
/**
 * @package Unlimited Elements
 * @author unlimited-elements.com
 * @copyright (C) 2021 Unlimited Elements, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('UNLIMITED_ELEMENTS_INC') or die('Restricted access');

class UniteCreatorForm extends HtmlOutputBaseUC{
	
	private static $isFormIncluded = false;	  //indicator that the form included once
	
	
	/**
	 * add form includes
	 */
	public function addFormIncludes(){
		
		if(self::$isFormIncluded == true)
			return(false);
		
		//include common scripts only once
		if(self::$isFormIncluded == false){
			
			$urlFormJS = GlobalsUC::$url_assets_libraries."form/uc_form.js";
			
			UniteProviderFunctionsUC::addAdminJQueryInclude();
			HelperUC::addScriptAbsoluteUrl($urlFormJS, "uc_form");
			
		}
				
		//include addon scripts
		$script  = "\n\n jQuery(document).ready(function(){\n";
		$script .= "	var objUCForm = new UnlimitedElementsForm();\n";
		$script .= "	objUCForm.init();\n";
		$script .= "});\n";
		
		UniteProviderFunctionsUC::printCustomScript($script);
		
		self::$isFormIncluded = true;
		
		
	}
	
	/**
	 * add conditions elementor control
	 */
	public static function getConditionsRepeaterSettings(){
		
		$settings = new UniteCreatorSettings();

		//--- operator
		
		$params = array();
		$params["origtype"] = UniteCreatorDialogParam::PARAM_DROPDOWN;
		
		$arrOptions = array("And"=>"and","Or"=>"or");
		
		$settings->addSelect("operator", $arrOptions, __("Operator","unlimited-elements-for-elementor"),"and",$params);
		
		//--- field name
		
		$params = array();
		$params["origtype"] = UniteCreatorDialogParam::PARAM_TEXTFIELD;
		
		$settings->addTextBox("field_name","",__("Field Name","unlimited-elements-for-elementor"),$params);
		
		//--- condition
		
		$params = array();
		$params["origtype"] = UniteCreatorDialogParam::PARAM_DROPDOWN;
		
		$arrOptions = array(
			"=" => "= (equal)",
			">" => "> (more)",
			">=" => ">= (more or equal)",
			"<" => "< (less)",
			"<=" => "<= (less or equal)",
			"!=" => "!= (not equal)");
		
		$arrOptions = array_flip($arrOptions);
		
		$settings->addSelect("condition", $arrOptions, __("Condition","unlimited-elements-for-elementor"),"=",$params);
		
		//--- value
		
		$params = array();
		$params["origtype"] = UniteCreatorDialogParam::PARAM_TEXTFIELD;
		$params["label_block"] = true;
		
		$settings->addTextBox("field_value","",__("Field Value","unlimited-elements-for-elementor"),$params);
		
		
		return($settings);		
	}
	
	
	
	
}