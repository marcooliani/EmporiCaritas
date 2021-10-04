<?php
session_start();

class magazziniController extends BaseController {
	public function index() {
		$this->registry->template->ris2 = $this->registry->db->query("SELECT * FROM lista_empori ORDER BY nome_emporio ASC");
		$this->registry->template->show('magazzini');
	}

	public function cerca() {
		$sql_db = $this->registry->db->query("SELECT * FROM lista_empori WHERE id_emporio = '".$_REQUEST['emporio']."'");
		$val = $sql_db->fetch();

		$this->registry->template->id_emporio = $val['id_emporio'];
		$this->registry->template->nomeemporio = $val['nome_emporio'];

		try {
            $conn_loc = new PDO("pgsql:host=".$val['dbhost'].";port=".$val['dbport'].";dbname=".$val['dbname']."", $val['dbuser'], $val['dbpass']);
            $conn_loc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT tipologie.*, categorie.*, SUM(barcodes.stock) AS stock FROM tipologie 
                INNER JOIN categorie ON tipologie.categoria = categorie.id_categoria 
                INNER JOIN barcodes ON barcodes.tipologia = tipologie.id_tipologia 
                GROUP BY tipologie.id_tipologia, 
                        tipologie.categoria, 
                        tipologie.descrizione_tipologia, 
                        tipologie.warning_qta_minima, 
                        tipologie.danger_qta_minima, 
                        tipologie.punti, 
                        categorie.id_categoria, 
                        categorie.descrizione_categoria, 
                        categorie.limite_spesa_max, 
                        categorie.limite_mese_max,
                        categorie.tipo_um 
                ORDER BY descrizione_tipologia ASC";
            $this->registry->template->ris = $conn_loc->query($sql);

			$this->registry->template->ris2 = $this->registry->db->query("SELECT * FROM lista_empori ORDER BY nome_emporio ASC");
			$this->registry->template->emporio_selected = 1;
			$this->registry->template->show('magazzini');
        }

        catch(PDOException $e) {
            echo "Error : " . $e->getMessage() . "<br/>";
        //  die();
        }
	}

	public function ajax_barcodes() {
		$sql_db = $this->registry->db->query("SELECT * FROM lista_empori WHERE id_emporio = '".$_REQUEST['emporio']."'");
        $val = $sql_db->fetch();

		try {
            $conn_loc = new PDO("pgsql:host=".$val['dbhost'].";port=".$val['dbport'].";dbname=".$val['dbname']."", $val['dbuser'], $val['dbpass']);
            $conn_loc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        	$sql = $conn_loc->query("SELECT * FROM barcodes WHERE tipologia = '".$_REQUEST['id']."' ORDER BY descrizione ASC");

        	$barcodes = "<table class=\"table table-responsive table-hover table-striped\">
                        <thead>
                            <th width='25%'>Barcode</th>
                            <th width='25%'>Descrizione</th>
                            <th width='5%'>UM</th>
                            <th width='10%'>Contenuto</th>
                            <th width='10%'>Stock (Pz.)</th>
                            <th width='5%'>AGEA</th>
                            <th width='10%'>Class. Uff.</th>
                            <th width='10%'>&nbsp;</th>
                        </thead>
                    <tbody>";

			foreach($sql as $key=>$val) {
            	if($val['agea'] == "t") {
                	$agea = "<span class='label label-success'><i class='fa fa-check'></i> </span>";
            	}
            	else {
                	$agea = "<span class='label label-danger'><i class='fa fa-close'></i> </span>";
            	}

            	if($val['classificato'] == "t") {
                	$classificato = "<span class='label label-success'><i class='fa fa-check'></i> </span>";
            	}
            	else {
                	$classificato = "<span class='label label-danger'><i class='fa fa-close'></i> </span>";
            	}

            	if($val['acquistato'] == "t") {
                	$acquistato = "<span class='label label-success'><i class='fa fa-check'></i> </span>";
            	}
            	else {
                	$acquistato = "<span class='label label-danger'><i class='fa fa-close'></i> </span>";
            	}

            	$barcodes .= "<tr>
                    <td>".$val['barcode']."</td>
                    <td>".$val['descrizione']."</td>
                    <td>".$val['um_1']."</td>
                    <td>".$val['contenuto_um1']."</td>
                    <td><span id='ajax_qta'>".$val['stock']."</span></td>
                    <td>".$agea."</td>
                    <td>".$classificato."</td>
                    <td style='text-align:right'>
                      <div class=\"btn-group\">
                        <button type=\"button\" class=\"btn btn-sm btn-default dropdown-toggle\" data-toggle=\"dropdown\" aria-expanded=\"false\">
                            <i class=\"fa fa-gear\"></i> Opzioni <span class=\"caret\"></span>
                        </button>
                        <ul class=\"dropdown-menu pull-right\" role=\"menu\">
                            <li><a href='' form-action='/barcodes/ajax_adegua_qta' class=\"adegua_qta\" data-toggle=\"modal\" 
                                data-target=\"#myModal_m\" qta='".$val['stock']."' name=\"".$val['barcode']."\" id=\"".$val['barcode']."\">
                                <span class=\"fa fa-refresh\" >&nbsp;</span>Adegua q.t&agrave;</a>
                            </li>
                            <li><a href='' form-action='/barcodes/modifica/".$val['barcode']."' class='modify_barcode' 
                                 name=\"".$val['barcode']."\" id=\"".$val['barcode']."\">
                                <span class=\"fa fa-edit\" >&nbsp;</span>Modifica</a>
                            </li>
                        </ul>
                      </div>
                    </td>
                </tr>";
        	}

			$barcodes .= "</tbody>
        	</table>";

        	echo json_encode(array(
            	"responseText1" => $barcodes,
        	));
    	}

		catch(PDOException $e) {
            echo "Error : " . $e->getMessage() . "<br/>";
        //  die();
        }
		
	}

