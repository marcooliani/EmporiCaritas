<?php
session_start();

class ageaController extends BaseController {
    public function index() {
        // TODO
    }

	public function dichiarazione($data = '') {
        if(empty($data)) {
            $data = date("Y-m-d");
        }

        $this->registry->template->data_report = $data;

        $sql_clienti = "SELECT SUM(indigenti) AS indigenti, COUNT(cliente) AS numclienti FROM registro_agea_indigenti WHERE data = '".$data."'";

        $ris = $this->registry->db->query($sql_clienti);
        $val = $ris->fetch();
        $this->registry->template->clienti = $val['numclienti'];
        $this->registry->template->indigenti = $val['indigenti'];

    /*  $sql_prodotti = "SELECT registro_agea.id_tipologia, 
                                categorie.descrizione_categoria AS descrizione, 
                                registro_agea.um_1, 
                                registro_agea.quantita AS qta, 
                                registro_agea.giacenza AS stock
                        FROM registro_agea 
                        INNER JOIN categorie ON categorie.id_categoria = registro_agea.id_tipologia 
                        WHERE data = '".$data."' AND carico = 'f'"; */
        $sql_prodotti = "SELECT registro_agea.id_tipologia, 
                                barcodes.descrizione, 
                                registro_agea.um_1, 
                                registro_agea.quantita AS qta, 
                                registro_agea.giacenza AS stock
                        FROM registro_agea 
                        INNER JOIN barcodes ON barcodes.barcode = registro_agea.id_tipologia 
                        WHERE data = '".$data."' AND carico = 'f'";

        $this->registry->template->ris = $this->registry->db->query($sql_prodotti);

        $this->registry->template->show("ageadichiarazione");
    }

	public function dichiarazione_doc($data = '') {
        $this->registry->template->data_report = $data;

        $sql_numconsegna = $this->registry->db->query("SELECT COUNT(DISTINCT data) AS numconsegna FROM registro_agea WHERE carico = 'f'");
        $val = $sql_numconsegna->fetch();

        $this->registry->template->numconsegna = $val['numconsegna'];
        $numconsegna = $val['numconsegna'];

        $sql = "SELECT emporio_config.*, 
                        c1.nome_comune AS comune_n, 
                        c1.provincia AS provincia_n, 
                        c2.nome_comune AS comune_a, 
                        c2.provincia AS provincia_a 
                FROM emporio_config 
                INNER JOIN comuni AS c1 ON emporio_config.comune_nascita = c1.cod_istat
                INNER JOIN comuni AS c2 ON emporio_config.comune = c2.cod_istat";
        $this->registry->template->ris_emporio = $this->registry->db->query($sql);

        $sql_clienti = "SELECT SUM(indigenti) AS indigenti FROM registro_agea_indigenti WHERE data = '".$data."'";
        $ris = $this->registry->db->query($sql_clienti);
        $val = $ris->fetch();
        $this->registry->template->indigenti = $val['indigenti'];

    /*  $sql_prodotti = "SELECT registro_agea.id_tipologia, 
                                categorie.descrizione_categoria AS descrizione, 
                                registro_agea.um_1, 
                                registro_agea.quantita AS qta, 
                                registro_agea.giacenza AS stock
                        FROM registro_agea 
                        INNER JOIN categorie ON categorie.id_categoria = registro_agea.id_tipologia 
                        WHERE data = '".$data."' AND carico = 'f'"; */
        $sql_prodotti = "SELECT registro_agea.id_tipologia, 
                                barcodes.descrizione, 
                                registro_agea.um_1, 
                                registro_agea.quantita AS qta, 
                                registro_agea.giacenza AS stock
                        FROM registro_agea 
                        INNER JOIN barcodes ON barcodes.barcode = registro_agea.id_tipologia 
                        WHERE data = '".$data."' AND carico = 'f'";

        $this->registry->template->ris = $this->registry->db->query($sql_prodotti);

        $sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                    VALUES('".date("Y-m-d H:i:s")."', 
                            '[AGEA]', 
                            'Stampa dichiarazione di consegna n. ".$numconsegna." (".date("d/m/Y", strtotime($data)).")', 
                            '".$_SESSION['username']."')";
        $count_log = $this->registry->db->exec($sql_log);

        $this->registry->template->show("ageadichiarazione_doc");
    }

