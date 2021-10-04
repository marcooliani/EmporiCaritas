<?php
/**
 * MyLogPHP 1.2.5
 *
 * MyLogPHP is a single PHP class to easily keep log files in CSV format.
 *
 * @package    MyLogPHP
 * @subpackage Common
 * @author     Lawrence Lagerlof <llagerlof@gmail.com>
 * @copyright  2014 Lawrence Lagerlof
 * @link       http://github.com/llagerlof/MyLogPHP
 * @license    http://opensource.org/licenses/BSD-3-Clause New BSD License
 */
class Logger_CSV {
	/*
     * @var object $instance
     */
    private static $instance = null;

	/**
	 * Name of the file where the message logs will be appended.
	 * @access private
	 */
	private $LOGFILENAME;

	/**
	 * Define the separator for the fields. Default is comma (,).
	 * @access private
	 */
	private $SEPARATOR;

	/**
	 * The first line of the log file.
	 * @access private
	 */
	private $HEADERS;

	/* @const Default tag. */
	const DEFAULT_TAG = '--';
	
	/**
	 * Constructor
	 * @param string $logfilename Path and name of the file log.
	 * @param string $separator Character used for separate the field values.
	 */
	private function __construct($logfilename = '/logs/customlog.csv', $separator = ';') {
		$this->LOGFILENAME = $logfilename;
		$this->SEPARATOR = $separator;
		$this->HEADERS =
			'DATETIME' . $this->SEPARATOR . 
			'ERRORLEVEL' . $this->SEPARATOR .
			'TAG' . $this->SEPARATOR .
			'VALUE' . $this->SEPARATOR .
			'LINE' . $this->SEPARATOR .
			'FILE';
	}

	/**
     *
     * Return Logger_CSV instance or create intitial instance
     *
     * @access public
     *
     * @return object
     *
     */
    public static function getInstance() {
        if(is_null(self::$instance)) {
            self::$instance = new Logger_CSV;
        }

        return self::$instance;
    }

	/**
	 * Set log file name
	 *
	 * @param string $logfilename - Log file name (path allowed)
	 */
	public function setLogFileName($logfilename) {
		$this->LOGFILENAME = $logfilename;
	}

	/**
	 * Private method that will write the text messages into the log file.
	 * 
	 * @param string $errorlevel There are 4 possible levels: INFO, WARNING, DEBUG, ERROR
	 * @param string $value The value that will be recorded on log file.
	 * @param string $tag Any possible tag to help the developer to find specific log messages.
	 */
	private function log($errorlevel = 'INFO', $value = '', $tag) {
		$datetime = @date("Y-m-d H:i:s");

		if (!file_exists($this->LOGFILENAME)) {
			$headers = $this->HEADERS . "\n";
		}

		$fd = fopen($this->LOGFILENAME, "a");

		if (@$headers) {
			fwrite($fd, $headers);
		}

		$debugBacktrace = debug_backtrace();
		$line = $debugBacktrace[1]['line'];
		$file = $debugBacktrace[1]['file'];
		$value = preg_replace('/\s+/', ' ', trim($value));
		$entry = array($datetime,$errorlevel,$tag,$value,$line,$file);

		fputcsv($fd, $entry, $this->SEPARATOR);

		fclose($fd);
	}

	/**
	 * Function to write non INFOrmation messages that will be written into $LOGFILENAME.
	 * 
	 * @param string $value
	 * @param string $tag 
	 */
	public function info($value = '', $tag = self::DEFAULT_TAG) {
		self::log('INFO', $value, $tag);
	}

	/**
	 * Function to write WARNING messages that will be written into $LOGFILENAME.
	 *
	 * Warning messages are for non-fatal errors, so, the script will work properly even
	 * if WARNING errors appears, but this is a thing that you must ponderate about.
	 * 
	 * @param string $value
	 * @param string $tag 
	 */
	public function warning($value = '', $tag = self::DEFAULT_TAG) {
		self::log('WARNING', $value, $tag);
	}

	/**
	 * Function to write ERROR messages that will be written into $LOGFILENAME.
	 *
	 * These messages are for fatal errors. Your script will NOT work properly if an ERROR happens, right?
	 * 
	 * @param string $value
	 * @param string $tag 
	 */
	public function error($value = '', $tag = self::DEFAULT_TAG) {
		self::log('ERROR', $value, $tag);
	}

	/**
	 * Function to write DEBUG messages that will be written into $LOGFILENAME.
	 *
	 * DEBUG messages are for variable values and other technical issues.
	 * 
	 * @param string $value
	 * @param string $tag 
	 */
	public function debug($value = '', $tag = self::DEFAULT_TAG) {
		self::log('DEBUG', $value, $tag);
	}
}