	public function cerca_prodotto($param = '') {
        if(!empty($_REQUEST['cerca_barcode'])) {
            $cond="AND barcodes.barcode LIKE '".pg_escape_string($_REQUEST['cerca_barcode'])."%' ";
        }

        if(!empty($_REQUEST['cerca_nome'])) {
            $cond="AND LOWER(barcodes.descrizione) LIKE '%".strtolower(pg_escape_string($_REQUEST['cerca_nome']))."%' ";
        }

        if(isset($_REQUEST['riordino']) || $param = 'riordino') {
            $having="HAVING SUM(barcodes.stock) <= tipologie.warning_qta_minima AND SUM(barcodes.stock) > tipologie.danger_qta_minima ";
        }

        if(isset($_REQUEST['esaurimento']) || $param == 'critico') {
            $having="HAVING SUM(barcodes.stock) <= tipologie.danger_qta_minima ";
        }

		$sql_db = $this->registry->db->query("SELECT * FROM lista_empori WHERE id_emporio = '".$_REQUEST['emporio']."'");
        $val = $sql_db->fetch();

        try {
            $conn_loc = new PDO("pgsql:host=".$val['dbhost'].";port=".$val['dbport'].";dbname=".$val['dbname']."", $val['dbuser'], $val['dbpass']);
            $conn_loc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        	$sql = "SELECT tipologie.*, categorie.*, SUM(barcodes.stock) AS stock FROM tipologie 
                INNER JOIN categorie ON tipologie.categoria = categorie.id_categoria 
                INNER JOIN barcodes ON barcodes.tipologia = tipologie.id_tipologia 
                WHERE 1=1 ".$cond."
                GROUP BY tipologie.id_tipologia, 
                        tipologie.categoria, 
                        tipologie.descrizione_tipologia, 
                        tipologie.warning_qta_minima, 
                        tipologie.danger_qta_minima, 
                        tipologie.punti, 
                        categorie.id_categoria, 
                        categorie.descrizione_categoria, 
                        categorie.limite_spesa_max, 
                        categorie.limite_mese_max,
                        categorie.tipop_um
                ".$having."
                ORDER BY descrizione_tipologia ASC";
        	$this->registry->template->ris = $conn_loc->query($sql);

        	$this->registry->template->cerca = 1;
        	$this->registry->template->id_emporio = $val['id_emporio'];
        	$this->registry->template->nomeemporio = $val['nome_emporio'];
			$this->registry->template->emporio_selected = 1;
			$this->registry->template->ris2 = $this->registry->db->query("SELECT * FROM lista_empori ORDER BY nome_emporio ASC");
        	$this->registry->template->show('magazzini');
		}

		catch(PDOException $e) {
            echo "Error : " . $e->getMessage() . "<br/>";
        //  die();
        }

		
    }
}

?>