	public function caricoscarico() {
	/*	$this->registry->template->ris = $this->registry->db->query("SELECT id_categoria, descrizione_categoria FROM categorie ORDER BY descrizione_categoria ASC"); */
		$this->registry->template->ris = $this->registry->db->query("SELECT barcode, descrizione FROM barcodes ORDER BY descrizione ASC");
		
		$data_fine = date("Y-m-d", strtotime(date("Y-m-d")."- 10 days"));

	/*	$sql_carico = "SELECT registro_agea.*, categorie.descrizione_categoria FROM registro_agea 
				INNER JOIN categorie ON registro_agea.id_tipologia = categorie.id_categoria
				WHERE data BETWEEN '".$data_fine."' AND '".date("Y-m-d")."' 
				AND carico = 't'
				ORDER BY data DESC, categorie.descrizione_categoria ASC";
		
		$sql_scarico = "SELECT registro_agea.*, categorie.descrizione_categoria FROM registro_agea 
				INNER JOIN categorie ON registro_agea.id_tipologia = categorie.id_categoria
				WHERE data BETWEEN '".$data_fine."' AND '".date("Y-m-d")."' 
				AND carico = 'f'
				ORDER BY data DESC, categorie.descrizione_categoria ASC"; */
        $sql_carico = "SELECT registro_agea.*, barcodes.descrizione FROM registro_agea 
                INNER JOIN barcodes ON registro_agea.id_tipologia = barcodes.barcode
                WHERE data BETWEEN '".$data_fine."' AND '".date("Y-m-d")."' 
                AND carico = 't'
                ORDER BY data DESC, barcodes.descrizione ASC";
        
        $sql_scarico = "SELECT registro_agea.*, barcodes.descrizione FROM registro_agea 
                INNER JOIN barcodes ON registro_agea.id_tipologia = barcodes.barcode
                WHERE data BETWEEN '".$data_fine."' AND '".date("Y-m-d")."' 
                AND carico = 'f'
                ORDER BY data DESC, barcodes.descrizione ASC";

		$this->registry->template->ris_carico = $this->registry->db->query($sql_carico);
		$this->registry->template->ris_scarico = $this->registry->db->query($sql_scarico);

		$this->registry->template->datastart = $data_fine;	
		$this->registry->template->datafine = date("Y-m-d");	

		$this->registry->template->show('agearegistro');	
	}

