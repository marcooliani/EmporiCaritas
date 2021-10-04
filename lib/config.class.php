<?php
class Config {
	/*
	* @var string $config_file
	*/
	private static $config_file = '/config.ini';

	/*
	 * @var array $config_values; 
	 */
	public $config_values = array();

	/*
	* @var object $instance
	*/
	private static $instance = null;

	/**
	 *
	 * Return Config instance or create intitial instance
	 *
	 * @access public
	 *
	 * @return object
	 *
	 */
	public static function getInstance() {
		if(is_null(self::$instance)) {
			self::$instance = new Config;
		}

		return self::$instance;
	}

	private function __construct() {
		$this->config_values = parse_ini_file(__SITE_PATH . self::$config_file, true);
	}

	/**
	 * @get a config option by key
	 *
	 * @access public
	 *
	 * @param string $key:The configuration setting key
	 *
	 * @return string
	 *
	 */
	public function getValue($key) {
		return self::$config_values[$key];
	}

	/**
	 *
	 * @__clone
	 *
	 * @access private
	 *
	 */
	private function __clone() {
	}

}
