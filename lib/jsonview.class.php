<?php

/**
 * Esporto i dati in formato JSON, così da poterli passare come output
 * a funzioni JQuery che utilizzano Ajax (o anche per altri scopi, che
 * al momento ignoro).
 *
 * Esempio d'uso:
 *
 * $data = "blablablabla"; 
 * $json = JsonView::getInstance();
 * $json->headers();  // Non necessaria, se la classe è usata in script AJAX
 * $json->output($data);
 *
 * @author Marco Oliani
 */

class JsonView {
	private static $instance = NULL;
    public $data = null;

	/**
     * the constructor is set to private so
     * so nobody can create a new instance using new
     */
    private function __construct() {
    }

	/**
     *
     * Return JsonView instance or create intitial instance
     *
     * @access public
     *
     * @return object
     *
     */
    public static function getInstance() {
        if(is_null(self::$instance)) {
            self::$instance = new JsonView;
        }

        return self::$instance;
    }

	/**
     * Sets proper Content-Type header and clean the output buffer
	 * 
	 */
	public function headers() {
		header("Content-type: application/json");
		ob_clean();
	}

	/**
	 * Return JSON encoded data.
	 *
	 * @param object $data
	 */
	public function output($data) {
		$this->data = json_encode($data);
		echo $this->data;
		// exit(); 
	}

}

