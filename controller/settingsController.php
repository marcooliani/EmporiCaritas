<?php
session_start();

class settingsController extends BaseController {
    
    public function index() {
        $config = Config::getInstance();
        $this->registry->template->paper = $config->config_values['settings']['tipo_carta'];
        $this->registry->template->font = $config->config_values['settings']['scontrino_fontsize'];

        $this->registry->template->show('settings');
    }

    public function salva() {
        if($_REQUEST['tipo_carta'] == "continua") {
            $fontsize = "6pt";
        }
        else if($_REQUEST['tipo_carta'] == "a4") {
            if($_REQUEST['fontsize'] == "small")
                $fontsize = "6pt";
            if($_REQUEST['fontsize'] == "medium")
                $fontsize = "8pt";
            if($_REQUEST['fontsize'] == "big")
                $fontsize = "12pt";
        }

        $cfg = new iniParser("config.ini");
        $tool = $cfg->get("settings");
        $cfg->setValue("settings","tipo_carta", $_REQUEST['tipo_carta']);
        $cfg->setValue("settings","scontrino_fontsize", $fontsize);
        $cfg->save("config.ini");

        $this->registry->template->insertok = 1;

        //$this->index();
        header("Location: /settings/index");
    }
}

?>
