<?php include("_navbaradmin.php"); ?>

<div class="col-md-12">
    <div class="row">
        <div class="col-sm-9 col-md-9 col-lg-10 col-main">
			<div style="float:left; height:100%; width:100%">

			<!--	<div class="panel panel-info"  style="height:49%; background:#FFFFFF"> -->
				<div class="panel panel-default"  style="height:100%; background:#FFFFFF">
					<div class="panel-heading"><i class="fa fa-list" aria-hidden="true"></i> <strong>UTENTI</strong></div>
						<!-- <div class="panel-body" style="height:86%; overflow-y: auto;"> -->
						<div class="panel-body" style="height:93%; overflow-y: auto;">
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

                            <?php if(isset($unlockok) && $unlockok == 1) { ?>
                                <div class="alert alert-success">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong>Utente sbloccato</strong>
                                </div>
                            <?php }
                                  else if(isset($unlockok) && $unlockok == 0) {
                            ?>
                                <div class="alert alert-danger">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong>Sblocco utente fallito</strong>
                                </div>
                            <?php
                                  }
                            ?>

							<table id="tab_utenti" class="table table-responsive table-hover table-striped">
								<thead>
                                    <tr>
										<th>Login</th>
										<th>Password</th>
										<th>Ruolo</th>
										<th>Cognome</th>
										<th>Nome</th>
										<th>Collegato</th>
										<th>Ultimo login</th>
										<th>&nbsp;</th>
                                    </tr>
								</thead>
								<tbody>
									<?php
										foreach($ris as $key=>$val) {
											print("<tr class='clickable-row' href='' data-url='' id_tipologia='".$val['login']."'>");
											print("<td>".$val['login']."</td>");
											print("<td>".$val['password']."</td>");
											print("<td>".$val['ruolo']."</td>");
											print("<td>".$val['cognome']."</td>");
											if(!empty($val['last_login'])) {
                                                print("<td>".$val['nome']."</td>");
                                            }
                                            else {
                                                print("<td>&nbsp;</td>");
                                            }
                                            if($val['logged']) {
                                                print("<td><i class='fa fa-lock fa-lg'></i></td>");
                                            }
                                            else {
                                                print("<td>&nbsp;</td>");
                                            }
											print("<td>".date("d/m/Y H:i:s", strtotime($val['last_login']))."</td>");
											if($val['login'] != "admin") {
												print("<td style='text-align:right'>
															<div class=\"btn-group\">
																<button type=\"button\" class=\"btn btn-sm btn-default dropdown-toggle\" data-toggle=\"dropdown\" aria-expanded=\"false\">
																	<i class=\"fa fa-gear\"></i> Opzioni <span class=\"caret\"></span>
																</button>
																<ul class=\"dropdown-menu pull-right\" role=\"menu\">");
                                                if($_SESSION['ruolo'] == "super" && $val['logged']) {
                                                    print("         <li><a href='' class='sblocca' form-action='/utenti/sblocca/".$val['login']."' 
                                                                        id='".$val['login']."' name=\"".$val['login']."\">
                                                                        <span  class='fa fa-unlock fa-fw' >&nbsp;</span>Sblocca</a>
                                                                    </li>");
                                                }
                                                
												print("				<li><a href='' class='modifica' form-action='/utenti/modifica/".$val['login']."' 
																		id='".$val['login']."' name=\"".$val['login']."\">
																		<span  class='fa fa-edit fa-fw' >&nbsp;</span>Modifica</a>
																	</li>
																	<li><a href='' class=\"delete\" form-action='/utenti/rimuovi' data-toggle=\"modal\" 
																		data-target=\"#myModal\" name=\"".$val['login']."\" id=\"".$val['login']."\">
																		<span class='fa fa-times fa-fw' >&nbsp;</span>Elimina</a>
																	</li>
																  </ul>
																</div>
															</td>");
											}
											else {
												print("<td style='text-align:right'>&nbsp;</td>");
											}
											print("</tr>");
										}
									?>
								</tbody>
							</table>
						</div>
				</div>

				<!--
				<div class="panel panel-info" style="height:49%; background:#FFFFFF">
                    <div class="panel-heading"><i class="fa fa-barcode" aria-hidden="true"></i> <strong>DETTAGLIO PRODOTTI</strong></div>
                        <div class="panel-body" id="dati_barcode" style="height:86%; overflow-y: auto;">
                        </div>
                </div>
				-->
			</div>

        </div>
        <div class="col-sm-3 col-md-3 col-lg-2 col-right">&nbsp;

			<form id="cerca" method="POST" action="/utenti/cerca">
				<div class="form-group">
					<label for="cerca_capo">Cerca utente</label>
					<input type="text" class="form-control" id="cerca_utente" name="cerca_utente" placeholder="Inserire nome utente">
				</div>

				<button class="btn btn-info btn-sm btn-block"><i class="fa fa-search" aria-hidden="true"></i> Cerca</button>
			</form>

			<div>&nbsp;</div>
			<a href="/utenti/nuovo" class="btn btn-success btn-sm btn-block"role="button"><i class="fa fa-plus" aria-hidden="true"></i> Nuovo utente</a>

        </div>
    </div>
</div>

<form id="fake-form" method="POST" action="">
    <input type="hidden" id="fake-form-utente" name="fake-form-utente" value="">
</form>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Elimina ente </span></h4>
      </div>
      <div class="modal-body">
        <p>Rimuovere l'utente selezionato?</p>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-danger" name="submit_delete" id="submit_delete" data-dismiss="modal">Elimina</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
      </div>
    </div>

  </div>
</div>

<script type="text/javascript">
$('body').on('hidden.bs.modal', '.modal', function () {
    $(this).removeData('bs.modal');
});
</script>

<script type="text/javascript">
$(document).ready(function(){
    $('.clickable-row').click(function(e){
        e.preventDefault();

//		$(this).addClass('highlight').siblings().removeClass('highlight');
     });

     return false;
});
</script>

<script type="text/javascript">
$(document).ready(function(){
    $(".delete").bind('click', function() {
        var preset_name = $(this).attr('name');
        var action = $(this).attr('form-action');
        $("#fam").html(preset_name);
        $('#fake-form').attr('action', action);
        $("#fake-form-utente").val(preset_name);
    });

    // Esecuzione della conferma nel modal
    $("#submit_delete").bind('click', function() {
        $("#fake-form").submit();
    });

    $(".modifica").bind('click', function() {
        var action = $(this).attr('form-action');
        $('#fake-form').attr('action', action);
        $("#fake-form").submit();
    });

    $(".sblocca").bind('click', function() {
        var action = $(this).attr('form-action');
        $('#fake-form').attr('action', action);
        $("#fake-form").submit();
    });

    return false;
});
</script>

<script type="text/javascript">
$(document).ready(function() {
    $('#tab_utenti').dataTable( {
        "scrollY": "710px",
        "scrollCollapse": false,
        "paging": false,
        "filter": false,
        "info": false,
        "language": {
            "infoEmpty": "Nessuna categoria trovata",
        }
    });
});
</script>
