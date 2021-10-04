<?php
session_start();

class utentiController extends BaseController {
	public function index() {
		$this->registry->template->ris = $this->registry->db->query("SELECT * FROM users ORDER BY login ASC");

		$this->registry->template->show("utenti");
	}

	public function scegli() {
		$this->registry->template->show("scegli");
	}

	public function nuovo() {
		$this->registry->template->show("nuovoutente");
	}

	public function inserisci() {
		$sql = "INSERT INTO users VALUES('".strtolower(pg_escape_string($_REQUEST['login']))."', '".pg_escape_string($_REQUEST['password'])."', '".$_REQUEST['ruolo']."', 
				'".pg_escape_string($_REQUEST['cognome'])."', '".pg_escape_string($_REQUEST['nome'])."', NULL)";
		$count = $this->registry->db->exec($sql);

		if($count > 0) {
			$this->registry->template->insertok = 1;
			
			$sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                    VALUES('".date("Y-m-d H:i:s")."', 
                            '[USERS]', 
                            'Creato nuovo utente ".$_REQUEST['ruolo'].": ".strtolower(pg_escape_string($_REQUEST['login']))."', 
                            '".$_SESSION['username']."')";
        	$count = $this->registry->db->exec($sql_log);
		}
		else {
			$this->registry->template->insertok = 0;
        }

		$this->nuovo();
	}

	public function modifica($user = '') {
		$this->registry->template->user = $user;

		$sql = "SELECT * FROM users WHERE login = '".$user."' ORDER BY login ASC";
		$this->registry->template->ris = $this->registry->db->query($sql);

		$this->registry->template->show("modificautente");
	}

	public function update_utente($user = '') {
		$sql = "UPDATE users SET(password, ruolo, cognome, nome) = 
				('".pg_escape_string($_REQUEST['password'])."', '".$_REQUEST['ruolo']."', '".pg_escape_string($_REQUEST['cognome'])."', '".pg_escape_string($_REQUEST['nome'])."')
				WHERE login = '".$user."'";
		$count = $this->registry->db->exec($sql);

		if($count > 0) {
            $this->registry->template->modifyok = 1;

            $sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                    VALUES('".date("Y-m-d H:i:s")."', 
                            '[USERS]', 
                            'Modificato utente: ruolo ".$_REQUEST['ruolo'].", login ".strtolower(pg_escape_string($_REQUEST['login']))."', 
                            '".$_SESSION['username']."')";
            $count = $this->registry->db->exec($sql_log);
		}
        else {
            $this->registry->template->modifyok = 0;
        }

        $this->modifica($user);
	}

	public function rimuovi() {
		$sql = "DELETE FROM users WHERE login = '".$_REQUEST['fake-form-utente']."'";
		$count = $this->registry->db->exec($sql);

        if($count > 0) {
            $this->registry->template->deleteok = 1;

		    $sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                        VALUES('".date("Y-m-d H:i:s")."', 
                            '[USERS]', 
                            'Rimosso utente: ".$_REQUEST['fake-form-utente']."', 
                            '".$_SESSION['username']."')";
            $count = $this->registry->db->exec($sql_log);
        }
        else {
            $this->registry->template->deleteok = 0;
        }

	    $this->index();
	}

    public function sblocca($user = '') {
        $sql = "UPDATE users SET logged = 'f' WHERE login = '".$user."'";
        $count = $this->registry->db->exec($sql);

        if($count > 0) {
            $this->registry->template->unlockok = 1;

            $sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                        VALUES('".date("Y-m-d H:i:s")."', 
                            '[USERS]', 
                            'Utente sbloccato: ".$user."', 
                            '".$_SESSION['username']."')";
            $count = $this->registry->db->exec($sql_log);
        }
        else {
            $this->registry->template->unlockok = 0;
        }

        $this->index();
    }
}

?>