	private function caricoscarico_doc_frontespizio($num_struttura, $emporio, $data_start, $data_fine) {
		print('<div style="float:right; display:block; width:25%; text-align:right; font-size:12pt;"><i>Allegato n. 8</i></div>
                <div style="clear:both;">&nbsp;</div>
				<br><br><br><br>
				<center>
					<h1><i>- Registro di carico e scarico dei prodotti alimentari AGEA</i></h1>
					<h2><i>(Reg. CE 3149/92 e successive modifiche)</i></h2>
					<br><br><br><br>
					<br><br><br><br>
					<h1><i>Struttura caritativa: '.$num_struttura.'</i></h1>
					<br>
					<h1><i>'.ucwords($emporio).'</i></h1>
				</center>
				<br><br>');

		print('<p style="page-break-after:always;"></p>');

		$sql_datiemporio = $this->registry->db->query("SELECT emporio_config.*, comuni.nome_comune, comuni.provincia FROM emporio_config INNER JOIN comuni ON emporio_config.comune = comuni.cod_istat");

        // Serve la prima data utile nel registro
        $sql_firstdate = $this->registry->db->query("SELECT MIN(data) FROM registro_agea WHERE data >= '".date('Y')."-01-01'");
		$val = $sql_datiemporio->fetch();
		$val_data = $sql_firstdate->fetch();

		print('<center>
                    <h1><i>- Registro di carico e scarico dei prodotti alimentari AGEA</i></h1>
                    <h2><i>(Reg. CE 3149/92 e successive modifiche)</i></h2>
				</center>
				<br>
				<h1><i>Struttura caritativa:</i> '.ucwords($emporio).' <i>(</i>'.$num_struttura.'<i>)</i></h1>
				<h1><i>Indirizzo:</i>'.ucwords($val['indirizzo_associazione']).' </h1>
				<h1><i>Citt&agrave;:</i>'.ucwords($val['nome_comune']).' &nbsp;&nbsp;&nbsp; </i>Prov: '.$val['provincia'].'</h1>
				<br><br>
				<h1><i>Il presente registro è composto da</i> ....... <i>pagine numerate dalla n.</i> 1<i> <br>
					alla n.</i> ....... <i>recanti il timbro della Struttura caritativa e tutte firmate <br>
					da me sottoscritto</i> '.ucwords($val['nome_responsabile']).' '.ucwords($val['cognome_responsabile']).' <i>legale rappresentante della struttura<i></h1>
				<div style="float:left; display:block; width:50%; text-align:leftr;"><h1><i>Data</i></h1><h2>'.date('d/m/Y', strtotime($val_data['data'])).'</h2></div>
				<div style="float:right; display:block; width:50%; text-align:center;"><h1><i>Firma</i></h1>
				<h3>..........................................................................................................................</h3></div>
                <div style="clear:both;">&nbsp;</div>');

		print('<p style="page-break-after:always;"></p>');
	}

	private function caricoscarico_doc_header($data_start, $data_fine, $offset, $pagina, $num_struttura) {
		/* Intestazione di ogni pagina del registro */
            print('<div style="float:left; display:block; width:25%; font-size:10pt;">STRUTTURA CARITATIVA<br>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<strong>'.$num_struttura.'</strong></div>
                <div style="float:left; display:block; width:50%; text-align:center; font-size:10pt;">REGISTRO DI CARICO E SCARICO<br>dei prodotti alimentari assegnati ai sensi del Reg. (UE) 807/2010</div>
                <div style="float:right; display:block; width:25%; text-align:right; font-size:10pt;">pagina n. <strong>'.$pagina.'</strong></div>
                <div style="clear:both;">&nbsp;</div>');

            print('<table>');
            print('<tr><th colspan="2">DOCUM. CONSEGNA</th>');

            $limit = 4;
            $start_offset = $offset;

         /* $sql_categorie = "SELECT DISTINCT(registro_agea.id_tipologia), categorie.descrizione_categoria FROM registro_agea
                                INNER JOIN categorie ON registro_agea.id_tipologia = categorie.id_categoria
                                WHERE registro_agea.data BETWEEN '".$data_start."' AND '".$data_fine."'
                                ORDER BY categorie.descrizione_categoria ASC
                                LIMIT ".$limit." OFFSET ".$start_offset; */
            $sql_categorie = "SELECT DISTINCT(registro_agea.id_tipologia), barcodes.descrizione FROM registro_agea
                                INNER JOIN barcodes ON registro_agea.id_tipologia = barcodes.barcode
                                WHERE registro_agea.data BETWEEN '".$data_start."' AND '".$data_fine."'
                                ORDER BY barcodes.descrizione ASC
                                LIMIT ".$limit." OFFSET ".$start_offset;
            $ris = $this->registry->db->query($sql_categorie);

            $cnt=0;
            $array_cat = array();
            foreach($ris as $key=>$val_cat) {
            /*  print('<th colspan="4">'.mb_strtoupper($val_cat['descrizione_categoria']).'</th>'); */
                print('<th colspan="4">'.mb_strtoupper($val_cat['descrizione']).'</th>');
                array_push($array_cat, $val_cat['id_tipologia']);
                $cnt++;
            }

            // Sstampo i campi vuoti
            for($k=0; $k< 4 - $cnt; $k++) {
                print('<th colspan="4">&nbsp;</th>');
            }

            print('<th rowspan="2">DESTINATARIO</th></tr>');
            print('<tr><td>DATA</td><td>NUMERO</td>');

            //for($z=0; $z<$cnt; $z++) {
            for($z=0; $z<4; $z++) {
                print('<td>Unit&agrave; m</td><td>CARICO</td><td>SCARICO</td><td>GIACENZA</td>');
            }
            print('</tr>');

			return array($cnt,$array_cat);
	}

	private function caricoscarico_doc_footer() {
		print('</table>');

		/* Footer di ogni pagina del registro */
		print('<div>&nbsp;</div>');
		print('<div style="float:right; display:block; width:100%; text-align:right; font-size:10pt;">TIMBRO DELLA STRUTTURA CARITATIVA E FIRMA DEL LEGALE RAPPRESENTANTE<br>
                    ..............................................................................................................................................................</div>
                    <div style="clear:both;"></div>');

	}

	public function caricoscarico_doc($start = '', $end = '') {
		//if(!empty($_REQUEST['fake-form-datestart'])) {
		if(!empty($start)) {
        //    $data_start = date("Y-m-d", strtotime($_REQUEST['fake-form-datestart']));
            $data_start = date("Y-m-d", strtotime($start));
        }
        else {
            $data_start = date("Y-m-d", strtotime(date("Y-m-d")."- 10 days"));
        }

        //if(!empty($_REQUEST['fake-form-dateend'])) {
        if(!empty($end)) {
        //    $data_fine = date("Y-m-d", strtotime($_REQUEST['fake-form-dateend']));
            $data_fine = date("Y-m-d", strtotime($end));
        }
        else {
            $data_fine = date("Y-m-d");
        }

		print('<style>
					@media print{
						@page {size: landscape} 
						table {border: 1px solid #000; border-spacing: 0;border-collapse: collapse; width:100%}
						th {border: 1px solid #000; padding:2px; font-size:8pt; text-align:center; vertical-align:middle} 
						td {border: 1px solid #000; padding:2px; font-size:8pt}
						p {display: block; page-break-after: always;}
					}
					table {border: 1px solid #000; border-spacing: 0;border-collapse: collapse; width:100%}
					th {border: 1px solid #000; padding:2px;}
					td {border: 1px solid #000; padding:2px;}
					p {display: block; page-break-after: always;}
				</style>');

		$config = Config::getInstance();
		$num_struttura = $config->config_values['emporio']['num_struttura'];
		$emporio = $config->config_values['emporio']['nome_emporio'];

		$this->caricoscarico_doc_frontespizio($num_struttura, $emporio, $data_start, $data_fine);

		$sql_countcat = "SELECT COUNT(DISTINCT id_tipologia) AS numcategorie FROM registro_agea WHERE data BETWEEN '".$data_start."' AND '".$data_fine."'";
		$ris = $this->registry->db->query($sql_countcat);
		$val = $ris->fetch();
		$numcategorie = $val['numcategorie'];

		$pagina = "3";

		for($i=0; $i<$numcategorie; $i=$i+4) {
			$limit = 4;
			$start_offset = $i;

			$fnh = $this->caricoscarico_doc_header($data_start, $data_fine, $i, $pagina, $num_struttura);
			$cnt = $fnh[0];
			$array_cat = array();
			$array_cat = $fnh[1];
			
			$sql_date = "SELECT DISTINCT data FROM registro_agea WHERE registro_agea.data BETWEEN '".$data_start."' AND '".$data_fine."' ORDER BY data ASC";
			$ris2 = $this->registry->db->query($sql_date);

			foreach($ris2 as $key=>$val_date) {
                $and_query = "";
                $length = sizeof($array_cat);
                for($p=0; $p<$length -1; $p++) {
                    $and_query .= "id_tipologia = '".$array_cat[$p]."' OR ";    
                }
                $length--;
                $and_query .= "id_tipologia = '".$array_cat[$length]."' ";

				$check_carico = $this->registry->db->query("SELECT EXISTS(SELECT 1 FROM registro_agea WHERE carico = 't' AND data = '".$val_date['data']."' AND (".$and_query."))");
				$row = $check_carico->fetch();

				if($row['exists']) {
				    print('<tr>');
					print("<td>".date("d/m/Y", strtotime($val_date['data']))."</td>");
					$query_ddt = $this->registry->db->query("SELECT ddt FROM registro_agea WHERE carico = 't' AND data = '".$val_date['data']."'");
					$val_ddt = $query_ddt->fetch();
                    print('<td>'.$val_ddt['ddt'].'</td>');

					for($p=0; $p<sizeof($array_cat); $p++) {
					/*	$sql_carico = "SELECT registro_agea.*, categorie.descrizione_categoria FROM registro_agea 
										INNER JOIN categorie ON registro_agea.id_tipologia = categorie.id_categoria
										WHERE carico = 't' AND data = '".$val_date['data']."'
										AND id_tipologia = '".$array_cat[$p]."'
										ORDER BY categorie.descrizione_categoria ASC"; */
						$sql_carico = "SELECT registro_agea.*, barcodes.descrizione FROM registro_agea 
										INNER JOIN barcodes ON registro_agea.id_tipologia = barcodes.barcode
										WHERE carico = 't' AND data = '".$val_date['data']."'
										AND id_tipologia = '".$array_cat[$p]."'
										ORDER BY barcodes.descrizione ASC";
						$ris_carico = $this->registry->db->query($sql_carico);

						if($ris_carico->rowCount() == 0) {
							print("<td>&nbsp;</td><td>&nbsp;<td></td>&nbsp;</td><td>&nbsp;</td>");
							continue;	
						}
							
						$val_carico = $ris_carico->fetch();
						print("<td>".$val_carico['um_1']."</td><td>".$val_carico['quantita']."</td>
                                    <td>&nbsp;</td><td>".$val_carico['giacenza']."</td>");	
					}

					//Stampo le righe per le categorie vuote
                    for($w=0; $w<4 - $cnt; $w++) {
                        print("<td>&nbsp;</td><td>&nbsp;<td></td>&nbsp;</td><td>&nbsp;</td>");
                    }

                    print("<td>&nbsp;</td>");
                    print('</tr>');

					$count_righe++;
                    if($count_righe % 26 == 0) {
                        $this->caricoscarico_doc_footer();
		                /* Page break */
                        print('<p style="page-break-after:always;"></p>');

                        $pagina++;
                        $dd = $this->caricoscarico_doc_header($data_start, $data_fine, $i, $pagina, $num_struttura);
                    }
				}

                $and_query = "";
                $length = sizeof($array_cat);
                for($p=0; $p<$length -1; $p++) {
                    $and_query .= "id_tipologia = '".$array_cat[$p]."' OR ";
                }
                $length--;
                $and_query .= "id_tipologia = '".$array_cat[$length]."' ";

				$check_scarico = $this->registry->db->query("SELECT EXISTS(SELECT 1 FROM registro_agea WHERE carico = 'f' AND data = '".$val_date['data']."' AND (".$and_query."))");
				$row = $check_scarico->fetch();

				if($row['exists']) {
				    print('<tr>');
                    print("<td>".date("d/m/Y", strtotime($val_date['data']))."</td>");
					$query_ddt = $this->registry->db->query("SELECT ddt FROM registro_agea WHERE carico = 'f' AND data = '".$val_date['data']."'");
                    $val_ddt = $query_ddt->fetch();
                    print('<td>'.$val_ddt['ddt'].'</td>');
				
					for($p=0; $p<sizeof($array_cat); $p++) {
					/*	$sql_scarico = "SELECT registro_agea.*, categorie.descrizione_categoria FROM registro_agea 
										INNER JOIN categorie ON registro_agea.id_tipologia = categorie.id_categoria
										WHERE carico = 'f' AND data = '".$val_date['data']."'
										AND id_tipologia = '".$array_cat[$p]."'
										ORDER BY categorie.descrizione_categoria ASC"; */
						$sql_scarico = "SELECT registro_agea.*, barcodes.descrizione FROM registro_agea 
										INNER JOIN barcodes ON registro_agea.id_tipologia = barcodes.barcode
										WHERE carico = 'f' AND data = '".$val_date['data']."'
										AND id_tipologia = '".$array_cat[$p]."'
										ORDER BY barcodes.descrizione ASC";
						$ris_scarico = $this->registry->db->query($sql_scarico);

						if(($ris_scarico->rowCount()) == 0) {
                            print("<td>&nbsp;</td><td>&nbsp;<td></td>&nbsp;</td><td>&nbsp;</td>");
                            continue;
                        }

						$val_scarico = $ris_scarico->fetch();
						print("<td>".$val_scarico['um_1']."</td><td>&nbsp;</td><td>".$val_scarico['quantita']."</td>
                                    <td>".$val_scarico['giacenza']."</td>");
					}

					$sql_indigenti = "SELECT SUM(indigenti) AS numindigenti FROM registro_agea_indigenti WHERE data = '".$val_date['data']."'";
					$ris_indigenti = $this->registry->db->query($sql_indigenti);
					$val_indigenti = $ris_indigenti->fetch();
				
					//Stampo le righe per le categorie vuote
                    for($w=0; $w<4 - $cnt; $w++) {
                        print("<td>&nbsp;</td><td>&nbsp;<td></td>&nbsp;</td><td>&nbsp;</td>");
                    }

					print("<td>".$val_indigenti['numindigenti']." indigenti</td>");
					print('</tr>');

					$count_righe++;
					if($count_righe % 26 == 0) {
						$this->caricoscarico_doc_footer();	
		                /* Page break */
                        print('<p style="page-break-after:always;"></p>');

						$pagina++;
						$dd = $this->caricoscarico_doc_header($data_start, $data_fine, $i, $pagina, $num_struttura);
					}
				}

			}

			for($m=($count_righe % 26); $m<26; $m++) {
                print('<tr>');
                for($n=0; $n<19; $n++) {
                    print('<td>&nbsp;</td>');
                }
                print('</tr>');
			}

			$this->caricoscarico_doc_footer();
		    /* Page break */
            print('<p style="page-break-after:always;"></p>');

            $pagina++;
		} 
        
        $sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                    VALUES('".date("Y-m-d H:i:s")."', 
                            '[AGEA]', 
                            'Stampa registro carico/scarico da ".date("d/m/Y", strtotime($data_start))." a ".date("d/m/Y", strtotime($data_fine))."', 
                            '".$_SESSION['username']."')";
        $count_log = $this->registry->db->exec($sql_log);
	}

	public function cerca() {
		$this->registry->template->cerca = 1;

	 /* $sql_carico = "SELECT registro_agea.*, categorie.descrizione_categoria FROM registro_agea 
				INNER JOIN categorie ON registro_agea.id_tipologia = categorie.id_categoria
				WHERE 1=1 ";
		
		$sql_scarico = "SELECT registro_agea.*, categorie.descrizione_categoria FROM registro_agea 
				INNER JOIN categorie ON registro_agea.id_tipologia = categorie.id_categoria
				WHERE 1=1 "; */

        $sql_carico = "SELECT registro_agea.*, barcodes.descrizione FROM registro_agea 
                INNER JOIN barcodes ON registro_agea.id_tipologia = barcodes.barcode
                WHERE 1=1 ";
        
        $sql_scarico = "SELECT registro_agea.*, barcodes.descrizione FROM registro_agea 
                INNER JOIN barcodes ON registro_agea.id_tipologia = barcodes.barcode
                WHERE 1=1 ";

		if(!empty($_REQUEST['fake-form-categoria'])) {
			$sql_carico .= "AND id_tipologia = '".$_REQUEST['fake-form-categoria']."' ";
			$sql_scarico .= "AND id_tipologia = '".$_REQUEST['fake-form-categoria']."' ";
		}

		if(!empty($_REQUEST['fake-form-datestart'])) {
            $_REQUEST['fake-form-datestart'] = str_replace('/', '-', $_REQUEST['fake-form-datestart']);
			$data_start = date("Y-m-d", strtotime($_REQUEST['fake-form-datestart']));			
		}
		else {
			$data_start = date("Y-m-d", strtotime(date("Y-m-d")."- 10 days"));
		}
		
		if(!empty($_REQUEST['fake-form-dateend'])) {
            $_REQUEST['fake-form-dateend'] = str_replace('/', '-', $_REQUEST['fake-form-dateend']);
			$data_fine = date("Y-m-d", strtotime($_REQUEST['fake-form-dateend']));			
		}
		else {
			$data_fine = date("Y-m-d");
		}

		$sql_carico .= "AND data BETWEEN '".$data_start."' AND '".$data_fine."' ";
		$sql_scarico .= "AND data BETWEEN '".$data_start."' AND '".$data_fine."' ";

		if(isset($_REQUEST['fake-form-carico']) && !empty($_REQUEST['fake-form-carico'])) {
			$sql_carico .= "AND carico = 't' ";
		}
		if(isset($_REQUEST['fake-form-scarico']) && !empty($_REQUEST['fake-form-scarico'])) {
			$sql_scarico .= "AND carico = 'f' ";
		}

	/*	$sql_carico .= "ORDER BY data DESC, categorie.descrizione_categoria ASC";
		$sql_scarico .= "ORDER BY data DESC, categorie.descrizione_categoria ASC"; */
        $sql_carico .= "ORDER BY data DESC, barcodes.descrizione ASC";
        $sql_scarico .= "ORDER BY data DESC, barcodes.descrizione ASC";
	
		if(isset($_REQUEST['fake-form-carico']) && !empty($_REQUEST['fake-form-carico'])) {
            $this->registry->template->ris_carico = $this->registry->db->query($sql_carico);
        }
        if(isset($_REQUEST['fake-form-scarico']) && !empty($_REQUEST['fake-form-scarico'])) {
            $this->registry->template->ris_scarico = $this->registry->db->query($sql_scarico);
        }

		$this->registry->template->datastart = $data_start;	
		$this->registry->template->datafine = $data_fine;	
		
	/*	$this->registry->template->ris = $this->registry->db->query("SELECT id_categoria, descrizione_categoria FROM categorie ORDER BY descrizione_categoria ASC"); */
		$this->registry->template->ris = $this->registry->db->query("SELECT barcode, descrizione FROM barcodes ORDER BY descrizione ASC");
		$this->registry->template->show('agearegistro');	
	}
}

?>

