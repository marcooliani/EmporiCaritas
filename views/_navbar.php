<?php 
	if((!isset($_SESSION['username'])) || ($_SESSION['ruolo'] == 'cassiere')) { 
		header("Location: /cassa/index"); 
	}

    if((!isset($_SESSION['username'])) || ($_SESSION['ruolo'] == 'accettazione')) {
        header("Location: /accettazione/index");
    }

	if($_SESSION['expire'] < time()) {
		header("Location: /expired");
	}
	else {
		$_SESSION['expire'] = time() + (60*60);
	}

	$config = Config::getInstance();
	$nome_emporio = ucwords($config->config_values['emporio']['nome_emporio']);
?>

<nav class="navbar navbar-default" role="navigation">
            <div class="navbar-header pull-left">
				<a class="navbar-brand" href="javascript:void(0)"><?php echo ucwords($nome_emporio); ?></a>
            </div>

            <div class="navbar-header pull-right">
			<!-- Brand and toggle get grouped for better mobile display -->
                <button type="button" data-toggle="collapse" data-target=".navbar-collapse" class="navbar-toggle">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>

            <div class="navbar-header pull-right">&nbsp;&nbsp;</div>

            <div class="navbar-header pull-right">
                <ul class="nav navbar-nav navbar-left">
                    <li class='dropdown pull-left' id="chat_container" name="chat_container">
                        <div id="chat_counter"></div>
                        <div id="chat_button"><i id="chat_icon" class="fa fa-comment fa-lg"></i></div>
                        <div id="chats">
                            <h5 class="chat"><div style="padding-bottom:15px;"><div style="float:left">Messaggi</div>
                                <div style="float:right"><a style="cursor:pointer; color:#333">Nuovo messaggio</a></div></div></h5>
                            <div id="messages_chat" style="height:300px; clear:both">
                            </div>
                            <div class="seeAll"><a href="/messaggi/index">Mostra tutto</a></div>
                        </div>
                    </li> 
                    <li class="dropdown pull-left">&nbsp;&nbsp;&nbsp;</li> 

                    <li class='dropdown pull-left' id="notify_container" name="notify_container">
                        <div id="notify_counter"></div>
                        <div id="notify_button"><i id="notify_icon" class="fa fa-globe fa-lg"></i></div>
                        <div id="notifications">
                            <h5 class="notifica"><div style="padding-bottom:15px;"><div style="float:left">Notifiche</div>
                                <div style="float:right"><a id="all_reads" style="cursor:pointer; color:#333">Segna tutti come letti</a></div></div></h5>
                            <div id="messages" style="height:300px; clear:both">
                            </div>
                            <div class="seeAll"><a href="/notifiche/index">Mostra tutto</a></div>
                        </div>
                <!--        <a href='javascript:void(0)'><i class="fa fa-bell-o"></i></a> -->
                    </li>

                    <li class="dropdown pull-left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
            
                    <li class="dropdown pull-right">
                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $_SESSION['username']; ?> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                        <?php
                            if($_SESSION['ruolo'] != "accettazione"  && $_SESSION['ruolo'] != "cassa+backend") {
                        ?>
                            <li><a href="/accettazione/index"><i class="fa fa-id-card-o fa-fw"></i> Vai a Accettazione</a></li>
                        <?php
                            }
                            if($_SESSION['ruolo'] == "backend" || $_SESSION['ruolo'] == "cassa+backend" || $_SESSION['ruolo'] == "superlocale" || $_SESSION['ruolo'] == "super") {
                        ?>
                            <li><a href="/cassa/index"><i class="fa fa-shopping-cart fa-fw"></i> Vai a Cassa</a></li>
                        <?php
                            }

                            if($_SESSION['ruolo'] == "superlocale" || $_SESSION['ruolo'] == "super") {
                        ?>
                            <li><a href="/utenti/index"><i class="fa fa-dashboard fa-fw"></i> Vai a Admin</a></li>
                        <?php
                            }
                        ?>
                            <li class="divider"></li>
                            <li><a href="/auth/logout/<?php echo $_SESSION['username']; ?>"><i class="fa fa-power-off fa-fw"></i> Esci</a></li>
        <!--                    <li><a href="javascript:void(0)">Another action</a></li>
                            <li><a href="javascript:void(0)">Something else here</a></li>
                            <li class="divider"></li>
                            <li><a href="javascript:void(0)">Separated link</a></li> -->
                        </ul>
                    </li>
                </ul>

			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse pull-left" id="bs-example-navbar-collapse-1">
			<?php if($_SESSION['username'] != "admin") { ?>
				<ul class="nav navbar-nav pull-right">
					<li class="dropdown ">
						<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">Prodotti <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="/barcodes/index">Elenco prodotti</a></li>
							<li><a href="/barcodes/nuovo">Inserisci prodotto</a></li>
							<li class="divider"></li>
							<li><a href="/categorie/index">Categorie merceologiche</a></li>
							<li><a href="/tipologie/index">Tipologie prodotto</a></li>
							<li><a href="/offerte/index">Offerte</a></li>
					<!--
							<li class="dropdown-submenu">
								<a tabindex="-1" href="#">Categorie merceologiche </a>
                                <ul class="dropdown-menu">
									<li><a href="/categorie/index">Elenco categorie</a></li>
									<li><a href="/categorie/nuovo">Nuova categoria</a></li>
								</ul>
							</li>
							<li class="dropdown-submenu">
                                <a tabindex="-1" href="#">Tipologie prodotto </a>
                                <ul class="dropdown-menu">
									<li><a href="/tipologie/index">Elenco tipologie</a></li>
									<li><a href="/tipologie/nuovo">Nuova tipologia</a></li>
								</ul>
							</li>
					-->
							<li><a href="/limiti/index">Limiti</a></li>
		            <!--	<li class="divider"></li>
							<li><a href="/misure/index">Unit&agrave; di misura</a></li> -->
						</ul>
					</li>

					<li class="dropdown ">
						<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">Clienti <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="/famiglie/index">Elenco clienti</a></li>
							<li><a href="/famiglie/nuovo">Nuovo cliente</a></li>
						</ul>
					</li>

					<li class="dropdown ">
                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">Fornitori <b class="caret"></b></a>
                        <ul class="dropdown-menu">
							<li><a href="/fornitori/index">Elenco fornitori</a></li>
							<li><a href="/fornitori/nuovo">Nuovo fornitore</a></li>
						</ul>
					</li>

					<li class="dropdown ">
                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">Enti proponenti<b class="caret"></b></a>
                        <ul class="dropdown-menu">
							<li><a href="/enti/index">Elenco enti</a></li>
							<li><a href="/enti/nuovo">Nuovo ente</a></li>
						</ul>
					</li>

                    <?php if($_SESSION['ruolo'] == "superlocale" || $_SESSION['ruolo'] == "super") { ?>
					<li class="dropdown ">
                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">Reportistica<b class="caret"></b></a>
                        <ul class="dropdown-menu">
							<li class="dropdown-submenu">
                                <a tabindex="-1" href="#">AGEA </a>
                                <ul class="dropdown-menu">
                                    <li><a href="/agea/dichiarazione">Dichiarazione consegna</a></li>
                                    <li><a href="/agea/caricoscarico">Registro carico/scarico</a></li>
                                </ul>
                            </li>

                            <li class="dropdown-submenu">
                                <a tabindex="-1" href="#">Venduto </a>
                                <ul class="dropdown-menu">
                                    <li><a href="/report/venduto/prodotti">Per prodotti</a></li>
                                    <li><a href="/report/venduto/famiglie">Per famiglie</a></li>
                                </ul>
                            </li>

                            <li><a href="/report/donazioni">Donazioni / Acquisti</a></li>
						</ul>
					</li>
                    <?php } ?>

                    <li class="dropdown ">
                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">?</b></a>
                        <ul class="dropdown-menu">
                            <li><a href='' data-toggle="modal" data-target="#modal-credits">Crediti</a></li>
                        </ul>
                    </li>

				</ul>
			<?php } ?>

			</div><!-- /.navbar-collapse -->
		</nav>

<!-- Modal -->
<div id="modal-credits" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Crediti </span></h4>
      </div>
      <div class="modal-body">
        <p><strong>Developed by Marco Oliani</strong></p>
        <p>
            Email: <a href="mailto:uomouranio@gmail.com">uomouranio@gmail.com</a></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
      </div>
    </div>

  </div>
</div>

<script type="text/javascript">
$('a[href="' + this.location.pathname + '"]').parent().addClass('active');//get the menu item
$('a[href="' + this.location.pathname + '"]').parents('li').last().addClass('active'); //get the parent
</script>

<script type="text/javascript">
$(document).ready(function () {
	$(".dropdown, btn-group").hover(function(){
		var dropdownMenu = $(this).children(".dropdown-menu");
		if(dropdownMenu.is(":visible")){
			dropdownMenu.parent().toggleClass("open");
		}
	});
});
</script>

<script type="text/javascript">
$('body').on('hidden.bs.modal', '.modal', function () {
    $(this).removeData('bs.modal');
});
</script>

<script src="/public/js/notifiche.js"></script>
<script src="/public/js/messaggi.js"></script>
