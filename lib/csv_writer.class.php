<?php

/**
 * Simple class to properly output CSV data to clients. PHP 5 has a built
 * in method to do the same for writing to files (fputcsv()), but many times
 * going right to the client is beneficial.
 *
 * Esempio d'uso (sostanzialmente ogni riga deve essere un array):
 *
 * $data = array(array("one","two","three"), array(4,5,6));
 * $delim = ";"; // opzionale!
 * $csv = CSV_Writer::getInstance();
 * $csv->setData($data);
 * $csv->setDeliminator($delim); // opzionale!
 * $csv->headers('test');
 * $csv->output();
 *
 * @author Jon Gales - modified by Marco Oliani for his personal PHP Framework
 */

class CSV_Writer {

	private static $instance = NULL;

    public $data = array();
    public $deliminator;

    /**
     * Loads optionally a deliminator. Data is assumed to be an array
     * of associative arrays.
     *
     * the constructor is set to private so
     * so nobody can create a new instance using new
     *
     * @param string $deliminator
     */
    private function __construct($deliminator = ";") {
        $this->deliminator = $deliminator;
    }

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
            self::$instance = new CSV_Writer;
        }

        return self::$instance;
    }

	/**
	 * Set data. Data is assumed to be an array of associative arrays.
	 */
	public function setData($data) {
		if (!is_array($data)) {
            throw new Exception('CSV_Writer only accepts data as arrays');
        }

		$this->data = $data;
	}

	/*
	 * Setta un delimitatore al posto di quello di default del costruttore 
	 */
	public function setDeliminator($deliminator) {
		$this->deliminator = $deliminator;
	}


	/*
	 * Teoricamente va a mettere i doppi apici a ogni valore presente nei sottoarray.
	 * Visto che non mi interessa e, anzi, i doppi apici nel csv NON ce li voglio,
	 * la funzione resta inutilizzata, commentando le relative istruzioni in output()
	 */
    private function wrap_with_quotes($data) {
        $data = preg_replace('/"(.+)"/', '""$1""', $data);
        return sprintf('"%s"', $data);
    }

    /**
     * Echos the escaped CSV file with chosen delimeter
     *
     * @return void
     */
    public function output() {
        foreach ($this->data as $row) {
			// Disabilito il quoting dei singoli dati. Se serve, basta decommentare le due
			// righe sottostanti e commentare l'echo al momento attivo...
			//
			// $quoted_data = array_map(array('CSV_Writer', 'wrap_with_quotes'), $row);
            // echo sprintf("%s\n", implode($this->deliminator, $quoted_data));

            echo sprintf("%s\n", implode($this->deliminator, $row));
        }
	
		// Questo mi serve per evitare che nel csv mi venga scritto anche tutto il codice
		// del footer: esco appena finito il metodo e non fa più il flush del resto..	
		exit();
    }

    /**
     * Sets proper Content-Type header and attachment for the CSV outpu
     *
     * @param string $name
     * @return void
     */
    public function headers($name) {
        header('Content-Type: text/csv');
        header("Content-disposition: attachment; filename={$name}.csv");

		// Eliminino tutto l'output che precedentemente bufferizzato.
		// In questo modo posso utilizzare la classe e il metodo anche
		// all'interno del framework senza andare a modificare la struttura
		// di quest'ultimo per far andar gli header...
		ob_clean();
    }
}
