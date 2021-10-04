<?php
session_start();

class utilitiesController extends BaseController {
    public function index() {
        // TODO
    }

    public function scalacarbonaro() {
        $sql = "SELECT * FROM tabella_carbonaro ORDER BY famigliari ASC";
        $this->registry->template->ris = $this->registry->db->query($sql);

        $this->registry->template->show('scalacarbonaro');
    }

    public function modificacarbonaro() {
        $sql = "SELECT * FROM tabella_carbonaro ORDER BY famigliari ASC";
        $this->registry->template->ris = $this->registry->db->query($sql);

        $this->registry->template->show('modificacarbonaro');
    }

    public function update_carbonaro() {
        $sql_delete = "DELETE FROM tabella_carbonaro";
        $count = $this->registry->db->query($sql_delete);

        for($i = 0; $i < sizeof($_REQUEST['famigliari']); $i++) {
            $riga = $_REQUEST['famigliari'][$i];

            $sql = "INSERT INTO tabella_carbonaro 
                    VALUES('".$_REQUEST['famigliari'][$i]."', '".$_REQUEST['punti'][$i]."', 
                            '".$_REQUEST['coefficiente'][$i]."', '".$_REQUEST['punti_corretti'][$i]."')";
            $count = $this->registry->db->exec($sql);

            if($count > 0) {
                $sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                            VALUES('".date("Y-m-d H:i:s")."', 
                            '[ADMIN]', 
                            'Modificata scala Carbonaro', 
                            '".$_SESSION['username']."')";
                $count_log = $this->registry->db->exec($sql_log);

                header("Location: /utilities/scalacarbonaro");
            }
        }
    }

    public function emporio() {
        $sql = "SELECT emporio_config.*, 
                        c1.nome_comune AS comune_n, 
                        c1.provincia AS provincia_n, 
                        c2.nome_comune AS comune_a, 
                        c2.provincia AS provincia_a 
                FROM emporio_config 
                INNER JOIN comuni AS c1 ON emporio_config.comune_nascita = c1.cod_istat
                INNER JOIN comuni AS c2 ON emporio_config.comune = c2.cod_istat";
        $this->registry->template->ris = $this->registry->db->query($sql);

        $this->registry->template->show('emporio');
    }

    public function modificaemporio() {
        $sql = "SELECT emporio_config.*, 
                        c1.nome_comune AS comune_n, 
                        c1.provincia AS provincia_n, 
                        c2.nome_comune AS comune_a, 
                        c2.provincia AS provincia_a 
                FROM emporio_config 
                INNER JOIN comuni AS c1 ON emporio_config.comune_nascita = c1.cod_istat
                INNER JOIN comuni AS c2 ON emporio_config.comune = c2.cod_istat";
        $this->registry->template->ris = $this->registry->db->query($sql);

        $this->registry->template->show('modificaemporio');
    }

    public function update_emporio() {
        $cfg = new iniParser("config.ini");
        $cfg->get("emporio");
        $cfg->setValue("emporio","num_struttura", $_REQUEST['num_struttura']);
        $cfg->setValue("emporio","nome_emporio", strtolower(pg_escape_string($_REQUEST['nome_emporio'])));
        $cfg->save("config.ini");

        $sql = "UPDATE emporio_config 
                SET(id_emporio, num_struttura,nome_emporio,cognome_responsabile,nome_responsabile,data_nascita,
                        comune_nascita,nome_associazione, indirizzo_associazione, localita, comune, cap, telefono, email) = 
                            ('".$_REQUEST['id_emporio']."',
                            '".$_REQUEST['num_struttura']."',
                            '".strtolower(pg_escape_string($_REQUEST['nome_emporio']))."',
                            '".strtolower(pg_escape_string($_REQUEST['cognome_responsabile']))."',
                            '".strtolower(pg_escape_string($_REQUEST['nome_responsabile']))."',
                            '".$_REQUEST['data_nascita']."',
                            '".$_REQUEST['comune_nascita']."',
                            '".strtolower(pg_escape_string($_REQUEST['nome_associazione']))."',
                            '".strtolower(pg_escape_string($_REQUEST['indirizzo_associazione']))."',
                            '".strtolower(pg_escape_string($_REQUEST['localita']))."',
                            '".$_REQUEST['comune']."',
                            '".$_REQUEST['cap']."',
                            '".$_REQUEST['telefono']."',
                            '".$_REQUEST['email']."'
                        )";
        $count = $this->registry->db->exec($sql);

        $config = Config::getInstance();
        $tipo_emporio = ucwords($config->config_values['emporio']['tipologia']);

        if($tipo_emporio == "rete") {
            $sql_centralizzato = "UPDATE lista_empori 
                                SET nome_emporio = '".strtolower(pg_escape_string($_REQUEST['nome_emporio']))."'
                                WHERE id_emporio = '".$_REQUEST['id_emporio']."'";

            $count2 = $this->registry->db->exec($sql_centralizzato);
        }
        else { $count2 = 1; }

        if($count > 0 && $count2 > 0) {
            $sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                            VALUES('".date("Y-m-d H:i:s")."', 
                            '[EMPORIO]', 
                            'Modificati dati emporio', 
                            '".$_SESSION['username']."')";
            $count_log = $this->registry->db->exec($sql_log);
        
            header("Location: /utilities/emporio");
        }
    }
}

?>
