<?php

 /*** include the controller class ***/
 include __SITE_PATH . '/application/' . 'controller_base.class.php';

 /*** include the registry class ***/
 include __SITE_PATH . '/application/' . 'registry.class.php';

 /*** include the router class ***/
 include __SITE_PATH . '/application/' . 'router.class.php';

 /*** include the template class ***/
 include __SITE_PATH . '/application/' . 'template.class.php';


 /*** nullify any existing autoloads ***/
 spl_autoload_register(null, false);

 /*** specify extensions that may be loaded ***/
 spl_autoload_extensions('.php, .class.php, .lib.php');

 /*** model(s) Loader ***/
 function modelLoader($class) {
	$filename = strtolower($class) . '.class.php';
    $file =  __SITE_PATH . '/model/' . $filename;

    if (!file_exists($file)) {
        return false;
    }

    include $file;
 }

 /*** User-defined classes Loader ***/
 function libLoader($class) {
		$filename = strtolower($class) . '.class.php';
		$file = __SITE_PATH . '/lib/' . $filename;

		if (!file_exists($file)) {
			return false;
		}

		include $file;
  }

 /*** register the loader functions ***/
 spl_autoload_register('modelLoader');
 spl_autoload_register('libLoader');

 /*** a new registry object ***/
 $registry = new Registry;

 /*** create the database registry object ***/
 /* Volendo si può aggiungere questa riga nei singoli controller, così da non avere un oggetto globale 
  * EDIT: l'if serve per far funzionare il setup, altrimenti mi darebbe di continuo errore del db!
  */
 if($_SERVER['REQUEST_URI'] != "/setup" && $_SERVER['REQUEST_URI'] != "/setup/" && $_SERVER['REQUEST_URI'] != "/setup/index") {
 	$registry->db = DbObject::getInstance();
 }

?>
