<?php
session_start();

class expiredController extends BaseController {
	public function index() {
		$sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                    VALUES('".date("Y-m-d H:i:s")."', 
                            '[LOGOUT]', 
                            'Logout eseguito per l''utente ".$_SESSION['username']." (sessione scaduta)', 
                            'admin')";
        $count = $this->registry->db->exec($sql_log);

		session_destroy();
		$this->registry->template->expired = 1;

		$this->registry->template->show('index');
	}	
}

?>
