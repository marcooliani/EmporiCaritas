<?php
session_start();

class accettazioneController extends BaseController {
	public function index() {
		$this->registry->template->show('accettazione');
	}

	public function verifica($barcode = '') {
		$sql = "SELECT famiglie.punti_residui, famiglie.sospeso, famiglie.scadenza FROM famiglie 
                LEFT JOIN componenti_famiglie ON famiglie.codice_fiscale = componenti_famiglie.capofamiglia
                WHERE famiglie.codice_fiscale = '".$_REQUEST['id_tessera']."'
                OR componenti_famiglie.c_codice_fiscale = '".$_REQUEST['id_tessera']."'";
		$ris = $this->registry->db->query($sql);

		if($ris->rowCount() > 0) {
			$val = $ris->fetch();

			if($val['sospeso']) {
				$this->registry->template->sospeso = "1";
			}

            else if($val['scadenza'] < date("Y-m-d")) {
                $this->registry->template->accettato = "scaduto";
                $this->registry->template->scadenza = date("d/m/Y", strtotime($val['scadenza']));
            }

			else if($val['punti_residui'] <= 0 && $val['punti_residui'] != -1 ) {
				$this->registry->template->accettato = "no";
				$this->registry->template->punti_residui = $val['punti_residui'];
			}

			else if($val['punti_residui'] > 0 && $val['punti_residui'] <= 20 )  {
				$this->registry->template->accettato = "riserva";
				$this->registry->template->punti_residui = $val['punti_residui'];
			}

			else {
                $this->cerca_bimbi($_REQUEST['id_tessera']);
				$this->registry->template->accettato = "ok";
				$this->registry->template->punti_residui = $val['punti_residui'];
			}
		}

		else {
			$this->registry->template->no_tessera = "1";
		}

		$this->registry->template->show('accettazione');
	}

    private function cerca_bimbi($barcode = '') {
        $sql = "SELECT nome, data_nascita, sesso, ruolo FROM componenti_famiglie WHERE capofamiglia = '".$barcode."'";
        $ris = $this->registry->db->query($sql);

        if($ris->rowCount() > 0) {
            $num_bimbi = 0;
            $bimbi = array();
            foreach($ris as $k=>$val) {
                $data_nascita = new DateTime($val['data_nascita']);
                $oggi = new DateTime(date('Y-m-d'));
                $diff = $data_nascita->diff($oggi);
                $eta = $diff->format('%y');

                if($eta >= 0 && $eta < 18) {
                    $row_array['nome'] = $val['nome'];
                    $row_array['sesso'] = $val['sesso'];
                    $row_array['ruolo'] = $val['ruolo'];
                    $row_array['eta'] = $eta;

                    array_push($bimbi, $row_array);
                    $num_bimbi++;
                }
            }

            if(!empty($bimbi)) {
                $this->registry->template->bimbi = 1;
                $this->registry->template->num_bimbi = $num_bimbi;
                $this->registry->template->bimbi_array = $bimbi;
            }
            else {
                $this->registry->template->bimbi = 0;
            }
        }
    }

	public function ajax_cercaFamiglia() {
        $sql = "SELECT codice_fiscale, cognome, nome FROM famiglie 
                WHERE cognome LIKE '".strtolower($_REQUEST['term'])."%'";
        $sql_m = "SELECT c_codice_fiscale AS codice_fiscale, cognome, nome FROM componenti_famiglie 
                WHERE cognome LIKE '".strtolower($_REQUEST['term'])."%'";

        $res = $this->registry->db->query($sql);
        $res_m = $this->registry->db->query($sql_m);

        $results = array();

        foreach($res as $k=>$row) {
            $row_array['codice_fiscale'] = $row['codice_fiscale'];
            $row_array['nome'] = ucwords($row['cognome'])." ".ucwords($row['nome']);

            array_push($results, $row_array);
        }

        foreach($res_m as $k=>$row) {
            $row_array['codice_fiscale'] = $row['codice_fiscale'];
            $row_array['nome'] = ucwords($row['cognome'])." ".ucwords($row['nome']);

            array_push($results, $row_array);
        }

        echo json_encode($results);
    }
}

?>
