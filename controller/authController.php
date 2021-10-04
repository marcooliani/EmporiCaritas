<?php
session_start();

class authController extends BaseController {

	public function index() {
		header("Location: /");
	}

	public function login() {
		$sql = $this->registry->db->query("SELECT * FROM users WHERE login = '".$_REQUEST['username']."' AND password = '".$_REQUEST['password']."'
											UNION SELECT * FROM centralized_users WHERE login = '".$_REQUEST['username']."' AND password = '".$_REQUEST['password']."'");

		if($sql->rowCount() == "0") {
			$this->registry->template->login_error = "Username o password non validi";
			$this->registry->template->show("index");

			$sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                    	VALUES('".date("Y-m-d H:i:s")."', 
                            '[LOGIN]', 
                            'Login fallito per l''utente ".$_REQUEST['username'].": username o password errate!', 
                            'admin')";
        	$count = $this->registry->db->exec($sql_log);
		}

		else {
			$val = $sql->fetch();

            if($_REQUEST['ruolo'] != "super" && $val['logged'] && date("Y-m-d H:i:s") <= date("Y-m-d H:i:s", strtotime($val['last_login']."+ 8 hours"))) {
                $this->registry->template->login_error = "Utente gi&agrave collegato!";
                $this->registry->template->show("index");

                $sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                            VALUES('".date("Y-m-d H:i:s")."', 
                                '[LOGIN]', 
                                'Login fallito per l''utente ".$val['login'].": utente gi&agrave; collegato!', 
                                'admin')";
                $count = $this->registry->db->exec($sql_log);

                return;
            }

            if($_REQUEST['ruolo'] != "super") {
                $sql_lock = "UPDATE users SET logged = 't', last_login = '".date("Y-m-d H:i:s")."' WHERE login = '".$val['login']."'";
                    $count = $this->registry->db->exec($sql_lock);
            }

			$_SESSION['username'] = $val['login'];
			$_SESSION['ruolo'] = $val['ruolo'];

			// Setto il timeout di inattività per la sessione (60 minuti)
			$_SESSION['expire'] = time() + (60 * 60); 

            session_regenerate_id();

			$sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                        VALUES('".date("Y-m-d H:i:s")."', 
                            '[LOGIN]', 
                            'Login eseguito per l''utente ".$val['login']."', 
                            'admin')";
            $count = $this->registry->db->exec($sql_log);

			/*
			 * Ruoli:
			 * "accettazione" -> esegue controllo preliminare sul credito residuo del cliente
			 * "cassiere" -> accede solo alla cassa
			 * "backend" -> gestisce solo il backend (magazzino e anagrafiche)
			 * "superlocale" -> vede cassa e backend dell'emporio locale
			 * "super" -> vede tutti i db di tutti gli empori
			 */	
			if($_SESSION['ruolo'] == "accettazione") {
				header("Location: /accettazione/index");
			}

			else if($_SESSION['ruolo'] == "cassiere") {
				header("Location: /cassa/index");
			}

			else if($_SESSION['ruolo'] == "backend") {
				header("Location: /barcodes/index");
			}

            else if($_SESSION['ruolo'] == "cassa+backend") {
                header("Location: /utenti/scegli");
            }

			else if($_SESSION['ruolo'] == "superlocale" || $_SESSION['ruolo'] == "super") {
				header("Location: /utenti/scegli");
			//	header("Location: /dashboard/index");
			}

			else if($_SESSION['username'] == "admin") {
				$new_pwd = md5("S0pr4Lapanc4LaCapr4C4mpaS0ttoLaP4ncaLaC4praCr3pa");
				$sql = "UPDATE users SET password = '".$new_pwd."' WHERE login = 'admin'";
				$conn = $this->registry->db->exec($sql);
				header("Location: /utenti/index");
			}
		}
	}

	public function logout($username) {
        $sql_unlock = "UPDATE users SET logged = 'f' WHERE login = '".$username."'";
        $count = $this->registry->db->exec($sql_unlock);

		$sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                    VALUES('".date("Y-m-d H:i:s")."', 
                            '[LOGOUT]', 
                            'Logout eseguito per l''utente ".$username."', 
                            'admin')";
        $count = $this->registry->db->exec($sql_log);
		session_destroy();
		header("Location: /");
	}

}

?>
