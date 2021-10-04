<?php
session_start();

class tipologieController extends BaseController {

    public function index() {
        $sql = "SELECT tipologie.*, categorie.* FROM tipologie 
                INNER JOIN categorie ON tipologie.categoria = categorie.id_categoria 
                ORDER BY descrizione_tipologia ASC";
        $this->registry->template->ris = $this->registry->db->query($sql);

        $this->registry->template->ris_cat = $this->registry->db->query("SELECT * FROM categorie ORDER BY descrizione_categoria ASC");

        $this->registry->template->show('tipologie');
    }

    public function nuovo() {
        $this->registry->template->ris = $this->registry->db->query("SELECT * FROM categorie ORDER BY descrizione_categoria ASC");

        $this->registry->template->show('nuovatipologia');
    }

    /*
     * Carica via AJAX le tipologie di prodotto disponibili in una select all'interno della pagina di inserimento dei nuovi barcode
     */
    public function ajax_tipologie() {
        $sql = $this->registry->db->query("SELECT id_tipologia, descrizione_tipologia FROM tipologie WHERE categoria = '".$_REQUEST['id']."' ORDER BY descrizione_tipologia ASC");

        print("<option value='' disabled selected> -- Seleziona --- </option>");

        foreach($sql as $key=>$val) {
            print("<option value='".$val['id_tipologia']."'>".ucwords($val['descrizione_tipologia'])."</option>");
        }
    }

    public function cerca() {
        if(!empty($_REQUEST['cerca_tipologia'])) {
            $sql = "SELECT tipologie.*, categorie.* FROM tipologie 
                    INNER JOIN categorie ON tipologie.categoria = categorie.id_categoria 
                    WHERE LOWER(tipologie.descrizione_tipologia) LIKE '%".strtolower(pg_escape_string($_REQUEST['cerca_tipologia']))."%'
                    ORDER BY descrizione_tipologia ASC";
        }
        else if(!empty($_REQUEST['cerca_categoria'])) {
            $sql = "SELECT tipologie.*, categorie.* FROM tipologie 
                    INNER JOIN categorie ON tipologie.categoria = categorie.id_categoria 
                    WHERE tipologie.categoria = '".$_REQUEST['cerca_categoria']."'
                    ORDER BY descrizione_tipologia ASC";
        }

        $this->registry->template->ris = $this->registry->db->query($sql);
        $this->registry->template->ris_cat = $this->registry->db->query("SELECT * FROM categorie ORDER BY descrizione_categoria ASC");

        $this->registry->template->show('tipologie');
    }

    public function inserisci() {
        $sql = "INSERT INTO tipologie
                VALUES( 'tip".date("ymdHis")."',
                        '".$_REQUEST['id_categoria']."', 
                        '".strtolower(pg_escape_string($_REQUEST['descrizione_tipologia']))."', 
                        '".$_REQUEST['warning_qta_minima']."', 
                        '".$_REQUEST['danger_qta_minima']."', 
                        '".$_REQUEST['punti']."',
                        '".$_REQUEST['eta_min']."',
                        '".$_REQUEST['eta_max']."')";
        $count = $this->registry->db->exec($sql);

        if($count > 0) {
            $this->registry->template->insertok = 1;

			$sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                    	VALUES('".date("Y-m-d H:i:s")."', 
                        	'[PRODOTTI]', 
                            'Inserita nuova tipologia: ".ucwords(pg_escape_string($_REQUEST['descrizione_tipologia']))."', 
                            '".$_SESSION['username']."')";
        	$count = $this->registry->db->exec($sql_log);

            $sql_listaempori = "SELECT * FROM lista_empori";
            $ris_empori = $this->registry->db->query($sql_listaempori);

            foreach($ris_empori as $key=>$val) {
                try {
                    $conn_loc = new PDO("pgsql:host=".$val['dbhost'].";port=".$val['dbport'].";dbname=".$val['dbname']."", $val['dbuser'], $val['dbpass']);
                    $conn_loc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    
                    $msg = "<font color='#f00'><strong>[ADMIN]</strong></font> - Nuova <strong>categoria</strong> inserita: <strong>".ucwords(pg_escape_string($_REQUEST['descrizione_tipologia']))."</strong>";
                    $sql_notifica = "INSERT INTO notifiche(tipo, msg, data, letto) VALUES('tipologie', '".ucwords(pg_escape_string($msg))."', '".date("Y-m-d H:i:s")."', false)";
                    $count = $conn_loc->exec($sql_notifica);
                }

                catch(PDOException $e) {
                    echo "Error : " . $e->getMessage() . "<br/>";
                //  die();
                }
            }
        }
        else {
            $this->registry->template->insertok = 0;
        }

        $this->nuovo();
    }

    public function modifica($tipologia = '') {
        $this->registry->template->tipologia = $tipologia;

        $this->registry->template->ris = $this->registry->db->query("SELECT * FROM tipologie WHERE id_tipologia = '".$tipologia."'");
        $this->registry->template->ris_cat = $this->registry->db->query("SELECT * FROM categorie ORDER BY descrizione_categoria ASC");

       $this->registry->template->show('modificatipologia');
    }

