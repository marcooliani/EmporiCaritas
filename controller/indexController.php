<?php
session_start();

class indexController extends BaseController {

	public function index() {
    
        if(isset($_SESSION['username']) && isset($_SESSION['ruolo'])) {
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
            //  header("Location: /dashboard/index");
            }
        }

        else {
            $this->registry->template->show('index');
        }
	}
}

?>
