<?php
/*	$filename="agea_dichiarazione_".date("Ymd", strtotime($data_report)).".doc";
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=\"".$filename."\""); */

?>
<!DOCTYPE html>
<html lang=it>
<head>
<style>
body {font-family: "Arial", Georgia, Sans-Serif;font-size: 14pt;line-height: 125%;}
table {border: 1px solid #000; border-spacing: 0;border-collapse: collapse; width:100%}
th {border: 1px solid #000; padding:2px; font-size:12pt; text-align:center; vertical-align:middle}
td {border: 1px solid #000; padding:2px; font-size:12pt; vertical-align:middle}
.dati {font-size:14pt;}
.footer {font-size:10pt;text-align:right;text-transform:uppercase;}
</style>

<title>Titolo</title>
</head>

<body>

<center>
<p style="font-size:10pt">
<strong>AIUTI CE – REG. CE 3149/92<br>DICHIARAZIONE DI CONSEGNA AGLI INDIGENTI DI PRODOTTI ALIMENTARI GRATUITI</strong>
</p>
</center>

<br><br>

<table style="border: 1px solid #fff">
<tr>
<!-- <td style="font-size:10pt" bordercolor="black">NUMERO</td><td width="15%" style="font-size:10pt; font-weight:bold" bordercolor="black"><?php //echo date("z", strtotime($data_report)); ?></td> -->
<td style="font-size:10pt" >NUMERO</td><td width="15%" style="font-size:10pt; font-weight:bold" ><?php echo $numconsegna; ?></td>
<td width="50%" style="border: 1px solid #fff"></td>
<td style="font-size:10pt" >DATA</td><td style="font-size:10pt"><strong><?php echo date("d/m/Y", strtotime($data_report)); ?></strong></td>
</tr>
</table>

<br><br>

<?php
	$val = $ris_emporio->fetch(); 
?>

<p>
Il sottoscritto <strong class="dati"> <?php echo ucwords($val['nome_responsabile'])." ".ucwords($val['cognome_responsabile']); ?> </strong><br>
nato a <strong class="dati"> <?php echo ucwords($val['comune_n'])." (".$val['provincia_n'].")"; ?> </strong> il <strong class="dati"> <?php echo date("d/m/Y", strtotime($val['data_nascita'])); ?> </strong><br>
in qualit&agrave; di legale rappresentante del <strong class="dati"> <?php echo ucwords($val['nome_associazione']); ?> </strong>,<br>
con sede a <strong class="dati"> <?php echo ucwords($val['comune_a'])." (".$val['provincia_a'].") - ".ucwords($val['indirizzo_associazione']); ?> </strong><br>
consapevole che chiunque rilasci dichiarazioni mendaci &egrave; punito ai sensi del codice penale
e delle leggi speciali in materia, ai sensi e per gli effetti degli artt. 75 e 76 del D.P.R. n.
445/200;<br>
ai sensi di quanto previsto dal capitolo 7 dell'Organismo Pagatore AGEA n. DPMU. 2012.2818 del 14/09/2012
</p>

<br>

<center>
	<strong>DICHIARA</strong>
</center>

<p>
A) che da me e/o rappresentanti della Struttura di cui in premessa, da me delegati, sono stati distribuiti 
in data odierna a n. <strong class="dati"><?php echo $indigenti; ?></strong> indigenti i seguenti prodotti:
</p>

<table width="100%">
<thead>
	<th >PRODOTTO</th>
	<th >Unit&agrave; di misura</th>
	<th >QUANTIT&Agrave;</th>
</thead>
<tbody>
<?php
	$righe = 0;
	$pagina = 1;
	foreach($ris as $key=>$val2) {
		print("</tr>
				<td>".ucfirst($val2['descrizione'])."</td>
				<td>".$val2['um_1']."</td>
				<td>".$val2['qta']."</td>
				</tr>"); 
		$righe++;
		if($righe > 8 || $pagina < 2) {
			print("<p style='page-breake-after:always'></p>");
			$pagina++;
		}
		else if($pagina >= 2 && $righe % 30 == 0) {
			print("<p style='page-breake-after:always'></p>");
		}
	}
?>
</tbody>
</table>

<br>

<p>
B) che i su indicati dati vengono riportati sul registro di carico e scarico
</p>
<p>
Allego fotocopia integrale, fronte e retro, di un documento di identità in corso di validit&agrave;
</p>

<br><br>

<p class="footer">Timbro dell'ente caritativo e firma del legale rappresentante<br><br>
............................................................................................................................
</p>

</body>
</html>

