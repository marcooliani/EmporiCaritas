<?php
session_start();

class dashboardController extends BaseController {
    public function index() {
        $sql_clienti = $this->registry->db->query("SELECT COUNT(DISTINCT codice_fiscale) AS numclienti FROM scontrini 
                                                    WHERE data BETWEEN '".date("Y-m-d 00:00:00")."' AND '".date("Y-m-d 23:59:59")."' ");
        $val_clienti = $sql_clienti->fetch();

        $sql_prodotti = $this->registry->db->query("SELECT SUM(spesa.qta) AS numprodotti FROM spesa 
                                                    WHERE data BETWEEN '".date("Y-m-d 00:00:00")."' AND '".date("Y-m-d 23:59:59")."'");
        $val_prodotti = $sql_prodotti->fetch();

        $sql_agea = "SELECT SUM(spesa.qta) AS numagea FROM spesa 
                    LEFT JOIN barcodes ON spesa.barcode = barcodes.barcode 
                    WHERE barcodes.agea = 't'
                    AND data BETWEEN '".date("Y-m-d 00:00:00")."' AND '".date("Y-m-d 23:59:59")."'";
        $ris_agea = $this->registry->db->query($sql_agea);
        $val_agea = $ris_agea->fetch();

        $this->registry->template->numclienti = $val_clienti['numclienti'];
        $this->registry->template->numprodotti = $val_prodotti['numprodotti'];
        $this->registry->template->numagea = $val_agea['numagea'];
        $this->registry->template->numricarico = $val_ricarico['numricarico'];
        $this->registry->template->show('dashboard');
    }
}

?>
