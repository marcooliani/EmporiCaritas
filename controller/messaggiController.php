<?php
session_start();

class messaggiController extends BaseController {

    public function index() {
    }

    public function check() {
        $config = Config::getInstance();
        $myself = $config->config_values['emporio']['idemporio'];

        $sql_lastdate = "SELECT MAX(data) AS maxdata, id_conversazione FROM messaggi GROUP BY id_conversazione ORDER BY MAX(data) DESC";
        $ris_lastdate = $this->registry->db->query($sql_lastdate);

        $nuovi_avvisi = 0;
        $msg = "";
        $chars = 55;

        foreach($ris_lastdate as $key=>$val_lastdate) {
            $sql = "SELECT messaggi.*, lista_empori.nome_emporio FROM messaggi 
                            INNER JOIN lista_empori ON lista_empori.id_emporio::VARCHAR = messaggi.peer
                            WHERE data = '".$val_lastdate['maxdata']."' 
                            AND id_conversazione = '".$val_lastdate['id_conversazione']."'";
            $ris = $this->registry->db->query($sql); 
        

/*        $week = date("Y-m-d H:i", strtotime("-1 week"));
        $sql = "SELECT messaggi.*, lista_empori.nome_emporio 
                FROM messaggi 
                INNER JOIN lista_empori ON messaggi.sender = lista_empori.id_emporio::VARCHAR
                WHERE messaggi.data >= '".$week."' 
                GROUP BY messaggi.id_conversazione, lista_empori.nome_emporio, messaggi.id_messaggio
                ORDER BY messaggi.data DESC";
        $ris = $this->registry->db->query($sql); */

            foreach($ris as $key=>$val) {
                if($val['sender'] == $myself) {
                    $val['sender'] = $val['receiver'];
                }

                if(!$val['letto']) {
                    $nuovi_avvisi++;
                    $background = "style='background:#eeeeee; cursor:pointer;'";
                }
                else {
                    $background = "style='cursor:pointer'";
                }

                if($val['tipo'] == "chat") {
                    $background_thumb = "style='background:#5bc0de; border-color: #46b8da; cursor:pointer;'";
                    $fa_icon = "<i class='fa fa-comments fa-inverse'></i>";
                }
                else if($val['tipo'] == "transf_req") {
                    $background_thumb = "style='background:#f0ad4e; border-color: #eea236; cursor:pointer;'";
                    $fa_icon = "<i class='fa fa-truck fa-inverse'></i>";
                }

                if(strlen($val['msg']) > $chars) {
                    $new = wordwrap($val['msg'], $chars, "|");
                    $result=explode("|",$new);
                    $val['msg'] = $result[0]." ...";
                }

                // Formatta la singola notifica
                $msg .= "<div class='messaggio_chat' ".$background." id_conv='".$val['id_conversazione']."' id_sender='".$val['sender']."' name_sender='".ucwords($val['nome_emporio'])."'>
                        <div style:'position:relative; float:left; height:100%'>
                            <span class='msg_chat_thumb' ".$background_thumb.">".$fa_icon." </span>
                        </div>
                        <div style:'position:relative; float:right; height:100%'>
                            <span class='msg_chat_sender'><strong>".ucwords($val['nome_emporio'])."</strong></span><br>
                            <span class='msg_chat_testo'>".$val['msg']."</span><br>
                            <span class='msg_chat_data'><i class='fa fa-calendar'></i> ".date("d/m/Y H:i", strtotime($val['data']))."</span>
                        </div>
                    </div>";
            }
        }

        echo json_encode(array(
            "responseText1" => $msg,
            "responseText2" => $nuovi_avvisi
        ));
    }

    /**
     * visualizza()
     * @param sender varchar
     *
     * Visualizza i messaggi tra empori nel boxino della chat
     */
    public function visualizza($id_conv = '') {
        $config = Config::getInstance();
        $myself = $config->config_values['emporio']['idemporio'];

        $sql = "SELECT * FROM messaggi WHERE id_conversazione = '".$id_conv."' ORDER BY data ASC";
        $ris = $this->registry->db->query($sql);

        $msg = '';

        foreach($ris as $key=>$val) {
            if($val['sender'] == $myself) {
                $msg .= "<div class='msga'>".$val['msg']."</div><div style='clear: both; height: 10px;'>&nbsp;</div>";
            }
            else {
                $msg .= "<div class='msgda'>".$val['msg']."</div><div style='clear: both; height: 10px;'>&nbsp;</div>";
            }
        }

        echo json_encode($msg);
    }

