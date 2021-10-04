<?php
session_start();

class reportController extends BaseController {
	public function index() {
		// TODO
	}

    public function venduto($tipo = '') {
        if(!isset($_REQUEST['cerca'])) { unset($_SESSION['xls_venduto']); }

        if(isset($_REQUEST['anno'])) { 
            $anno = $_REQUEST['anno']; 
            $this->registry->template->anno = $_REQUEST['anno'];
        }
        else { 
            $anno = date("Y"); 
            $this->registry->template->anno = date("Y");
        }

        if(isset($_REQUEST['mese'])) { 
            $mese = $_REQUEST['mese']; 
            $this->registry->template->mese = $_REQUEST['mese'];
        }
        else { 
            $mese = date("m"); 
            $this->registry->template->mese = date("m");
        }

        if($tipo == "prodotti") {
            $sql = "SELECT DATE(spesa.data), 
                            spesa.barcode, 
                            barcodes.descrizione, 
                            tipologie.descrizione_tipologia, 
                            categorie.descrizione_categoria, 
                            spesa.punti, 
                            spesa.scontato,
                            SUM(spesa.qta) AS qta 
                    FROM spesa 
                    INNER JOIN barcodes ON spesa.barcode = barcodes.barcode 
                    INNER JOIN tipologie ON spesa.tipologia = tipologie.id_tipologia 
                    INNER JOIN categorie ON categorie.id_categoria = tipologie.categoria 
                    WHERE EXTRACT(YEAR FROM spesa.data) = '".$anno."' AND EXTRACT(MONTH FROM spesa.data) = '".$mese."'
                    GROUP BY DATE(spesa.data), 
                            spesa.barcode, 
                            barcodes.descrizione, 
                            tipologie.descrizione_tipologia, 
                            categorie.descrizione_categoria,
                            spesa.punti,
                            spesa.scontato
                    ORDER BY DATE(spesa.data) ASC, barcodes.descrizione ASC";
        }
        else if($tipo == "famiglie") {
            $sql = "SELECT  famiglie.cognome,
                            famiglie.nome,
                            famiglie.punti_totali,
                            DATE(spesa.data),
                            spesa.barcode,
                            barcodes.descrizione,
                            tipologie.descrizione_tipologia,
                            categorie.descrizione_categoria,
                            spesa.punti,
                            spesa.scontato,
                            SUM(spesa.qta) AS qta,
                            SUM(scontrini.totale_punti) AS totale_spesa
                    FROM spesa
                    INNER JOIN scontrini ON spesa.id_scontrino = scontrini.id_scontrino
                    INNER JOIN famiglie ON scontrini.codice_fiscale = famiglie.codice_fiscale
                    INNER JOIN barcodes ON spesa.barcode = barcodes.barcode
                    INNER JOIN tipologie ON spesa.tipologia = tipologie.id_tipologia
                    INNER JOIN categorie ON categorie.id_categoria = tipologie.categoria
                    WHERE EXTRACT(YEAR FROM spesa.data) = '".$anno."' AND EXTRACT(MONTH FROM spesa.data) = '".$mese."'
                    GROUP BY famiglie.cognome,
                            famiglie.nome, 
                            famiglie.punti_totali,
                            DATE(spesa.data),
                            spesa.barcode,
                            barcodes.descrizione,
                            tipologie.descrizione_tipologia,
                            categorie.descrizione_categoria,
                            spesa.punti,
                            spesa.scontato
                    ORDER BY DATE(spesa.data) ASC, famiglie.cognome ASC, barcodes.descrizione ASC;";
        }
        
        $_SESSION['xls_venduto'] = $sql;
       
        $this->registry->template->ris = $this->registry->db->query($sql);

        $this->registry->template->ris_anno = $this->registry->db->query("SELECT DISTINCT(EXTRACT(YEAR FROM spesa.data)) AS anno FROM spesa ORDER BY anno DESC");
        $this->registry->template->tipo = $tipo;
        
        if(isset($_REQUEST['cerca'])) {
            $this->registry->template->cerca = 1;
        }

        $this->registry->template->show('reportvenduto');
    }

    public function esportavenduto($tipo = '', $anno = '', $mese = '') {
        $sql = $_SESSION['xls_venduto'];
        $this->registry->template->ris = $this->registry->db->query($sql);

        $this->registry->template->tipo = $tipo;
        $this->registry->template->anno = $anno;
        $this->registry->template->mese = $mese;
        $this->registry->template->show_noheader('xls_venduto');
    }