    public function update_tipologia($tipologia = '') {
        $sql_nome = $this->registry->db->query("SELECT tipologie.*, categorie.descrizione_categoria FROM tipologie 
                                                INNER JOIN categorie ON tipologie.categoria = categorie.id_categoria 
                                                WHERE tipologie.id_tipologia = '".$tipologia."'");
        $val = $sql_nome->fetch();

        $sql_nuovacat = $this->registry->db->query("SELECT descrizione_categoria FROM categorie WHERE id_categoria = '".$_REQUEST['id_categoria']."'");
        $val3 = $sql_nuovacat->fetch();

        $sql = "UPDATE tipologie SET (descrizione_tipologia, categoria, warning_qta_minima, danger_qta_minima, punti, eta_min, eta_max ) = 
                ('".strtolower(pg_escape_string($_REQUEST['descrizione_tipologia']))."', '".$_REQUEST['id_categoria']."', 
                    '".$_REQUEST['warning_qta_minima']."', '".$_REQUEST['danger_qta_minima']."', '".$_REQUEST['punti']."',
                    '".$_REQUEST['eta_min']."', '".$_REQUEST['eta_max']."') 
                WHERE id_tipologia = '".$tipologia."'";
        $count = $this->registry->db->exec($sql);

        if($count > 0) {
            $this->registry->template->modifyok = 1;

			$sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                        VALUES('".date("Y-m-d H:i:s")."', 
                            '[PRODOTTI]', 
                            'Modificata tipologia: ".ucwords(pg_escape_string($_REQUEST['descrizione_tipologia']))."', 
                            '".$_SESSION['username']."')";
            $count = $this->registry->db->exec($sql_log);

            $sql_listaempori = "SELECT * FROM lista_empori";
            $ris_empori = $this->registry->db->query($sql_listaempori);

            foreach($ris_empori as $key=>$val2) {
                try {
                    $conn_loc = new PDO("pgsql:host=".$val2['dbhost'].";port=".$val2['dbport'].";dbname=".$val2['dbname']."", $val2['dbuser'], $val2['dbpass']);
                    $conn_loc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    if($val['categoria'] != strtolower(pg_escape_string($_REQUEST['id_categoria']))) {
                        $msg = "<font color='#f00'><strong>[ADMIN]</strong></font> - La tipologia <strong>".ucwords(pg_escape_string($val['descrizione_tipologia']))."</strong>
                                appartiene ora alla categoria <strong>".ucwords(pg_escape_string($val3['descrizione_categoria']))."</strong>";
                        $sql_notifica = "INSERT INTO notifiche(tipo, msg, data, letto) VALUES('tipologie', '".pg_escape_string($msg)."', '".date("Y-m-d H:i:s")."', false)";
                        $count = $conn_loc->exec($sql_notifica);
                    }

                    if($val['descrizione_tipologia'] != strtolower(pg_escape_string($_REQUEST['descrizione_tipologia']))) {
                        $msg = "<font color='#f00'><strong>[ADMIN]</strong></font> - La tipologia <strong>".ucwords(pg_escape_string($val['descrizione_tipologia']))."</strong>
                                &egrave; stata rinominata in <strong>".ucwords(pg_escape_string($_REQUEST['descrizione_tipologia']))."</strong>";
                        $sql_notifica = "INSERT INTO notifiche(tipo, msg, data, letto) VALUES('tipologie', '".pg_escape_string($msg)."', '".date("Y-m-d H:i:s")."', false)";
                        $count = $conn_loc->exec($sql_notifica);
                    }

                    if($val['punti'] != pg_escape_string($_REQUEST['punti'])) {
                        $msg = "<font color='#f00'><strong>[ADMIN]</strong></font> - I <strong>punti</strong> per 
                                <strong>".ucwords(pg_escape_string($val['descrizione_tipologia']))."</strong>
                                sono stati modificati da <strong>".$val['punti']."</strong> a <strong>".$_REQUEST['punti']."</strong>";
                        $sql_notifica = "INSERT INTO notifiche(tipo, msg, data, letto) VALUES('tipologie', '".pg_escape_string($msg)."', '".date("Y-m-d H:i:s")."', false)";
                        $count = $conn_loc->exec($sql_notifica);
                    }

                    if($val['warning_qta_minima'] != pg_escape_string($_REQUEST['warning_qta_minima'])) {
                        $msg = "<font color='#f00'><strong>[ADMIN]</strong></font> - Il livello di riordino stock per la tipologia
                                <strong>".ucwords(pg_escape_string($val['descrizione_tipologia']))."</strong> &egrave; passato da
                                <strong>".pg_escape_string($val['limite_spesa_max'])."</strong> a
                                <strong>".pg_escape_string($_REQUEST['limite_spesa_max'])."</strong><br>
                                Verificare il proprio database prodotti!";
                        $sql_notifica = "INSERT INTO notifiche(tipo, msg, data, letto) VALUES('tipologie', '".pg_escape_string($msg)."', '".date("Y-m-d H:i:s")."', false)";
                        $count = $conn_loc->exec($sql_notifica);
                    }

                    if($val['danger_qta_minima'] != pg_escape_string($_REQUEST['danger_qta_minima'])) {
                        $msg = "<font color='#f00'><strong>[ADMIN]</strong></font> - Il livello critico di riordino per la tipologia
                                <strong>".ucwords(pg_escape_string($val['descrizione_tipologia']))."</strong> &egrave; passato da
                                <strong>".pg_escape_string($val['limite_mese_max'])."</strong> a
                                <strong>".pg_escape_string($_REQUEST['limite_mese_max'])."</strong><br>
                                Verificare il proprio database prodotti!";
                        $sql_notifica = "INSERT INTO notifiche(tipo, msg, data, letto) VALUES('tipologie', '".pg_escape_string($msg)."', '".date("Y-m-d H:i:s")."', false)";
                        $count = $conn_loc->exec($sql_notifica);
                    }

                    if($val['eta_min'] != pg_escape_string($_REQUEST['eta_min']) || $val['eta_max'] != pg_escape_string($_REQUEST['eta_max'])) {
                        $msg = "<font color='#f00'><strong>[ADMIN]</strong></font> - I <strong>limiti d'et&agrave;</strong> per 
                                <strong>".ucwords(pg_escape_string($val['descrizione_tipologia']))."</strong>
                                sono stati modificati da <strong>".$val['eta_min']."-".$val['eta_max']." anni</strong> a 
                                <strong>".$_REQUEST['eta_min']."-".$_REQUEST['eta_max']." anni</strong>";
                        $sql_notifica = "INSERT INTO notifiche(tipo, msg, data, letto) VALUES('tipologie', '".pg_escape_string($msg)."', '".date("Y-m-d H:i:s")."', false)";
                        $count = $conn_loc->exec($sql_notifica);
                    }
                }

                catch(PDOException $e) {
                    echo "Error : " . $e->getMessage() . "<br/>";
                //  die();
                }
            }
        }
        else {
            $this->registry->template->modifyok = 0;
        }

        $this->modifica($tipologia);
    }

    public function rimuovi() {
        $sql_nome = $this->registry->db->query("SELECT descrizione_tipologia FROM tipologie WHERE id_tipologia = '".$_REQUEST['fake-form-tipologia']."'");
        $val = $sql_nome->fetch();

        $sql = "DELETE FROM tipologie WHERE id_tipologia = '".$_REQUEST['fake-form-tipologia']."'";
        $count = $this->registry->db->exec($sql);

		$sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                    VALUES('".date("Y-m-d H:i:s")."', 
                            '[PRODOTTI]', 
                            'Rimossa tipologia: ".ucwords(pg_escape_string($val['descrizione_tipologia']))."', 
                            '".$_SESSION['username']."')";
        $count = $this->registry->db->exec($sql_log);

        $sql_listaempori = "SELECT * FROM lista_empori";
        $ris_empori = $this->registry->db->query($sql_listaempori);

        foreach($ris_empori as $key=>$val2) {
            try {
                $conn_loc = new PDO("pgsql:host=".$val2['dbhost'].";port=".$val2['dbport'].";dbname=".$val2['dbname']."", $val2['dbuser'], $val2['dbpass']);
                $conn_loc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $msg = "<font color='#f00'><strong>[ADMIN]</strong></font> - Tipologia RIMOSSA: <strong>".ucwords(pg_escape_string($val['descrizione_tipologia']))."</strong><br>
                        Verificare il proprio database prodotti!";
                $sql_notifica = "INSERT INTO notifiche(tipo, msg, data, letto) VALUES('tipologie', '".pg_escape_string($msg)."', '".date("Y-m-d H:i:s")."', false)";
                $count = $conn_loc->exec($sql_notifica);
            }

            catch(PDOException $e) {
                echo "Error : " . $e->getMessage() . "<br/>";
            //  die();
            }
        }

        header("Location: /tipologie/index");
    }

     public function esporta() {
        $sql = "SELECT tipologie.descrizione_tipologia,
                        SUM(barcodes.stock) AS stock,
                        barcodes.um_1 AS um,
                        SUM(barcodes.contenuto_um1*barcodes.stock) AS quantita_stock 
                FROM barcodes
                INNER JOIN tipologie ON barcodes.tipologia = tipologie.id_tipologia
                WHERE barcodes.tipologia = tipologie.id_tipologia 
                GROUP BY barcodes.tipologia,tipologie.descrizione_tipologia,barcodes.um_1
                ORDER BY tipologie.descrizione_tipologia ASC";
        $this->registry->template->ris = $this->registry->db->query($sql);

        $this->registry->template->show_noheader('xls_tipologie');
    }
}

?>

