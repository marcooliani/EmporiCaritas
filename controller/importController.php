<?php
session_start();

class importController extends BaseController {
    
    public function index() {
        $this->registry->template->show('import');
    }

    public function carica() {
        if(!empty($_REQUEST['fileupload'])) {
            foreach($_FILES as $filename=>$bla) {
                if($filename == "prodotti") {
                    if($_FILES[$filename]["error"] == 0) {
                        move_uploaded_file($_FILES[$filename]["tmp_name"],"/tmp/". $_FILES[$filename]["name"]);
                        $this->import_data($_FILES[$filename]["name"], "barcodes");
                    }
                }
                if($filename == "famiglie") {
                    if($_FILES[$filename]["error"] == 0) {
                        move_uploaded_file($_FILES[$filename]["tmp_name"],"/tmp/". $_FILES[$filename]["name"]);
                        $this->import_data($_FILES[$filename]["name"], "famiglie");
                    }
                }
                if($filename == "famigliari") {
                    if($_FILES[$filename]["error"] == 0) {
                        move_uploaded_file($_FILES[$filename]["tmp_name"],"/tmp/". $_FILES[$filename]["name"]);
                        $this->import_data($_FILES[$filename]["name"], "componenti_famiglie");
                    }
                }
                if($filename == "fornitori") {
                    if($_FILES[$filename]["error"] == 0) {
                        move_uploaded_file($_FILES[$filename]["tmp_name"],"/tmp/". $_FILES[$filename]["name"]);
                        $this->import_data($_FILES[$filename]["name"], "fornitori");
                    }
                }
            }

            $this->registry->template->importok = 1;
            $this->registry->template->show('import');
        }
    }

    public function cancella() {
        $sql_s = "DELETE FROM scontrini";
        $sql_b = "DELETE FROM barcodes";
        $sql_c = "DELETE FROM famiglie";
        $sql_fa = "DELETE FROM componenti_famiglie";
        $sql_fo = "DELETE FROM fornitori";

        $this->registry->db->exec($sql_s);
        $this->registry->db->exec($sql_b);
        $this->registry->db->exec($sql_c);
        $this->registry->db->exec($sql_fa);
        $this->registry->db->exec($sql_fo);

        $this->registry->template->cancellato = 1;
        $this->registry->template->show('import');
    }

    private function import_data($file = '', $table = '') {
        // $table: 
        //      * barcodes
        //      * famiglie
        //      * componenti_famiglie
        //      * fornitori
        $fd = fopen("/tmp/".$file, "r");

        if($fd) {
            if($table == "barcodes") {
                $um_da_sostituire = array("gr", "lt");
                $um_corrette = array("g", "l");

                while(($data = fgetcsv($fd, "|", ";")) !== false) {
                    $sql = "INSERT INTO ".$table."
                            VALUES('".$data[0]."', 
                                    '$data[6]', 
                                    '".pg_escape_string($data[1])."', 
                                    '".strtolower(str_replace($um_da_sostituire, $um_corrette, $data[5]))."', '".strtolower(str_replace($um_da_sostituire, $um_corrette, $data[5]))."',
                                    '".$data[4]."', '".$data[4]."', 
                                    'f', 
                                    'f', 
                                    'pz', 
                                    '".$data[3]."')";
                    $this->registry->db->exec($sql);
               }
            }

            else if($table == "famiglie") {
                while(($data = fgetcsv($fd, "|", ";")) !== false) {
                    $sql = "INSERT INTO ".$table."
                            VALUES('".strtoupper($data[0])."',
                                    '".strtolower(pg_escape_string($data[1]))."',
                                    '".strtolower(pg_escape_string($data[2]))."',
                                    'f',
                                    '".$data[3]."',
                                    '',
                                    '00',
                                    '',
                                    '',
                                    '0',
                                    '".strtolower(pg_escape_string($data[4]))."',
                                    '',
                                    NULL,
                                    '000000',
                                    '',
                                    '".$data[5]."',
                                    '',
                                    '1',
                                    '".$data[8]."',
                                    '".$data[9]."',
                                    'f',
                                    '',
                                    '',
                                    '".$data[7]."',
                                    '".$data[6]."',
                                    'f',
                                    NULL,
                                    NULL,
                                    '')";
                    $this->registry->db->exec($sql);
               }
            }

            else if($table == "componenti_famiglie") {
                while(($data = fgetcsv($fd, "|", ";")) !== false) {
                    $sql = "INSERT INTO ".$table."
                            VALUES('".strtoupper($data[0])."',
                                    '".strtoupper($data[4])."',
                                    '".strtolower(pg_escape_string($data[1]))."',
                                    '".strtolower(pg_escape_string($data[2]))."',
                                    '',
                                    '',
                                    '".$data[3]."',
                                    '',
                                    '',
                                    '')";
                    $this->registry->db->exec($sql);
                }
            }

            else if($table == "fornitori") {
                while(($data = fgetcsv($fd, "|", ";")) !== false) {
                    if(empty($data[4])) {
                        $data[4] = '1970-01-01';
                    }
                    $sql = "INSERT INTO ".$table."(ragione_sociale,cognome,nome,referente,indirizzo,localita,cap,comune,telefono,email,fornitore_da,donatore,note)
                            VALUES('".strtolower(pg_escape_string($data[0]))."',
                                    '',
                                    '',
                                    '',
                                    '".strtolower(pg_escape_string($data[2]))."',
                                    '',
                                    '',
                                    '000000',
                                    '".$data[3]."',
                                    '',
                                    '".$data[4]."',
                                    'f',
                                    '".$data[5]."')";
                    $this->registry->db->exec($sql);
                }
            }
        }

        fclose($fd);
    }
}

?>
