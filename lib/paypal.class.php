<?php

class Paypal {

/**
 * Implementa i pagamenti da un sito web tramite le API Paypal.
 * Originale: http://www.smashingmagazine.com/2011/09/05/getting-started-with-the-paypal-api/
 *
 * ISTRUZIONI:
 * 
 * Per prima cosa va richiamata l'API SetExpressCheckout, tramite il metodo
 * request(). Il metodo necessita che gli venga passato, oltre al nome dell'API
 * da richiamare, anche un array contenente tutti i parametri necessari all'API
 * stessa: tale array è composto di tre array uniti assieme tramite l'operatore 
 * "+" e vanno settati, di base, più o meno così sulla pagina in cui viene
 * richiamato il metodo:
 *
 * $orderParams = array(
 *  'PAYMENTREQUEST_m_AMT' => $total,
 *  'PAYMENTREQUEST_m_CURRENCYCODE' => 'EUR',
 * );
 *
 * $item = array(
 *  'L_PAYMENTREQUEST_0_NAMEm' => 'Oggetto',
 *  'L_PAYMENTREQUEST_0_DESCm' => 'Descrizione oggetto',
 *  'L_PAYMENTREQUEST_0_AMTm' => $total_item,
 *  'L_PAYMENTREQUEST_0_QTYm' => $qt
 * );
 *
 * dove m è una variabile che va da 0 a 9 che identifica il numero
 * dell'articolo all'interno dell'array. Per ogni
 * richiesta possono esserci al massimo 10 articoli: esempi sono
 * 'L_PAYMENTREQUEST_0_NAME0' => 'Articolo 1'
 * 'L_PAYMENTREQUEST_0_NAME1' => 'Articolo 2'
 * 'L_PAYMENTREQUEST_0_NAME2' => 'Articolo 3'
 * e così via.
 *
 * I parametri settati e inviati dal metodo request() saranno poi visualizzati
 * nella pagina di checkout sotto "Riepilogo ordine" e sono obbligatori
 *
 * Il primo array riguarda i valori totali dell'ordine: nell'esempio ci sono solo
 * il totale in PAYMENTREQUEST_m_AMT (già ivato e compreso di spese di trasporto!)
 * e la valuta utilizzata per il pagamento, in PAYMENTREQUEST_0_CURRENCYCODE, che 
 * un utilizzo veramente di base sono sufficienti.
 * Il secondo array (OBBLIGATORIO!), invece, riguarda i singoli articoli: per ognuno è
 * specificato, nell'esempio il nome, la descrizione, il costo totale (sempre ivato 
 * e con spese di trasporto!) e la quantità. Se non si vogliono processare i singoli
 * articoli del carrello, si può creare in item "fasullo", sulla falsa riga di 
 * quanto fa Trenitalia.com quando vengono acquistati i biglietti tramite Paypal.
 * Nota: la somma dei singoli costi per articolo deve corrispondere al totale generale!
 *
 * Esistono ovviamente una miriade di altri parametri utilizzabili in entrambi gli
 * array: per maggiori info in merito consultare la relativa pagina sul sito di Paypal, 
 * https://developer.paypal.com/docs/classic/api/merchant/SetExpressCheckout_API_Operation_NVP/
 *
 * A questo punto effettuiamo la SetExpressCheckout:
 * 
 * $paypal = Paypal::getInstance();
 * $paypal->setCredentials($paypal_user, $paypal_pwd, $paypal_sign);
 * $paypal->setUrls($returnurl, $cancelurl);
 * $paypal->request('SetExpressCheckout', $orderParams + $item);
 *
 * Se tutto è andato a buon fine, Paypal dovrebbe aver fatto un redirect alla pagina
 * settata in RETURNURL, passando via GET i parametri "token" e "PayerID".
 * Da qui ora si può completare la transazione, non prima però di aver ottenuto 
 * alcune informazioni extra sul checkout e sull'utente attraverso la chiamata all'API
 * GetExpressCheckoutDetails.
 *
 * Nella pagina, bisogna inserire queste righe di codice:
 *
 * if( isset($_GET['token']) && !empty($_GET['token']) ) { // Token parameter exists
 *   // Get checkout details, including buyer information.
 *   // We can save it for future reference or cross-check with the data we have
 *   $paypal = Paypal::getInstance();
 *   $checkoutDetails = $paypal->request('GetExpressCheckoutDetails', array('TOKEN' => $_GET['token']));
 *
 * (NOTA: la parentesi dell'if resta aperta, ci andrà altro codice)
 * Ottenuti i dettagli, che possono essere utilizzati in seguito per vari scopi
 * (si veda https://developer.paypal.com/docs/classic/api/merchant/GetExpressCheckoutDetails_API_Operation_NVP/)
 * possiamo completare definitivamente la transazione attraverso l'ultima API,
 * DoExpressCheckoutPayment.
 *
 * Dobbiamo preparare un altro array simile a questo e aggiungerlo nel codice riportato sopra:
 *
 * $requestParams = array(
 *      'TOKEN' => $_GET['token'],
 *      'PAYMENTACTION' => 'Sale', // Questo, salvo casi particolari, resta così
 *      'PAYERID' => $_GET['PayerID'],
 *      'PAYMENTREQUEST_0_AMT' => $total, // Same amount as in the original request
 *      'PAYMENTREQUEST_0_CURRENCYCODE' => 'EUR' // Same currency as the original request
 *  );
 *
 * Dopodichè richiamiamo nuovamente il metodo request():
 *
 * $response = $paypal->request('DoExpressCheckoutPayment',$requestParams);
 *  if( is_array($response) && $response['ACK'] == 'Success') { // Payment successful
 *      // We'll fetch the transaction ID for internal bookkeeping
 *      $transactionId = $response['PAYMENTINFO_0_TRANSACTIONID'];
 *  }
 * } // Questa parentesi chiude l'if iniziale!
 *
 * E con questo la transazione è terminata. In questo caso salviamo l'id della transazione
 * in una variabile, magari per loggarlo o utilizzarlo altrove (vedi Orderwave).
 * Per maggiori dettagli sui parametri accettati e ritornati da DoExpressCheckoutPayment, consultare
 * https://developer.paypal.com/docs/classic/api/merchant/DoExpressCheckoutPayment_API_Operation_NVP/
 */

