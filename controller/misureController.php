<?php

class misureController extends BaseController {
	
	public function index() {
		$this->registry->template->ris = $this->registry->db->query("SELECT * FROM um ORDER BY tipo ASC, val_um ASC");

		$this->registry->template->show("misure");
	}

	public function nuovo() {
		$this->registry->template->show("nuovamisura");
	}

	public function inserisci() {
		$sql = "INSERT INTO um VALUES('".strtolower(pg_escape_string($_REQUEST['val_um']))."', 
                                    '".strtolower(pg_escape_string($_REQUEST['descrizione']))."',
                                    '".$_REQUEST['tipo_um']."')";
		$count = $this->registry->db->exec($sql);

		if($count > 0)
			$this->registry->template->insertok = 1;
		else
			$this->registry->template->insertok = 0;

		$this->registry->template->show("nuovamisura");
	}

	public function cerca() {
	}

	public function rimuovi() {
		$sql = "DELETE FROM um WHERE val_um = '".$_REQUEST['fake-form-misura']."'";
		$count = $this->registry->db->exec($sql);

		header("Location: /misure/index");
	}
}
