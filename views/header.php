<?php
$config = Config::getInstance();
$title = $config->config_values['emporio']['nome_emporio'];
if(empty($title)) { $title = "Emporio senza nome"; }

$head = PageHeader::getInstance();
$head->addMeta("<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">");
$head->addMeta("<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">");
$head->setTitle(ucwords($title));

$head->addStyle("/public/bootstrap/css/bootstrap.min.css");
$head->addStyle("/public/css/jquery-ui.min.css");
$head->addStyle("/public/font-awesome/css/font-awesome.min.css");
$head->addStyle("/public/css/style.css");
$head->addStyle("/public/css/notifiche.css");
$head->addStyle("/public/css/messaggi.css");
$head->addStyle("/public/css/dataTables.bootstrap.css");
$head->addStyle("/public/css/datepicker3.css");
$head->addStyle("/public/css/select2.min.css");
$head->addStyle("/public/css/chatbox.css");

$head->addScript("/public/js/jquery-1.11.3.min.js");
$head->addScript("/public/bootstrap/js/bootstrap.min.js");
$head->addScript("/public/js/jquery-ui.min.js");
$head->addScript("/public/js/jquery.printPage.js");
$head->addScript("/public/js/bootstrap-datepicker.js");
$head->addScript("/public/js/jquery.validate.min.js");
$head->addScript("/public/js/messages_it.js");
$head->addScript("/public/js/jquery.dataTables.js");
$head->addScript("/public/js/dataTables.bootstrap.js");
$head->addScript("/public/js/select2.min.js");
$head->addScript("/public/js/chatbox.js");

$head->write();


?>

<?php session_start(); ?>

<body>
<div class="col-md-12">
    <div class="row">


