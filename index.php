<?php

 /*** define the site path ***/
 $site_path = realpath(dirname(__FILE__));
 define ('__SITE_PATH', $site_path);

 /*** error reporting on ***/
 error_reporting(E_ALL);
 ini_set('display_errors','Off');
 ini_set('log_errors', 'On');
 $date = date('Ymd');
 ini_set('error_log', __SITE_PATH . '/logs/error.'.$date.'.log');

 /*** include the init.php file ***/
 include  __SITE_PATH . '/includes/init.php';

 /*** load the router ***/
 $registry->router = new Router($registry);

 /*** set the controller path ***/
 $registry->router->setPath (__SITE_PATH . '/controller');

 /*** load up the template ***/
 $registry->template = new Template($registry);

 /*** load the controller ***/
 $registry->router->loader();

?>
