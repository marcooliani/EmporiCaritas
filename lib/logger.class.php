<?php

class Logger {

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
     * Constructor
	 *
     * @param string $logfilename Path and name of the file log.
     */
    private function __construct($logfilename = '/logs/customlog.log') {
        $this->LOGFILENAME = $logfilename;
    }

    /**
     *
     * Return Logger instance or create intitial instance
     *
     * @access public
     *
     * @return object
     *
     */
    public static function getInstance() {
        if(is_null(self::$instance)) {
            self::$instance = new Logger;
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
     */
    private function log($errorlevel = 'INFO', $value = '') {
        $datetime = @date("D M d Y H:i:s");

        $fd = fopen($this->LOGFILENAME, "a");

        $debugBacktrace = debug_backtrace();
        $line = $debugBacktrace[1]['line'];
        $file = $debugBacktrace[1]['file'];
        $value = preg_replace('/\s+/', ' ', trim($value));
        $entry = "[" . $datetime . "] " . $errorlevel . ": " . $value . " in " . $file . " on line " . $file);

        fwrite($fd, $entry);

        fclose($fd);
    }

    /**
     * Function to write non INFOrmation messages that will be written into $LOGFILENAME.
     * 
     * @param string $value
     */
    public function info($value = '') {
        self::log('INFO', $value);
    }

    /**
     * Function to write WARNING messages that will be written into $LOGFILENAME.
     *
     * Warning messages are for non-fatal errors, so, the script will work properly even
     * if WARNING errors appears, but this is a thing that you must ponderate about.
     * 
     * @param string $value
     */
    public function warning($value = '') {
        self::log('WARNING', $value);
    }

    /**
     * Function to write ERROR messages that will be written into $LOGFILENAME.
     *
     * These messages are for fatal errors. Your script will NOT work properly if an ERROR happens, right?
     * 
     * @param string $value
     */
    public function error($value = '') {
        self::log('ERROR', $value);
    }

    /**
     * Function to write DEBUG messages that will be written into $LOGFILENAME.
     *
     * DEBUG messages are for variable values and other technical issues.
     * 
     * @param string $value
     */
    public function debug($value = '') {
        self::log('DEBUG', $value);
    }
}
