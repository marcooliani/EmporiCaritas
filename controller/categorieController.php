<?php
session_start();

class categorieController extends BaseController {

    public function index() {
        $this->registry->template->ris = $this->registry->db->query("SELECT * FROM categorie ORDER BY descrizione_categoria ASC");

        $this->registry->template->show('categorie');
    }

    public function cerca_categorie() {
        $sql = "SELECT * FROM categorie 
                WHERE LOWER(descrizione_categoria) LIKE '%".strtolower(pg_escape_string($_REQUEST['cerca_categoria']))."%'
                ORDER BY descrizione_categoria ASC";
        $this->registry->template->ris = $this->registry->db->query($sql);

        $this->registry->template->show('categorie');
    }

    public function nuovo() {
        $this->registry->template->ris =  $this->registry->db->query("SELECT DISTINCT tipo FROM um ORDER BY tipo ASC");
        $this->registry->template->show('nuovacategoria');
    }

    public function inserisci() {
        $sql = "INSERT INTO categorie 
                VALUES( 'cat".date("ymdHis")."',
                        '".strtolower(pg_escape_string($_REQUEST['descrizione_categoria']))."', 
                        '".$_REQUEST['limite_spesa_max']."', 
                        '".$_REQUEST['limite_mese_max']."',
                        '".$_REQUEST['tipo_um']."')";
        $count = $this->registry->db->exec($sql);

        if($count > 0) {
            $this->registry->template->insertok = 1;

			$sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                        VALUES('".date("Y-m-d H:i:s")."', 
                            '[PRODOTTI]', 
                            'Inserita nuova categoria: ".ucwords(pg_escape_string($_REQUEST['descrizione_categoria']))."', 
                            '".$_SESSION['username']."')";
            $count = $this->registry->db->exec($sql_log);

            $sql_listaempori = "SELECT * FROM lista_empori";
            $ris_empori = $this->registry->db->query($sql_listaempori);

            foreach($ris_empori as $key=>$val) {
                try {
                    $conn_loc = new PDO("pgsql:host=".$val['dbhost'].";port=".$val['dbport'].";dbname=".$val['dbname']."", $val['dbuser'], $val['dbpass']);
                    $conn_loc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $msg = "<font color='#f00'><strong>[ADMIN]</strong></font> - Nuova <strong>categoria</strong> inserita: <strong>".ucwords(pg_escape_string($_REQUEST['descrizione_categoria']))."</strong>";
                    $sql_notifica = "INSERT INTO notifiche(tipo, msg, data, letto) VALUES('categorie', '".ucwords(pg_escape_string($msg))."', '".date("Y-m-d H:i:s")."', false)";
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

    public function modifica($categoria = '') {
        $this->registry->template->categoria = $categoria;
        $this->registry->template->ris = $this->registry->db->query("SELECT * FROM categorie WHERE id_categoria = '".$categoria."'");
        $this->registry->template->ris_um = $this->registry->db->query("SELECT DISTINCT tipo FROM um ORDER BY tipo ASC");

        $this->registry->template->show('modificacategoria');
    }

    public function update_categoria($categoria = '') {
        $sql_nome = $this->registry->db->query("SELECT * FROM categorie WHERE id_categoria = '".$categoria."'");
        $val = $sql_nome->fetch();
   
        $sql = "UPDATE categorie SET(descrizione_categoria, limite_spesa_max, limite_mese_max, tipo_um) =
                ('".strtolower(pg_escape_string($_REQUEST['descrizione_categoria']))."', 
                 '".$_REQUEST['limite_spesa_max']."', 
                 '".$_REQUEST['limite_mese_max']."',
                 '".$_REQUEST['tipo_um']."') 
                WHERE id_categoria = '".$categoria."'";
        $count = $this->registry->db->exec($sql);

        if($count > 0) {
            $this->registry->template->modifyok = 1;

			$sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                        VALUES('".date("Y-m-d H:i:s")."', 
                            '[PRODOTTI]', 
                            'Modificata categoria: ".ucwords(pg_escape_string($val['descrizione_categoria']))."', 
                            '".$_SESSION['username']."')";
            $count = $this->registry->db->exec($sql_log);

            $sql_listaempori = "SELECT * FROM lista_empori";
            $ris_empori = $this->registry->db->query($sql_listaempori);

            foreach($ris_empori as $key=>$val2) {
                try {
                    $conn_loc = new PDO("pgsql:host=".$val2['dbhost'].";port=".$val2['dbport'].";dbname=".$val2['dbname']."", $val2['dbuser'], $val2['dbpass']);
                    $conn_loc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    
                    if($val['descrizione_categoria'] != strtolower(pg_escape_string($_REQUEST['descrizione_categoria']))) {
                        $msg = "<font color='#f00'><strong>[ADMIN]</strong></font> - La categoria <strong>".ucwords(pg_escape_string($val['descrizione_categoria']))."</strong>
                                &egrave; stata rinominata in <strong>".ucwords(pg_escape_string($_REQUEST['descrizione_categoria']))."</strong>";
                        $sql_notifica = "INSERT INTO notifiche(tipo, msg, data, letto) VALUES('categorie', '".pg_escape_string($msg)."', '".date("Y-m-d H:i:s")."', false)";
                        $count = $conn_loc->exec($sql_notifica);
                    }
                   
                    if($val['limite_spesa_max'] != pg_escape_string($_REQUEST['limite_spesa_max'])) {
                        $msg = "<font color='#f00'><strong>[ADMIN]</strong></font> - Il limite di singola spesa per la categoria 
                                <strong>".ucwords(pg_escape_string($val['descrizione_categoria']))."</strong> &egrave; passato da
                                <strong>".pg_escape_string($val['limite_spesa_max'])."</strong> a
                                <strong>".pg_escape_string($_REQUEST['limite_spesa_max'])."</strong>";
                        $sql_notifica = "INSERT INTO notifiche(tipo, msg, data, letto) VALUES('categorie', '".pg_escape_string($msg)."', '".date("Y-m-d H:i:s")."', false)";
                        $count = $conn_loc->exec($sql_notifica);
                    }

                    if($val['limite_mese_max'] != pg_escape_string($_REQUEST['limite_mese_max'])) {
                        $msg = "<font color='#f00'><strong>[ADMIN]</strong></font> - Il limite di spesa mensile per la categoria 
                                <strong>".ucwords(pg_escape_string($val['descrizione_categoria']))."</strong> &egrave; passato da
                                <strong>".pg_escape_string($val['limite_mese_max'])."</strong> a
                                <strong>".pg_escape_string($_REQUEST['limite_mese_max'])."</strong>";
                        $sql_notifica = "INSERT INTO notifiche(tipo, msg, data, letto) VALUES('categorie', '".pg_escape_string($msg)."', '".date("Y-m-d H:i:s")."', false)";
                        $count = $conn_loc->exec($sql_notifica);
                    }

                    if($val['tipo_um'] != pg_escape_string($_REQUEST['tipo_um'])) {
                        $msg = "<font color='#f00'><strong>[ADMIN]</strong></font> - L'unit&agrave; di misura per la categoria 
                                <strong>".ucwords(pg_escape_string($val['descrizione_categoria']))."</strong> &egrave; passata da
                                <strong>".pg_escape_string($val['tipo_um'])."</strong> a
                                <strong>".pg_escape_string($_REQUEST['tipo_um'])."</strong>";
                        $sql_notifica = "INSERT INTO notifiche(tipo, msg, data, letto) VALUES('categorie', '".pg_escape_string($msg)."', '".date("Y-m-d H:i:s")."', false)";
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
        
        $this->modifica($categoria);
    }

    public function rimuovi() {
        $sql_nome = $this->registry->db->query("SELECT descrizione_categoria FROM categorie WHERE id_categoria = '".$_REQUEST['fake-form-categoria']."'");
        $val = $sql_nome->fetch();

        $sql = "DELETE FROM categorie WHERE id_categoria = '".$_REQUEST['fake-form-categoria']."'";
        $count = $this->registry->db->exec($sql);

		$sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                    VALUES('".date("Y-m-d H:i:s")."', 
                            '[PRODOTTI]', 
                            'Rimossa categoria: ".ucwords(pg_escape_string($val['descrizione_categoria']))."', 
                            '".$_SESSION['username']."')";
        $count = $this->registry->db->exec($sql_log);

        $sql_listaempori = "SELECT * FROM lista_empori";
        $ris_empori = $this->registry->db->query($sql_listaempori);

        foreach($ris_empori as $key=>$val2) {
            try {
                $conn_loc = new PDO("pgsql:host=".$val2['dbhost'].";port=".$val2['dbport'].";dbname=".$val2['dbname']."", $val2['dbuser'], $val2['dbpass']);
                $conn_loc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $msg = "<font color='#f00'><strong>[ADMIN]</strong></font> - Categoria RIMOSSA: <strong>".ucwords(pg_escape_string($val['descrizione_categoria']))."</strong><br>
                        Verificare il proprio database prodotti!";
                $sql_notifica = "INSERT INTO notifiche(tipo, msg, data, letto) VALUES('categorie', '".pg_escape_string($msg)."', '".date("Y-m-d H:i:s")."', false)";
                $count = $conn_loc->exec($sql_notifica);
            }

            catch(PDOException $e) {
                echo "Error : " . $e->getMessage() . "<br/>";
            //  die();
            }
        }

        header("Location: /categorie/index");
    }

    public function ajax_misure() {
        $sql = "SELECT val_um FROM um WHERE tipo = (SELECT tipo_um FROM categorie WHERE id_categoria = '".$_REQUEST['id']."')";
        $ris_um = $this->registry->db->query($sql);

        foreach($ris_um as $key=>$val) {
            print("<option value='".$val['val_um']."'>".$val['val_um']."</option>");
        }
    }
    public function ajax_misure_doppione() {
        $sql = "SELECT val_um FROM um WHERE tipo = (SELECT tipo_um FROM categorie WHERE id_categoria = '".$_REQUEST['id']."')";
        $ris_um = $this->registry->db->query($sql);

        foreach($ris_um as $key=>$val) {
            if($val['val_um'] == $_REQUEST['selected'])
                print("<option value='".$val['val_um']."' selected>".$val['val_um']."</option>");
        }
    }

    public function esporta() {
        $sql = "SELECT categorie.descrizione_categoria, SUM(barcodes.stock) AS stock 
                FROM categorie 
                INNER JOIN tipologie ON tipologie.categoria = categorie.id_categoria 
                INNER JOIN barcodes ON barcodes.tipologia = tipologie.id_tipologia 
                GROUP BY categorie.descrizione_categoria ORDER BY descrizione_categoria ASC;";
        $this->registry->template->ris = $this->registry->db->query($sql);

        $this->registry->template->show_noheader('xls_categorie');
    }
}
?>
