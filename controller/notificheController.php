<?php

class notificheController extends BaseController {

	public function index() {
		// vuota
	}

    /**
     * avvisi()
     *
     * Gestisce la visualizzazione delle notifiche e dei singoli messaggi
     */
	public function avvisi() {
		$week = date("Y-m-d H:i", strtotime("-1 week"));
		$sql = "SELECT * FROM notifiche WHERE data >= '".$week."' ORDER BY data DESC";
		$ris = $this->registry->db->query($sql);

		$nuovi_avvisi = 0;
		$msg = "";

        // Verifico la tipologia della notifica, in base alla quale verra` mostrato il messaggio
        // corretto
		foreach($ris as $key=>$val) {
			if($val['tipo'] == "riordino") {
				$background_thumb = "style='background:#f0ad4e; border-color: #eea236; cursor:pointer;'";
				$fa_icon = "<i class='fa fa-shopping-cart'></i>";
				$link = "/barcodes/cerca/riordino";
			}
			else if ($val['tipo'] == "critico") {
				$background_thumb = "style='background:#d9534f; border-color:#d43f3a; cursor:pointer;'";
				$fa_icon = "<i class='fa fa-shopping-cart'></i>";
				$link = "/barcodes/cerca/critico";
			}
            else if($val['tipo'] == "categorie") {
				//$background_thumb = "style='background:#337ab7; border-color:#2e6da4; cursor:pointer;'";
				$background_thumb = "style='background:#5bc0de; border-color:#46b8da; cursor:pointer;'";
				$fa_icon = "<i class='fa fa-th-list'></i>";
				$link = "/categorie/index";
            }
            else if($val['tipo'] == "tipologie") {
				$background_thumb = "style='background:#5bc0de; border-color:#46b8da; cursor:pointer;'";
				$fa_icon = "<i class='fa fa-list'></i>";
				$link = "/tipologie/index";
            }
            else if($val['tipo'] == "negativo") {
				$background_thumb = "style='background:#d9534f; border-color:#d43f3a; cursor:pointer;'";
				$fa_icon = "<i class='fa fa-barcode'></i>";
				$link = "/barcodes/cerca/stock_negativo";
            }
            else if($val['tipo'] == "inesistente") {
				$background_thumb = "style='background:#d9534f; border-color:#d43f3a; cursor:pointer;'";
				$fa_icon = "<i class='fa fa-barcode'></i>";
				$link = "/barcodes/index";
            }
            else if($val['tipo'] == "esaurito") {
				$background_thumb = "style='background:#f0ad4e; border-color:#eea236; cursor:pointer;'";
				$fa_icon = "<i class='fa fa-barcode'></i>";
				$link = "/barcodes/cerca/stock_esaurito";
            }
            else if($val['tipo'] == "puntireset_ok") {
				$background_thumb = "style='background:#5bc0de; border-color:#46b8da; cursor:pointer;'";
				$fa_icon = "<i class='fa fa-info-circle'></i>";
				$link = "/famiglie/index";
            }
            else if($val['tipo'] == "clienti_inscadenza") {
				$background_thumb = "style='background:#f0ad4e; border-color:#eea236; cursor:pointer;'";
				$fa_icon = "<i class='fa fa-id-card'></i>";
				$link = "/famiglie/cerca/inscadenza";
            }
            else if($val['tipo'] == "lotti") {
                $background_thumb = "style='background:#f0ad4e; border-color:#eea236; cursor:pointer;'";
                $fa_icon = "<i class='fa fa-tags'></i>";
                $link = "/barcodes/cerca/lotti";
            }

			if(!$val['letto']) {
				$nuovi_avvisi++;
				$background = "style='background:#eeeeee; cursor:pointer;'";
			}
			else {
				$background = "style='cursor:pointer'";
			}

            // Formatta la singola notifica
			$msg .= "<div class='messaggio' ".$background." id_notifica='".$val['id_notifica']."' data-url='".$link."'>
                        <div style:'position:relative; float:left; height:100%'>
                            <span class='msg_thumb' ".$background_thumb.">".$fa_icon." </span>
                        </div>
                        <div style:'position:relative; float:right; height:100%'>
                            <span class='msg_testo'>".$val['msg']."</span><br>
                            <span class='msg_data'><i class='fa fa-calendar'></i> ".date("d/m/Y H:i", strtotime($val['data']))."</span>
                        </div>
                     </div>";

		}

		echo json_encode(array(
            "responseText1" => $msg,
            "responseText2" => $nuovi_avvisi
        ));
	}

    /**
     * letto()
     * @param $id_notifica int
     *
     * Segna come letta la singola notifica cliccata
     */
    public function letto($id_notifica = '') {
        $sql = "UPDATE notifiche SET letto = 't' WHERE id_notifica = '".$id_notifica."'";
        $count = $this->registry->db->exec($sql);
    }

    /**
     * cancella_avvisi()
     *
     * Contrassegna i messaggi come gia` letti all'apertura del contenitore delle notifiche
     */
	public function cancella_avvisi() {
		$sql = "UPDATE notifiche SET letto = 't'";
		$count = $this->registry->db->exec($sql);
	}

}
?>