	private static $instance = NULL;

	/**
	 * Last error message(s)
     * @var array
     */
	protected $_errors = array();

	/**
     * API Credentials
     * Use the correct credentials for the environment in use (Live / Sandbox)
     * @var array
     */
	protected $_credentials = array(
		'USER' => 'seller_1297608781_biz_api1.lionite.com',
		'PWD' => '1297608792',
		'SIGNATURE' => 'A3g66.FS3NAf4mkHn3BDQdpo6JD.ACcPc4wMrInvUEqO3Uapovity47p',
	);

	/**
	 * URL utilizzati da Paypal alla fine della SetExpressCheckout
	 *
	 * RETURNURL - Quando si completa la fase iniziale del chekout, in cui l'utente
	 * effettua il login sulla pagina di Paypal, Paypal ritorna alla
	 * pagina selezionata due parametri che serviranno poi per le fasi 
	 * successive della transazione (nuovamente il token ottenuto dal
	 * checkout e PayerId, che contiene l'id dell'account Paypal del compratore)
	 * CANCELURL - Pagina a cui Paypal rimanda se non si abortisce il checkout
	 *
	 * @var array
	 */
	protected $_returnurls = array(
		'RETURNURL' => 'http://www.yourdomain.com/payment/success',
 		'CANCELURL' => 'http://www.yourdomain.com/payment/cancelled'
	);

	/**
     * API endpoint
     * Live - https://api-3t.paypal.com/nvp
     * Sandbox - https://api-3t.sandbox.paypal.com/nvp
     * @var string
     */
	protected $_endPoint = 'https://api-3t.sandbox.paypal.com/nvp';

