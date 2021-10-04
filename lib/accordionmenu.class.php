<?php
/**
 * Crea un menu accordion con voci e sottovoci. 
 * Per ogni voce vi è la possibilità di aggiungere un'icona di font-awesome
 * (di cui basta semplicemente sppecificare il nome, senza il prefisso "fa-")
 * e il link al quale la voce puunta. 
 * Il menu è customizzabile utilizzando un css esterno.
 * 
 * Esempio d'uso:
 *
 * $am::AccordionMenu::getInstance();
 * $am->createVoice('nome voce', '#voce1', 'users');
 * $am->subVoice('sottomenu1', 'http://google.it', 'close');
 * $am->subVoice('sottomenu2', /path/to/pagina', 'info');
 * $am->endVoice();
 * $am->showMenu();
 */

class AccordionMenu {

	private static $instance = NULL;
	public $menu = NULL;

	private function __construct() {
	}

	public static function getInstance() {
        if(is_null(self::$instance)) {
            self::$instance = new AccordionMenu;
        }

        return self::$instance;
    }

	/**
	 * Crea la voce principale di menu
	 *
	 * @param $name - nome della voce di menu
	 * @param $href - link a cui punta la voce. In caso di sottomenu, $href deve essere del tipo "#ancora".
	 * @param $icon - opzionale, icona da associare alla voce di menu, icona richiamata dalla libreria FA. 
	 *	Se si vuole richiamare, ad esempio, l'icona "fa-user" è sufficiente specificare "user", senza prefisso
	 *
	 */
	public function createVoice($name, $href = "#", $icon = "", $background = "transparent") {
		$this->menu .= "<div class=\"panel panel-default\">\r\n";
		$this->menu .= "<div class=\"panel-heading\" style=\"background-color:".$background."\">\r\n";
		$this->menu .= "<h4 class=\"panel-title\">\r\n";
		$this->menu .= "<a data-toggle=\"collapse\" data-parent=\"#accordion\" href=\"".$href."\"><i style=\"margin-right:10px\" class=\"fa fa-".$icon."\"></i>".$name."</a>\r\n";
		$this->menu .= "</h4>\r\n";
		$this->menu .= "</div>\r\n";

		$this->menu .= "<div id=\"".str_replace("#", "", $href)."\" class=\"panel-collapse collapse\">\r\n";
		$this->menu .= "<div class=\"panel-body\" style=\"padding:0px;\">\r\n";
		$this->menu .= "<table id=\"submenu\" class=\"table\" style=\"margin-bottom: 0px;\">\r\n";	
	}

	/**
	 * Crea le voci di sottomenu. Il comportamento è il medesimo della funzione createVoice()
	 */
	public function subVoice($name, $link = "#", $icon = "") {
		$this->menu .= "<tr>\r\n";
		$this->menu .= "<td style=\"padding-left: 30px\"><i style=\"margin-right:10px\" class=\"fa fa-".$icon."\"></i><a href=\"".$link."\">".$name."</a></td>\r\n";
		$this->menu .= "</tr>\r\n";
	}

	/**
	 * Chiude i tag del sottomenu e della voce principale, visto che non c'è altro modo di farlo
	 */	
	public function endVoice() {
		$this->menu .= "</table>\r\n";
		$this->menu .= "</div>\r\n</div>\r\n</div>\r\n";
	}

	/** 
	 * Mostra il menu
	 */
	public function showMenu() {
		echo "<div class=\"panel-group\" id=\"accordion\">\r\n";
		echo $this->menu;
		echo "</div>\r\n";
	}
}
