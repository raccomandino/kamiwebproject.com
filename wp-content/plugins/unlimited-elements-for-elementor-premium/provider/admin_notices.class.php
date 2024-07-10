<?php

/**
 * @package Unlimited Elements
 * @author UniteCMS http://unitecms.net
 * @copyright Copyright (c) 2016 UniteCMS
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

// No direct access
defined('UNLIMITED_ELEMENTS_INC') or die ('restricted aceess');

class UniteCreatorAdminNotices{

	const OPTIONS_KEY = 'unlimited_elements_notices';
	const NOTICES_LIMIT = 2;
	const TYPE_ADVANCED = 'advanced';
	const TYPE_BANNER = 'banner';

	private static $isInited = false;
	private static $arrNotices = array();

	/**
	 * set notice
	 */
	public function setNotice($text, $id, $params = array()){

		// Don't let to add more than limited notices
		if(count(self::$arrNotices) >= self::NOTICES_LIMIT)
			return;

		$type = UniteFunctionsUC::getVal($params, "type");

		if(empty($text) && $type != self::TYPE_BANNER)
			return;

		if(empty($id))
			return;

		$arrNotice = array();
		$arrNotice["text"] = $text;
		$arrNotice["id"] = $id;

		if(!empty($params)){
			unset($params["text"]);
			unset($params["id"]);

			$arrNotice = array_merge($arrNotice, $params);
		}

		if(isset(self::$arrNotices[$id]))
			return;

		self::$arrNotices[$id] = $arrNotice;

		
		$this->init();
	}

	/**
	 * put admin notices
	 */
	public function putAdminNotices(){

		if(empty(self::$arrNotices))
			return;

		//echo "<pre>";
		//print_r(self::$arrNotices);
		//echo "</pre>";

		foreach(self::$arrNotices as $notice){
			$isDebug = $this->isNoticeDebugging($notice);

			if($isDebug === true){
				echo $this->getNoticeHtml($notice);
				continue;
			}

			$isDismissed = $this->isNoticeDismissed($notice);

			if($isDismissed === true)
				continue;

			$isConditionAllowed = $this->isConditionAllowed($notice);

			if($isConditionAllowed === false)
				continue;

			$isScheduleAllowed = $this->isScheduleAllowed($notice);

			if($isScheduleAllowed === false)
				continue;

			echo $this->getNoticeHtml($notice);
		}
	}

	/**
	 * put admin styles
	 */
	public function putAdminStyles(){

		?>
		<!--  unlimited elements notices styles -->
		<style type="text/css">

			.uc-admin-notice {
				position: relative;
			}

			.uc-admin-notice.uc-notice-advanced {
				font-size: 16px;
			}

			.uc-admin-notice--nowrap {
				padding: 0px !important;
				border: none !important;
				background-color: transparent !important;
			}

			.uc-admin-notice.uc-type-banner {
				border-left-width: 1px !important;
			}

			.uc-admin-notice .uc-notice-advanced-wrapper span {
				display: table-cell;
				vertical-align: middle;
			}

			.uc-admin-notice .uc-notice-advanced-wrapper .button {
				vertical-align: middle;
				margin-left: 10px;
			}

			.uc-admin-notice .uc-notice-advanced__item-logo {
				padding-right: 15px;
			}

			.uc-admin-notice .uc-notice-dismiss {
				position: absolute;
				top: 0px;
				right: 10px;
				padding: 10px 15px 10px 21px;
				font-size: 13px;
				text-decoration: none;
			}

			.uc-admin-notice .uc-notice-dismiss::before {
				position: absolute;
				top: 10px;
				left: 0px;
				transition: all .1s ease-in-out;

				background: none;
				color: #72777c;
				content: "\f153";
				display: block;
				font: normal 16px/20px dashicons;
				speak: none;
				height: 20px;
				text-align: center;
				width: 20px;
			}

			.uc-admin-notice .uc-notice-dismiss:focus::before,
			.uc-admin-notice .uc-notice-dismiss:hover::before {
				color: #c00;
			}

			.uc-notice-banner-link {
				display: block;
			}

			.uc-notice-banner {
				width: 100%;
			}

			.uc-notice-debug {
				font-size: 12px;
				margin-top: 10px;
				color: #72777c;
			}

			.uc-notice-dismiss-banner {
				background-color: #000000;
				position: absolute;
				top: 20px;
				right: 23px;
				height: 20px;
				width: 20px;
				border-radius: 20px;
				font-size: 12px;
				text-decoration: none;
				color: #ffffff;
				text-align: center;
			}

			.uc-notice-dismiss-banner:hover {
				color: #ffffff;
				background-color: #c00;
			}

			.uc-notice-dismiss-banner:focus,
			.uc-notice-dismiss-banner:visited {
				color: #ffffff;
			}

			.uc-notice-doubly {
				border-left-color: #ff6a00 !important;
				border-color: #ff6a00 !important;
			}

			.uc-notice-header {
				font-weight: bold;
				font-size: 16px;
			}

			.uc-notice-middle {
				padding-top: 10px;
				padding-bottom: 18px;
			}

			.uc-notice-wrapper {
				display: flex;
			}

			.uc-notice-left {
				padding-left: 15px;
				padding-right: 30px;
			}

		</style>
		<?php
	}

	/**
	 * init
	 */
	private function init(){

		if(self::$isInited === true)
			return;

		if(GlobalsUC::$is_admin === false)
			return;

		// Set plugin installation time
		$installTime = $this->getOption('install_time');
		
		if(empty($installTime)){
			$this->setOption('install_time', time());
		}
		
		$this->checkDismissAction();

		UniteProviderFunctionsUC::addFilter('admin_notices', array($this, 'putAdminNotices'), 10, 3);
		UniteProviderFunctionsUC::addAction('admin_print_styles', array($this, 'putAdminStyles'));

		self::$isInited = true;
	}

	/**
	 * check dismiss action
	 */
	private function checkDismissAction(){

		$noticeId = UniteFunctionsUC::getPostGetVariable('uc_dismiss_notice', '', UniteFunctionsUC::SANITIZE_KEY);

		if(empty($noticeId))
			return;
		
		$this->setNoticeOption(array('id' => $noticeId), 'dismissed', true);
	}

	/**
	 * get notice html
	 */
	private function getNoticeHtml($notice, $isDismissible = true){

		$id = UniteFunctionsUC::getVal($notice, "id");
		$text = UniteFunctionsUC::getVal($notice, "text");
		$type = UniteFunctionsUC::getVal($notice, "type");

		$isNoWrap = UniteFunctionsUC::getVal($notice, "no-notice-wrap");
		$isNoWrap = UniteFunctionsUC::strToBool($isNoWrap);

		$classWrap = "notice ";

		if($isNoWrap)
			$classWrap = "";

		//set color class
		$color = UniteFunctionsUC::getVal($notice, "color");

		switch($color){
			default:
			case "error":
			case "warning":
			case "info":
				$noticeClass = "notice-$color";
			break;
			case "doubly":
				$noticeClass = "uc-notice-doubly";
			break;
		}

		$class = "notice uc-admin-notice $noticeClass";

		if($type == self::TYPE_ADVANCED)
			$class .= " uc-notice-advanced";

		if($type == self::TYPE_BANNER){
			$class = "notice uc-admin-notice uc-type-banner";

			if($isNoWrap == true)
				$class .= " uc-admin-notice--nowrap";
		}

		$htmlDismiss = "";

		if($isDismissible){
			$textDismiss = __("Dismiss", "unlimited-elements-for-elementor");
			$textDismissLabel = __("Dismiss unlimited elements message", "unlimited-elements-for-elementor");

			$textDismiss = esc_attr($textDismiss);
			$textDismissLabel = esc_attr($textDismissLabel);

			$urlDismiss = GlobalsUC::$current_page_url;
			$urlDismiss = UniteFunctionsUC::addUrlParams($urlDismiss, "uc_dismiss_notice=$id");

			$htmlDismiss = "\n<a class=\"uc-notice-dismiss\" href=\"{$urlDismiss}\" aria-label=\"$textDismissLabel\">$textDismiss</a>\n";

			if($type == self::TYPE_BANNER)
				$htmlDismiss = "\n<a class=\"uc-notice-dismiss-banner\" href=\"{$urlDismiss}\" title=\"{$textDismiss}\" aria-label=\"$textDismissLabel\">X</a>\n";
		}

		switch($type){
			case self::TYPE_ADVANCED:

				$buttonText = UniteFunctionsUC::getVal($notice, "button_text");
				$buttonLink = UniteFunctionsUC::getVal($notice, "button_link");

				$urlLogo = GlobalsUC::$urlPluginImages . "logo-circle.svg";

				$htmlButton = "";

				if(!empty($buttonText)){
					$htmlButton = "<a class='button button-primary' href='{$buttonLink}' target='_blank'>{$buttonText}</a>";
				}

				$text = "<div class='uc-notice-advanced-wrapper'>
					<span class='uc-notice-advanced__item-logo'>
						<img class='uc-image-logo-ue' width=\"40\" src='$urlLogo' alt=\"Logo\" />
					</span>
					<span class='uc-notice-advanced__item-text'>" . $text . $htmlButton . "</span>
				</div>";

			break;
			case self::TYPE_BANNER:

				$filename = UniteFunctionsUC::getVal($notice, "banner");

				if(empty($filename))
					return '';

				$urlBanner = GlobalsUC::$urlPluginImages . $filename;

				$buttonLink = UniteFunctionsUC::getVal($notice, "button_link");

				$text = "<a class='uc-notice-banner-link' href='{$buttonLink}' target='_blank'>
					<img class='uc-notice-banner' src='{$urlBanner}' alt='' />
				</a>";

			break;
		}

		$isDebug = $this->isNoticeDebugging($notice);
		$htmlDebug = "";

		if($isDebug === true){
			$status = $this->getNoticeDebugStatus($notice);

			$htmlDebug = "<div class='uc-notice-debug'><b>DEBUG:</b> Notice {$status}</div>";
		}

		$html = "<div class=\"$class\"><p>";
		$html .= $text . "\n";
		$html .= $htmlDismiss;
		$html .= $htmlDebug;
		$html .= "</p></div>";

		return ($html);
	}

	/**
	 * check if the notice is in debug mode
	 */
	private function isNoticeDebugging($notice){

		$isDebug = UniteFunctionsUC::getVal($notice, 'debug');
		$isDebug = UniteFunctionsUC::strToBool($isDebug);

		return $isDebug;
	}

	/**
	 * get the debug status of the notice
	 */
	private function getNoticeDebugStatus($notice){

		$isDismissed = $this->isNoticeDismissed($notice);

		if($isDismissed === true)
			return 'hidden - dismissed';

		$isConditionAllowed = $this->isConditionAllowed($notice);

		if($isConditionAllowed === false)
			return 'hidden - false condition';

		$isScheduleAllowed = $this->isScheduleAllowed($notice);

		if($isScheduleAllowed === false)
			return 'hidden - scheduled';

		return 'visible';
	}

	/**
	 * check if the notice was dismissed
	 */
	private function isNoticeDismissed($notice){

		$isDismissed = $this->getNoticeOption($notice, 'dismissed', false);
		$isDismissed = UniteFunctionsUC::strToBool($isDismissed);

		return $isDismissed;
	}

	/**
	 * check if the notice condition is allowed
	 */
	private function isConditionAllowed($notice){

		$condition = UniteFunctionsUC::getVal($notice, "condition");

		if(empty($condition))
			return true;

		switch($condition){
			case "no_doubly":
				if(defined("DOUBLY_INC"))
					return false;
			break;
		}

		return true;
	}

	/**
	 * check if the notice schedule is allowed
	 */
	private function isScheduleAllowed($notice){

		$duration = intval(UniteFunctionsUC::getVal($notice, 'duration')) * 3600;
		$startTime = intval(UniteFunctionsUC::getVal($notice, 'start')) * 3600;
		$installTime = intval($this->getOption('install_time'));
		$currentTime = time();
		$installDiff = $currentTime - $installTime;

		//echo $installDiff;
		//echo "<br>" . $currentTime . "<br>";

		if($installDiff > $startTime){
			$finishTime = $this->getNoticeOption($notice, 'finish_time');

			if(empty($finishTime)){
				$finishTime = $currentTime + $duration;

				$this->setNoticeOption($notice, 'finish_time', $finishTime);
			}

			if($installDiff < $finishTime){
				return true;
			}
		}

		return false;
	}

	/**
	 * get all options
	 */
	private function getOptions(){
		
		$options = get_option(self::OPTIONS_KEY, array(
			'options' => array(),
			'notices' => array(),
		));
		
		return $options;
	}

	/**
	 * set all options
	 */
	private function setOptions($options){

		update_option(self::OPTIONS_KEY, $options);
	}

	/**
	 * get the option value
	 */
	private function getOption($key, $fallback = null){

		$options = $this->getOptions();
		$value = UniteFunctionsUC::getVal($options['options'], $key, $fallback);

		return $value;
	}

	/**
	 * set the option value
	 */
	private function setOption($key, $value){

		$options = $this->getOptions();
		$options['options'][$key] = $value;
		
		$this->setOptions($options);
	}

	/**
	 * get the notice option value
	 */
	private function getNoticeOption($notice, $key, $fallback = null){

		$options = $this->getOptions();

		$noticeId = UniteFunctionsUC::getVal($notice, 'id');
		$noticeOptions = UniteFunctionsUC::getVal($options['notices'], $noticeId, array());

		$value = UniteFunctionsUC::getVal($noticeOptions, $key, $fallback);

		return $value;
	}

	/**
	 * set the notice option value
	 */
	private function setNoticeOption($notice, $key, $value){
		
		$options = $this->getOptions();

		$noticeId = UniteFunctionsUC::getVal($notice, 'id');
		
		$noticeOptions = UniteFunctionsUC::getVal($options['notices'], $noticeId, array());
		$noticeOptions[$key] = $value;

		$options['notices'][$noticeId] = $noticeOptions;

		$this->setOptions($options);
	}

}