    public function donazioni($tipo = '') {
        if(!isset($_REQUEST['cerca'])) { 
            unset($_SESSION['xls_donazioni']); 
            unset($_SESSION['xls_acquisti']); 
        }

        if(isset($_REQUEST['anno'])) {
            $anno = $_REQUEST['anno'];
            $this->registry->template->anno = $_REQUEST['anno'];
        }
        else {
            $anno = date("Y");
            $this->registry->template->anno = date("Y");
        }

        if(isset($_REQUEST['mese'])) {
            $mese = $_REQUEST['mese'];
            $this->registry->template->mese = $_REQUEST['mese'];
        }
        else {
            $mese = date("m");
            $this->registry->template->mese = date("m");
        }

        $sql_donazioni = "SELECT donazioni.data_donazione,
                                fornitori.ragione_sociale,
                                donazioni.barcode,
                                barcodes.descrizione,
                                tipologie.descrizione_tipologia,
                                categorie.descrizione_categoria,
                                SUM(donazioni.quantita) AS quantita
                            FROM donazioni
                            INNER JOIN fornitori ON fornitori.id_fornitore = donazioni.id_fornitore
                            INNER JOIN barcodes ON donazioni.barcode = barcodes.barcode
                            INNER JOIN tipologie ON barcodes.tipologia = tipologie.id_tipologia
                            INNER JOIN categorie ON tipologie.categoria = categorie.id_categoria
                            WHERE donazioni.acquistato = 'f'
                                AND EXTRACT(YEAR FROM donazioni.data_donazione) = '".$anno."' ";
        if(!empty($mese)) {
            $sql_donazioni .= "AND EXTRACT(MONTH FROM donazioni.data_donazione) = '".$mese."' ";
        }

        $sql_donazioni .= "GROUP BY donazioni.data_donazione,
                                    fornitori.ragione_sociale,
                                    donazioni.barcode,
                                    barcodes.descrizione,
                                    tipologie.descrizione_tipologia,
                                    categorie.descrizione_categoria
                            ORDER BY donazioni.data_donazione ASC, barcodes.descrizione ASC";

        $sql_acquisti = "SELECT donazioni.data_donazione,
                                fornitori.ragione_sociale,
                                donazioni.barcode,
                                barcodes.descrizione,
                                tipologie.descrizione_tipologia,
                                categorie.descrizione_categoria,
                                SUM(donazioni.quantita) AS quantita
                            FROM donazioni
                            INNER JOIN fornitori ON fornitori.id_fornitore = donazioni.id_fornitore
                            INNER JOIN barcodes ON donazioni.barcode = barcodes.barcode
                            INNER JOIN tipologie ON barcodes.tipologia = tipologie.id_tipologia
                            INNER JOIN categorie ON tipologie.categoria = categorie.id_categoria
                            WHERE donazioni.acquistato = 't'
                                AND EXTRACT(YEAR FROM donazioni.data_donazione) = '".$anno."' ";
        if(!empty($mese)) {
            $sql_acquisti .= "AND EXTRACT(MONTH FROM donazioni.data_donazione) = '".$mese."' ";
        }

        $sql_acquisti .= "GROUP BY donazioni.data_donazione,
                                    fornitori.ragione_sociale,
                                    donazioni.barcode,
                                    barcodes.descrizione,
                                    tipologie.descrizione_tipologia,
                                    categorie.descrizione_categoria
                            ORDER BY donazioni.data_donazione ASC, barcodes.descrizione ASC";

        $_SESSION['xls_donazioni'] = $sql_donazioni;
        $_SESSION['xls_acquisti'] = $sql_acquisti;
       
        $this->registry->template->ris_donazioni = $this->registry->db->query($sql_donazioni);
        $this->registry->template->ris_acquisti = $this->registry->db->query($sql_acquisti);

        $this->registry->template->ris_anno = $this->registry->db->query("SELECT DISTINCT(EXTRACT(YEAR FROM spesa.data)) AS anno FROM spesa ORDER BY anno DESC");
        $this->registry->template->tipo = $tipo;
        
        if(isset($_REQUEST['cerca'])) {
            $this->registry->template->cerca = 1;
        }

        $this->registry->template->show('reportdonazioni');
    }

    public function esportadonazioni($tipo = '', $anno = '', $mese = '') {
        if($tipo == "donazioni") {
            $sql = $_SESSION['xls_donazioni'];
        }
        else if($tipo == "acquisti") {
            $sql = $_SESSION['xls_acquisti'];
        }

        $this->registry->template->ris = $this->registry->db->query($sql);

        $this->registry->template->tipo = $tipo;
        $this->registry->template->anno = $anno;
        $this->registry->template->mese = $mese;
        $this->registry->template->show_noheader('xls_donazioni');
    }
}

?>
