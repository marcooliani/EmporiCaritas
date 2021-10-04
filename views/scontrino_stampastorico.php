<?php
session_start();

$config = Config::getInstance();
$title = $config->config_values['emporio']['nome_emporio'];
if(empty($title)) { $title = "Emporio senza nome"; }

$head = PageHeader::getInstance();
$head->addMeta("<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">");
$head->addMeta("<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">");
$head->setTitle(ucwords($title));

$head->addStyle("/public/bootstrap/css/bootstrap.min.css");
$head->addStyle("/public/css/jquery-ui.min.css");
$head->addStyle("/public/css/datepicker3.css");
$head->addStyle("/public/font-awesome/css/font-awesome.min.css");

$head->addScript("/public/js/jquery-1.11.3.min.js");
$head->addScript("/public/bootstrap/js/bootstrap.min.js");
$head->addScript("/public/js/jquery-ui.min.js");
$head->write();

$config = Config::getInstance();
$nome_emporio = $config->config_values['emporio']['nome_emporio'];
$fontsize = $config->config_values['settings']['scontrino_fontsize'];

?>

<style>
@import url(https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700);

html,body {
    padding-left:8px;
    padding-right:8px;
    font-family: 'Open Sans', sans-serif;

}
body,td {font-size: <?php echo $fontsize; ?> ;}
th { height: 16px; }
td { height: 12px; }
</style>

<body>
Emporio: <strong>
	<?php
		echo ucwords($nome_emporio);
	?> </strong><br>
Data: <?php echo date("d/m/Y", strtotime($data_scontrino)); ?><br>
Scontrino: <?php echo $scontrino; ?>
<br><br>

Cliente: <strong><?php echo $cliente; ?></strong><br>
Scadenza tessera: <strong><?php echo $scadenza; ?></strong><br><br>


	<table class="" width="100%" border="0">
		<th>Articolo</th>
		<th>Prezzo</th>
		<th>Q.t&agrave;</th>
		<th>Totale</th>
				
<?php
        foreach ($spesa as $key=>$val) {
			if($val['agea']) {
				$agea = "[AGEA]";
			}
			else { $agea = ""; }
?>
			
			<tr>
            <td><?php echo $val['descrizione']. " ".$agea; ?></td>
            <td><?php echo $val['punti']; ?></td>
            <td>x<?php echo $val['qta']; ?></td>
            <td><?php echo $val['punti'] * $val['qta']; ?></td>
            </tr>

<?php
        } // End of foreach loop!
?>

		</tbody>
	</table>

<div>&nbsp;</div>
<div style="text-align:right">
	Totale spesa: <strong><?php echo $totale_spesa; ?></strong><br>
<!--	Punti residui: <strong><?php echo $_SESSION['punti_residui']; ?></strong> -->
</div>

<br>

<div align="center">Grazie e arrivederci!</div>

</body>
</html>