    public function scrivi() {
        // Recupero l'id dell'emporio locale. Serve a indicare il mittente dei messaggi inviati
        $config = Config::getInstance();
        $myself = $config->config_values['emporio']['idemporio'];

        // Recupero i dati di connessione del database dell'emporio destinatario
        $sql_remoto = "SELECT * FROM lista_empori WHERE id_emporio = '".$_REQUEST['recv']."'";
        $ris = $this->registry->db->query($sql_remoto);

        $val = $ris->fetch();

        try {
            // Mi connetto al db dell'emporio destinatario per recapitare il messaggio
            $conn_loc = new PDO("pgsql:host=".$val['dbhost'].";port=".$val['dbport'].";dbname=".$val['dbname']."", $val['dbuser'], $val['dbpass']);
            $conn_loc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql_notifica = "INSERT INTO messaggi 
                            VALUES('".$_REQUEST['conv']."', 
                                '".$_REQUEST['recv']."',
                                nextval('messaggi_id_messaggio_seq'), 
                                'chat', 
                                '".$myself."', 
                                '".$_REQUEST['recv']."', 
                                '".pg_escape_string(htmlentities($_REQUEST['msg']))."',
                                '".date("Y-m-d H:i:s")."',
                                'f')";
            $count = $conn_loc->exec($sql_notifica);

            if($count <= 0) {
                $msg = "<div style='text-align:center'><font color='#f00'>
                        <i class='fa fa-exclamation-triangle'></i> Messaggio non inviato!</font></div>
                        <div style='clear: both; height: 10px;'>&nbsp;</div>";
            }
        }

        catch(PDOException $e) {
            $msg = "<div style='text-align:center'><font color='#f00'>
                        <i class='fa fa-exclamation-triangle'></i> Messaggio non inviato!</font></div>
                        <div style='clear: both; height: 10px;'>&nbsp;</div>";
            echo json_encode($msg);
            return;
        //    echo "Error : " . $e->getMessage() . "<br/>";
        //  die();
        }

        // Inserisci il messaggio nel db locale (altrimenti non ho traccia dei messaggi che mando!)
        $sql_local = "INSERT INTO messaggi 
                        VALUES('".$_REQUEST['conv']."', 
                            '".$_REQUEST['recv']."',
                            nextval('messaggi_id_messaggio_seq'), 
                            'chat', 
                            '".$myself."', 
                            '".$_REQUEST['recv']."', 
                            '".pg_escape_string(htmlentities($_REQUEST['msg']))."',
                            '".date("Y-m-d H:i:s")."',
                            't')";
        $count = $this->registry->db->exec($sql_local);

        if($count > 0) {
            $msg = "<div class='msga'>".htmlentities($_REQUEST['msg'])."</div><div style='clear: both; height: 10px;'>&nbsp;</div>";
        }
        else {
            $msg = "<div style='text-align:center'><font color='#f00'>
                    <i class='fa fa-exclamation-triangle'></i> Messaggio non inviato!</font></div>
                    <div style='clear: both; height: 10px;'>&nbsp;</div>";
        }

        echo json_encode($msg);
    }

    public function update_chat($id_conv = '') {
        // Recupero l'id dell'emporio locale. Serve a indicare il mittente dei messaggi inviati
        $config = Config::getInstance();
        $myself = $config->config_values['emporio']['idemporio'];

        $sql = "SELECT * FROM messaggi 
                WHERE id_conversazione = '".$id_conv."' 
                    AND letto = 'f' 
                    AND receiver = '".$myself."'
                ORDER BY data ASC";
        $ris = $this->registry->db->query($sql);

        if($ris->rowCount() > 0) {
            $this->letto($id_conv);

            $msg = "";

            foreach($ris as $key=>$val) {
                $msg .= "<div class='msgda'>".$val['msg']."</div><div style='clear: both; height: 10px;'>&nbsp;</div>";
            }

            echo json_encode($msg);
        }
    }
    
    /**
     * letto()
     * @param $id_conv 
     *
     * Segna tutti i messaggi della conversazione come già letti
     */
    public function letto($id_conv = '') {
        $sql = "UPDATE messaggi SET letto = 't' WHERE id_conversazione = '".$id_conv."'";
        $count = $this->registry->db->exec($sql);
    }

    /**
     * cancella_messaggi()
     *
     * Contrassegna i messaggi come gia` letti all'apertura del contenitore delle notifiche
     */
    public function cancella_messaggi() {
        $sql = "UPDATE messaggi SET letto = 't'";
        $count = $this->registry->db->exec($sql);
    }

    public function loadsidebar() {
        $sql = "SELECT id_emporio, nome_emporio FROM lista_empori ORDER BY nome_emporio ASC";
        $ris = $this->registry->db->query($sql);

        $user = '';
        foreach($ris as $key=>$val) {
            $sql_dati = "SELECT messaggi.id_conversazione, lista_empori.nome_emporio 
                        FROM messaggi 
                        INNER JOIN lista_empori ON messaggi.peer = lista_empori.id_emporio::VARCHAR 
                        WHERE peer = '".$val['id_emporio']."'";
            $ris_dati = $this->registry->db->query($sql_dati);

            if($ris_dati->rowCount() <= 0) {
                $sql_nome = "SELECT nome_emporio FROM lista_empori WHERE id_emporio = '".$val['id_emporio']."'";
                $ris_nome = $this->registry->db->query($sql_nome);

                $val_nome = $ris_nome->fetch();

                $nome = ucwords($val_nome['nome_emporio']);
                $idconv = date("ymdHis").$val['id_emporio'];
            }
            else {
                $val_dati = $ris_dati->fetch();
                $nome = ucwords( $val_dati['nome_emporio']);
                $idconv = $val_dati['id_conversazione'];   
            }

            $user .= "<div class='sidebar-singleuser' nome_emporio='".$nome."' id_conv='".$idconv."' peer='".$val['id_emporio']."'>
                        <div class='sidebar-singleuser-thumb'><i class='fa fa-user'></i></div>
                        <div class='sidebar-singleuser-name'> ".ucwords($val['nome_emporio'])."</div></div>";
        }

        echo json_encode($user);
    }
}

?>