	/**
     * API Version
     * @var string
     */
	protected $_version = '74.0';

	/**
     * the constructor is set to private so
     * so nobody can create a new instance using new
	 */
	private function __construct() {
	}

	/**
     *
     * Return Paypal instance or create intitial instance
     *
     * @access public
     *
     * @return object
     */
	public static function getInstance() {
		if(is_null(self::$instance)) {
            self::$instance = new Paypal;
        }

        return self::$instance;
	}

	/**
	 * Set Paypal credentials. If a sandbox account is used, use
	 * facilitator's account credentials (they can be found logging in
	 * your develepers Paypal account)
	 *
	 * @param string $user
	 * @param string $passoword
	 * @param string $sign
	 */
	public function setCredentials($user, $password, $sign) {
		$this->_credentials['USER'] = $user;
		$this->_credentials['PWD'] = $password;
		$this->_credentials['SIGNATURE'] = $sign;
	}

	/**
	 * Set Paypal's return URLs
	 * 
	 * @param string $returnurl
	 * @param string $cancelurl
	 */
	public function setUrls($returnurl, $cancelurl) {
		if(strpos($returnurl, "http://") == false) {
			$this->_returnurls['RETURNURL'] = 'http://' . $returnurl;
		}

		else {
			$this->_returnurls['RETURNURL'] = $returnurl;
		}

		if(strpos($returnurl, "http://") == false) {
			$this->_returnurls['CANCELURL'] = 'http://' . $cancelurl;
		}

		else {
			$this->_returnurls['CANCELURL'] = $cancelurl;
		}
	}

	/**
	 * For debug only, returns Paypal credentials
	 */
	public function getCredentials() {
		return $this->_credentials;
	}

	/**
     * Make API request
     *
     * @param string $method string API method to request
     * @param array $params Additional request parameters
     * @return array / boolean Response array / boolean false on failure
     */
	public function request($method,$params = array()) {
		$this->_errors = array();

		if( empty($method) ) { //Check if API method is not empty
			$this->_errors = array('API method is missing');

			return false;
		}

		//Our request parameters
		$requestParams = array(
			'METHOD' => $method,
			'VERSION' => $this->_version
		) + $this->_credentials + $this->_returnurls;

		//Building our NVP string
		$request = http_build_query($requestParams + $params);

		//cURL settings
		$curlOptions = array (
			CURLOPT_URL => $this->_endPoint,
			CURLOPT_VERBOSE => 1,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => $request
		);

		$ch = curl_init();
		curl_setopt_array($ch,$curlOptions);

		//Sending our request - $response will hold the API response
		$response = curl_exec($ch);

		//Checking for cURL errors
		if (curl_errno($ch)) {
			$this->_errors = curl_error($ch);
			curl_close($ch);

			return false;
			//Handle errors
		} 

		else  {
			curl_close($ch);
			$responseArray = array();
			parse_str($response,$responseArray); // Break the NVP string to an array
 
			//return $responseArray;

			ppCheckoutRedirect($responseArray);
		}
	}

	/**
	 * Get token from SetExpressCheckout request and redirect to Paypal login screen
	 * LIVE: https://www.paypal.com/webscr?cmd=_express-checkout&token=' . urlencode($token)
	 * SANDBOX: https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&token=' . urlencode($token)
	 * 
	 * @param array $response - The array returned by a SetExpressCheckout API request
	 */
	private function ppCheckoutRedirect($response) {
		if(is_array($response) && $response['ACK'] == 'Success') { //Request successful
			$token = $response['TOKEN'];
			header( 'Location: https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&token=' . urlencode($token) );
		}

		else {
			// da modificare, magari loggando l'errore e redirigendo a una pagina specifica!
			echo "[" . $response['TIMESTAMP'] . "] API Error Code " . $response['L_ERRORCODE0'] . " : " . $response['L_SHORTMESSAGE0'] . " - " . $response['L_LONGMESSAGE0'] ;
			return false;
		}
	}
}
