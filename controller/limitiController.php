<?php
class limitiController extends BaseController {

	public function index() {
		$sql = "SELECT limiti_nucleo.*, categorie.descrizione_categoria FROM limiti_nucleo 
				INNER JOIN categorie ON limiti_nucleo.categoria_merce = categorie.id_categoria
                ORDER BY categorie.descrizione_categoria ASC";
		$this->registry->template->ris = $this->registry->db->query($sql);

		$this->registry->template->show('limiti');
	}

	public function nuovo() {
		$this->registry->template->ris = $this->registry->db->query("SELECT * FROM categorie ORDER BY descrizione_categoria ASC");

		$this->registry->template->show('nuovolimite');
	}

	public function inserisci() {
		$sql = "INSERT INTO limiti_nucleo 
				VALUES('".$_REQUEST['id_categoria']."', 
						'".$_REQUEST['lim_1c_spesa']."', 
						'".$_REQUEST['lim_2c_spesa']."', 
						'".$_REQUEST['lim_3c_spesa']."', 
						'".$_REQUEST['lim_4c_spesa']."', 
						'".$_REQUEST['lim_5c_spesa']."', 
						'".$_REQUEST['lim_6c_spesa']."', 
						'".$_REQUEST['lim_7c_spesa']."', 
						'".$_REQUEST['lim_8c_spesa']."', 
						'".$_REQUEST['lim_9c_spesa']."', 
						'".$_REQUEST['lim_10c_spesa']."', 
						'".$_REQUEST['lim_1c_mese']."', 
						'".$_REQUEST['lim_2c_mese']."', 
						'".$_REQUEST['lim_3c_mese']."', 
						'".$_REQUEST['lim_4c_mese']."', 
						'".$_REQUEST['lim_5c_mese']."', 
						'".$_REQUEST['lim_6c_mese']."', 
						'".$_REQUEST['lim_7c_mese']."', 
						'".$_REQUEST['lim_8c_mese']."', 
						'".$_REQUEST['lim_9c_mese']."', 
						'".$_REQUEST['lim_10c_mese']."')";

		$count = $this->registry->db->exec($sql);

		if($count > 0) {
 			$this->registry->template->insertok = 1;
		}
       	else {
			$this->registry->template->insertok = 0;
		}

        header("Location: /limiti/nuovo_limite");
	}

	public function modifica($categoria = '') {
		$this->registry->template->categoria = $categoria;

		$sql = "SELECT limiti_nucleo.*, categorie.descrizione_categoria FROM limiti_nucleo 
				INNER JOIN categorie ON limiti_nucleo.categoria_merce = categorie.id_categoria
				WHERE categoria_merce = '".$categoria."'";
		$this->registry->template->ris = $this->registry->db->query($sql);

		$this->registry->template->show('modificalimite');
	}

	public function update_limite($categoria = '') {
		$sql = "UPDATE limiti_nucleo SET(lim_1c_spesa, lim_2c_spesa, lim_3c_spesa, lim_4c_spesa, 
					lim_5c_spesa, lim_6c_spesa, lim_7c_spesa, lim_8c_spesa, lim_9c_spesa, lim_10c_spesa, 
					lim_1c_mese, lim_2c_mese, lim_3c_mese, lim_4c_mese, lim_5c_mese, lim_6c_mese, 
					lim_7c_mese, lim_8c_mese, lim_9c_mese, lim_10c_mese ) =
                ('".$_REQUEST['lim_1c_spesa']."', 
                 '".$_REQUEST['lim_2c_spesa']."', 
                 '".$_REQUEST['lim_3c_spesa']."', 
                 '".$_REQUEST['lim_4c_spesa']."', 
                 '".$_REQUEST['lim_5c_spesa']."', 
                 '".$_REQUEST['lim_6c_spesa']."', 
                 '".$_REQUEST['lim_7c_spesa']."', 
                 '".$_REQUEST['lim_8c_spesa']."', 
                 '".$_REQUEST['lim_9c_spesa']."', 
                 '".$_REQUEST['lim_10c_spesa']."', 
                 '".$_REQUEST['lim_1c_mese']."', 
                 '".$_REQUEST['lim_2c_mese']."', 
                 '".$_REQUEST['lim_3c_mese']."', 
                 '".$_REQUEST['lim_4c_mese']."', 
                 '".$_REQUEST['lim_5c_mese']."', 
                 '".$_REQUEST['lim_6c_mese']."', 
                 '".$_REQUEST['lim_7c_mese']."', 
                 '".$_REQUEST['lim_8c_mese']."', 
                 '".$_REQUEST['lim_9c_mese']."', 
                 '".$_REQUEST['lim_10c_mese']."')
				WHERE categoria_merce = '".$categoria."'";

        $count = $this->registry->db->exec($sql);

        header("Location: /limiti/index");
	}

	public function cerca() {
        $sql = "SELECT limiti_nucleo.*, categorie.descrizione_categoria FROM limiti_nucleo 
                INNER JOIN categorie ON limiti_nucleo.categoria_merce = categorie.id_categoria
                WHERE LOWER(categorie.descrizione_categoria) LIKE '%".strtolower(pg_escape_string($_REQUEST['cerca_limite']))."%'
                ORDER BY categorie.descrizione_categoria ASC";
        $this->registry->template->ris = $this->registry->db->query($sql);

        $this->registry->template->show('limiti');
	}

	public function rimuovi() {
		$sql = "DELETE FROM limiti_nucleo WHERE categoria_merce = '".$_REQUEST['fake-form-limite']."'";
		$count = $this->registry->db->exec($sql);

		header("Location: /limiti/index");		
	}	

}
?>

