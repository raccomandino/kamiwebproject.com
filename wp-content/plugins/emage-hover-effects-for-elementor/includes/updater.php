<?php

if (!defined('ABSPATH')) {
    exit;
}

require_once EHE_PATH . 'includes/plugin-update-checker/plugin-update-checker.php';

if (!class_exists('Blocksera_Updater')) {

    class Blocksera_Updater {

        /**
         * Update remote url
         * @var string
        **/
        protected $endpoint = 'https://api.blocksera.com/updates';

        /**
         * Plugin slug (plugin_directory/main_plugin_file.php)
         * @var string
        **/
        public $plugin_path;

        /**
         * Plugin slug (my-awesome-plugin)
         * @var string
        **/
        public $slug;

        /**
         * Blocksera product ID
         * @var string
        **/
        public $product_id;

        /**
         * Purchase code
         * @var string
        **/
        public $code;

        public function __construct($plugin_path, $slug, $product_id, $code) {

            $this->plugin_path = $plugin_path;
            $this->slug = $slug;
            $this->product_id = $product_id;
            $this->code = $code;
            $this->endpoint = $this->endpoint . '/' . $this->product_id;

            $this->checker = Puc_v4_Factory::buildUpdateChecker($this->endpoint, $this->plugin_path, $this->slug);

            $this->checker->addQueryArgFilter(function($queryargs) {
                $queryargs = array_merge($queryargs, array(
                    'code' => $this->code,
                    'remove' => 'false',
                    'site' => get_site_url()
                ));
                return $queryargs;
            });

        }

        public function request_info($params) {

            $this->checker->addQueryArgFilter(function($queryargs) use ($params) {
                $queryargs = array_merge($queryargs, $params);
                return $queryargs;
            });

            $response = $this->checker->requestInfo();

            return $response;

        }

    }

}