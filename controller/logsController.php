<?php
session_start();

class logsController extends BaseController {
    
    public function index() {
        $sql = "SELECT * FROM logs WHERE log_data >= '".date("Y-m-d 00:00:00", strtotime(date("Y-m-d 23:59:59")."- 7 days"))."' ORDER BY log_data DESC";
        $this->registry->template->ris = $this->registry->db->query($sql);

        $sql_tipo = "SELECT DISTINCT flag FROM logs ORDER BY flag ASC";
        $this->registry->template->ris_tipo = $this->registry->db->query($sql_tipo);

        $sql_login = "SELECT DISTINCT login FROM users ORDER BY login ASC";
        $this->registry->template->ris_login = $this->registry->db->query($sql_login);

        $this->registry->template->show('logs');
    }

    public function cerca() {
        $this->registry->template->cerca = 1;

        $sql_tipo = "SELECT DISTINCT flag FROM logs ORDER BY flag ASC";
        $this->registry->template->ris_tipo = $this->registry->db->query($sql_tipo);

        $sql_login = "SELECT DISTINCT login FROM users ORDER BY login ASC";
        $this->registry->template->ris_login = $this->registry->db->query($sql_login);

        $sql = "SELECT * FROM logs WHERE 1=1 ";
        
        if(!empty($_REQUEST['date-start'])) {
            $_REQUEST['date-start'] = str_replace('/', '-', $_REQUEST['date-start']);
            $sql .= "AND log_data >= '".date("Y-m-d 00:00:00", strtotime($_REQUEST['date-start']))."' ";
        }
        else {
            $sql .= "AND log_data >= '".date("Y-m-d 00:00:00", strtotime(date("Y-m-d 23:59:59")."- 7 days"))."' ";
        }

        if(!empty($_REQUEST['date-end'])) {
            $_REQUEST['date-end'] = str_replace('/', '-', $_REQUEST['date-end']);
            $sql .= "AND log_data <= '".date("Y-m-d 23:59:59", strtotime($_REQUEST['date-end']))."' ";
        }

        if(!empty($_REQUEST['tipo'])) {
            $sql .= "AND ";
            $length = sizeof($_REQUEST['tipo']);

            for($i=0; $i<$length - 1; $i++) {
                $sql .= "flag = '".$_REQUEST['tipo'][$i]."' OR ";
            }
   
            $length--; 
            $sql .= "flag = '".$_REQUEST['tipo'][$length]."' ";
        }

        if(!empty($_REQUEST['utente'])) {
            $sql .= "AND login = '".$_REQUEST['utente']."' ";
        }

        if(!empty($_REQUEST['descrizione'])) {
            $sql .= "AND LOWER(log_descrizione) LIKE '%".strtolower(pg_escape_string($_REQUEST['descrizione']))."%' ";
        }

        $sql .= "ORDER BY log_data DESC";
        $this->registry->template->ris = $this->registry->db->query($sql);

        $this->registry->template->show('logs');
    }
}

?>
