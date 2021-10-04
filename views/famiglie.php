<?php include("_navbar.php"); ?>

<div class="col-sm-12 col-md-12 col-lg-12">
    <div class="row">
        <div class="col-sm-9 col-md-9 col-lg-10 col-main">
			<div style="float:left; height:100%; width:74%;">
				<div class="panel panel-default"  style="height:49%; background:#FFFFFF">
					<div class="panel-heading"><i class="fa fa-list" aria-hidden="true"></i> <strong>ELENCO CLIENTI</strong></div>
						<div class="panel-body" style="height:86%; overflow-y: auto;">
                            <?php if(isset($deleteok) && $deleteok == 1) { ?>
                                <div class="alert alert-success">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong>Cancellazione riuscita</strong>
                                </div>
                            <?php }
                                  else if(isset($deleteok) && $deleteok == 0) {
                            ?>
                                <div class="alert alert-danger">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong>Cancellazione fallita</strong>
                                </div>
                            <?php
                                  }
                            ?>
                            <?php if(isset($suspendok) && $suspendok == 1) { ?>
                                <div class="alert alert-success">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong>Utente sospeso</strong>
                                </div>
                            <?php }
                                  else if(isset($suspendok) && $suspendok == 0) {
                            ?>
                                <div class="alert alert-danger">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong>Operazione fallita</strong>
                                </div>
                            <?php
                                  }
                            ?>
                            <?php if(isset($riattivaok) && $riattivaok == 1) { ?>
                                <div class="alert alert-success">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong>Utente riabilitato</strong>
                                </div>
                            <?php }
                                  else if(isset($riattivaok) && $riattivaok == 0) {
                            ?>
                                <div class="alert alert-danger">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong>Operazione fallita</strong>
                                </div>
                            <?php
                                  }
                            ?>

							<table id="tab_famiglie" class="table table-responsive table-hover table-striped">
								<thead>
                                    <tr>
									    <th>Codice Fiscale</th>
									    <th>Cognome</th>
									    <th>Nome</th>
									    <th>Famigliari</th>
									    <th>Punti totali/residui</th>
									    <th>&nbsp;</th>
                                    </tr>
								</thead>
								<tbody>
											
									<?php 
										foreach($ris as $key=>$val) {
											if($val['sospeso'] == "t") { $sospeso = "sospeso"; }
											else { $sospeso = ""; }
                
                                            if($val['scadenza'] < date("Y-m-d")) { $scaduto = "scaduto"; }
											else { $scaduto = ""; }

											if($val['punti_residui'] == "0") { $rosso = "danger"; }
											else { $rosso = ""; }

											print("<tr class='clickable-row ".$rosso."' href1='#dati_capofamiglia' href2='#dati_membri' 
													data-url='/famiglie/ajax_capiFamiglia' tessera='".$val['codice_fiscale']."'>");
											print("<td class='".$sospeso." ".$scaduto."'>".$val['codice_fiscale']."</td>");
											print("<td class='".$sospeso." ".$scaduto."'>".ucwords($val['cognome'])."</td>");
											print("<td class='".$sospeso." ".$scaduto."'>".ucwords($val['nome'])."</td>");

											if($val['num_componenti'] != 0) {
												print("<td class='".$sospeso." ".$scaduto."'>".$val['num_componenti']."</td>");
											}
											else {
												print("<td class='".$sospeso." ".$scaduto."'>Non specificato</td>");
											}

											if($val['esenzione'] == 't') {
												print("<td class='".$sospeso." ".$scaduto."'><span class='label label-danger'>ESENTE</span></td>");
											}
											else {
												print("<td class='".$sospeso." ".$scaduto."'>".$val['punti_totali']." / ".$val['punti_residui']."</td>");
											}

											print("<td style='text-align:right'>
												<div class=\"btn-group\">
													<button type=\"button\" class=\"btn btn-sm btn-default dropdown-toggle\" data-toggle=\"dropdown\" aria-expanded=\"false\">
														<i class=\"fa fa-gear\"></i> Opzioni <span class=\"caret\"></span>
													</button>
													<ul class=\"dropdown-menu pull-right\" role=\"menu\">
														<li><a href='' class='modify' form-action='/famiglie/modifica_famiglia/".$val['codice_fiscale']."' 
															id='".$val['codice_fiscale']."' name=\"".$val['codice_fiscale']."\">
															<span  class='fa fa-edit fa-fw' >&nbsp;</span> Modifica</a>
														</li>
														<li><a href='' class='addmember' form-action='/famiglie/nuovo_componente/".$val['codice_fiscale']."' 
															name=\"".$val['codice_fiscale']."\" id=\"".$val['codice_fiscale']."\">
															<span  class='fa fa-user-plus fa-fw' >&nbsp;</span> Aggiungi famigliari</a>
														</li>");
											

											if($val['sospeso'] != 't') {
												print("<li><a href='' class=\"suspend\" form-action='/famiglie/sospendi' data-toggle=\"modal\" 
															data-target=\"#myModal\" name=\"".$val['codice_fiscale']."\" id=\"".$val['codice_fiscale']."\">
															<span class='fa fa-user-times fa-fw' >&nbsp;</span> Sospendi</a>
														</li>");
											}
											else {
												print("<li><a href='' class=\"enable\" form-action='/famiglie/abilita' data-toggle=\"modal\" 
															data-target=\"#myModal\" name=\"".$val['codice_fiscale']."\" id=\"".$val['codice_fiscale']."\">
															<span class='fa fa-user fa-fw' >&nbsp;</span> Riabilita</a>
														</li>");
											}
	
                                            if($_SESSION['ruolo'] == "superlocale" || $_SESSION['ruolo'] == "super") {
											    print("<li><a href='' class=\"delete\" form-action='/famiglie/remove_famiglia' data-toggle=\"modal\" 
															data-target=\"#myModal\" name=\"".$val['codice_fiscale']."\" id=\"".$val['codice_fiscale']."\">
															<span class='fa fa-times fa-fw' >&nbsp;</span> Elimina</a>
														</li>");
                                            }

											print("		</ul>
													</div>
												</td>");
											print("</tr>");
										}
									?>

								</tbody>
							</table>
						</div>
				</div>
				<div class="panel panel-default"  style="height:49%; background:#FFFFFF">
					<div class="panel-heading"><i class="fa fa-users" aria-hidden="true"></i> <strong>FAMIGLIARI</strong></div>
						<div class="panel-body" id="dati_membri" style="height:86%; overflow-y: auto;">
                            <?php if(isset($delete_memberok) && $delete_memberok == 1) { ?>
                                <div class="alert alert-success">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong>Cancellazione riuscita</strong>
                                </div>
                            <?php }
                                  else if(isset($delete_memberok) && $delete_memberok == 0) {
                            ?>
                                <div class="alert alert-danger">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong>Cancellazione fallita</strong>
                                </div>
                            <?php
                                  }
                            ?>
						</div>
				</div>
			</div>
			<div style="float:right; height:100%; width:25%; background:#FFFFFF">
				<div class="panel panel-default"  style="height:100%; background:#FFFFFF">
                    <div class="panel-heading"><i class="fa fa-id-card-o" aria-hidden="true"></i> <strong>DATI CLIENTE / FAMIGLIARE</strong></div>
                        <div class="panel-body" id="dati_capofamiglia" style="height:90%; overflow-y: auto;">
                        </div>
                </div>
			</div>
        </div>
        <div class="col-sm-3 col-md-3 col-lg-2 col-right">&nbsp;

			<form id="cerca" method="POST" action="/famiglie/cerca">
				<div class="form-group">
					<label for="cerca_capo">Cerca cliente</label>
					<input type="text" class="form-control" name="cerca_capo" id="cerca_capo" placeholder="Inserire codice fiscale o cognome" autofocus>
				</div>
				<div class="form-group">
					<label for="cerca_membro">Cerca famigliare</label>
					<input type="text" class="form-control" name="cerca_membro" id="cerca_membro" placeholder="Inserire codice fiscale o cognome">
				</div>

                <table border="0">
                    <tr>
                        <td><label class="checkbox-inline"><input type="checkbox" name="credito_zero" id="credito_zero" value="">Credito esaurito</label> </td>
                    </tr>
                    <tr>
                        <td><label class="checkbox-inline"><input type="checkbox" name="sospesi" id="sospesi" value="">Clienti sospesi</label> </td>
                    </tr>
                    <tr>
                        <td><label class="checkbox-inline"><input type="checkbox" name="inscadenza" id="inscadenza" value="">Tessera in scadenza</label> </td>
                    </tr> 
                    <tr>
                        <td><label class="checkbox-inline"><input type="checkbox" name="scaduti" id="scaduti" value="">Tessera scaduta</label> </td>
                    </tr>
                </table>

                <div>&nbsp;</div>

				<button type="submit" class="btn btn-info btn-sm btn-block"><i class="fa fa-search" aria-hidden="true"></i> Cerca</button>
			</form>

			<?php 
				if($cerca == "1") {
			?>
				<div>&nbsp;</div>
				<a class="btn btn-danger btn-sm btn-block" href="/famiglie/index"><i class="fa fa-times" aria-hidden="true"></i> Azzera risultati ricerca</a>
			<?php
				}
			?>
			<div>&nbsp;</div>
			<a href="/famiglie/nuovo" class="btn btn-success btn-sm btn-block"><i class="fa fa-plus" aria-hidden="true"></i> Nuova famiglia</a>
		<!--	<button class="btn btn-secondary btn-sm btn-block"><i class="fa fa-user-plus" aria-hidden="true"></i> Nuovo componente</button> -->

            <?php if($_SESSION['ruolo'] == "superlocale" || $_SESSION['ruolo'] == "super") { ?>
            <div>&nbsp;</div>
            <div>&nbsp;</div>
            <a href="/famiglie/esporta" class="btn btn-default btn-sm btn-block"><i class="fa fa-file-excel-o fa-lg" aria-hidden="true"></i> Esporta lista famiglie</a>
            <?php } ?>

        </div>
    </div>
</div>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><span id="titolo_modal">Elimina cliente</span></h4>
      </div>
      <div class="modal-body">
        <h1 style="text-align:center"><span id="msg-titolo" class="label label-danger"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ATTENZIONE!</span></h1><br>
        <p class="msg-warning"><span id="testo_modal">Rimuovere il cliente selezionato?</span></p>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-danger" name="submit_delete" id="submit_delete" data-dismiss="modal"><span id="bottone">Elimina</span></button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
      </div>
    </div>

  </div>
</div>

<!-- Modal -->
<div id="myModal_m" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Elimina famigliare <span id="mem"></span></h4>
      </div>
      <div class="modal-body">
        <p>Rimuovere il famigliare selezionato?</p>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-danger" name="submit_delete_m" id="submit_delete_m" data-dismiss="modal">Elimina</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
      </div>
    </div>

  </div>
</div>

<form id="fake-form" method="POST" action="">
    <input type="hidden" id="fake-form-tessera" name="fake-form-tessera" value="">
</form>

<script type="text/javascript">
$('body').on('hidden.bs.modal', '.modal', function () {
    $(this).removeData('bs.modal');
});
</script>

<script type="text/javascript">
$(document).ready(function(){
    $('.clickable-row').click(function(e){
        e.preventDefault();

		$(this).addClass('highlight').siblings().removeClass('highlight');

        var lUrl = $(this).attr("data-url");
        var layer = $(this).attr("href1");
        var layer2 = $(this).attr("href2");
		var tessera = $(this).attr("tessera");

        $.ajax({
            url: lUrl,
			dataType: "json",
			data: "id=" + tessera,
            cache: false,
            success: function(message) {
                $(layer).html(message.responseText1);
                $(layer2).html(message.responseText2);

				$('.clickable-row-f').click(function(e){
					e.preventDefault();
					$(this).addClass('highlight_f').siblings().removeClass('highlight_f');
        			var lUrl = $(this).attr("data-url");
        			var layer = $(this).attr("href");
					var tessera = $(this).attr("tessera_f");

        			$.ajax({
            			url: lUrl,
						dataType: "json",
						data: "id=" + tessera,
            			cache: false,
            			success: function(message) {
                			$(layer).html(message.responseText1);
            			}
        			});
				});

				$(".delete_membro").bind('click', function(e) {
					e.preventDefault(); 
					var preset_name = $(this).attr('name');
					var action = $(this).attr('form-action');
					$("#mem").html(preset_name);
					$('#fake-form').attr('action', action);
					$("#fake-form-tessera").val(preset_name);
				});

				$(".modify_membro").bind('click', function(e) {
					e.preventDefault(); 
					var preset_name = $(this).attr('name');
					var action = $(this).attr('form-action');
					$('#fake-form').attr('action', action);
					$("#fake-form").submit();
				});

				// Esecuzione della conferma nel modal
				$("#submit_delete_m").bind('click', function(e) {
					e.preventDefault(); 
					$("#fake-form").submit();
				});

                $('#tab_famigliari').dataTable( {
                    "order": [[ 1, "asc" ]],
                    "scrollY": "280px",
                    "scrollCollapse": false,
                    "paging": false,
                    "filter": false,
                    "info": false,
                    "language": {
                        "infoEmpty": "Nessun cliente trovato"
                    }
                });
				
            }
        });
     });

	return false;
});

</script>

<script type="text/javascript">
$(document).ready(function(){
	$(".delete").bind('click', function(e) {
		e.preventDefault(); 
        var preset_name = $(this).attr('name');
		var action = $(this).attr('form-action');
		$("#fam").html(preset_name);
		$('#fake-form').attr('action', action);
        $("#fake-form-tessera").val(preset_name);
        $('.modal-title').html('Elimina cliente');
        $('#msg-titolo').removeClass('label-warning').addClass('label-danger');
        $('.msg-warning').html("La rimozione di un cliente pu&ograve; portare ad incongruenze o dati mancanti " + 
                                "nella visualizzazione degli scontrini e nelle statistiche!<br>" + 
                                "Procedere solo se si &egrave; sicuri di cosa si sta facendo!");
        $("#submit_delete").removeClass('btn-warning').addClass('btn-danger');
    });

    // Esecuzione della conferma nel modal
    $("#submit_delete").bind('click', function(e) {
		e.preventDefault(); 
        $("#fake-form").submit();
    });

    return false;
});
</script>


<script type="text/javascript">
$(document).ready(function(){
	$(".modify").bind('click', function(e) {
        e.preventDefault();
		var preset_name = $(this).attr('name');
		var action = $(this).attr('form-action');

		$('#fake-form').attr('action', action);
        $("#fake-form").submit();
	});
});
</script>


<script type="text/javascript">
$(document).ready(function(){
	$(".suspend").bind('click', function(e) {
        e.preventDefault();
        var preset_name = $(this).attr('name');
		var action = $(this).attr('form-action');
		$("#fam").html(preset_name);
		$('#fake-form').attr('action', action);
        $("#fake-form-tessera").val(preset_name);

		$('#titolo_modal').html("Sospendi cliente");
		$('#testo_modal').html("Sospendere il cliente selezionato?");
		$('#bottone').html("Sospendi");
    });

    // Esecuzione della conferma nel modal
    $("#submit_delete").bind('click', function() {
        $("#fake-form").submit();
    });

    return false;
});
</script>

<script type="text/javascript">
$(document).ready(function(){
	$(".enable").bind('click', function(e) {
        e.preventDefault();
        var preset_name = $(this).attr('name');
		var action = $(this).attr('form-action');
		$("#fam").html(preset_name);
		$('#fake-form').attr('action', action);
        $("#fake-form-tessera").val(preset_name);

		$('#titolo_modal').html("Riabilita cliente");
		$('#testo_modal').html("Riabilitare il cliente selezionato?");
		$('#bottone').html("Riabilita");
    });

    // Esecuzione della conferma nel modal
    $("#submit_delete").bind('click', function() {
        $("#fake-form").submit();
    });

    return false;
});
</script>

<script type="text/javascript">
$(document).ready(function(){
    $(".addmember").bind('click', function() {
		var capofamiglia = $(this).attr('name');
		var action = $(this).attr('form-action');
		$('#fake-form').attr('action', action);
		$('#fake-form-tessera').val(capofamiglia);
		$('#fake-form').submit();
    });

    return false;
});
</script>

<script type="text/javascript">
$(document).ready(function() {
    $('#tab_famiglie').dataTable( {
        "order": [[ 1, "asc" ]],
        "scrollY": "280px",
        "scrollCollapse": false,
        "paging": false,
        "filter": false,
        "info": false,
        "language": {
            "infoEmpty": "Nessun cliente trovato",
        }
    });
});
</script>
