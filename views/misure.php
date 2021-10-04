<?php include("_navbaradmin.php"); ?>

<div class="col-md-12">
    <div class="row">
        <div class="col-sm-9 col-md-9 col-lg-10 col-main">
			<div style="float:left; height:100%; width:100%">

			<!--	<div class="panel panel-info"  style="height:49%; background:#FFFFFF"> -->
				<div class="panel panel-default"  style="height:100%; background:#FFFFFF">
					<div class="panel-heading"><i class="fa fa-list" aria-hidden="true"></i> <strong>UNIT&Agrave; DI MISURA</strong></div>
						<!-- <div class="panel-body" style="height:86%; overflow-y: auto;"> -->
						<div class="panel-body" style="height:92%; overflow-y: auto;">
									<table class="table table-responsive table-hover table-striped">
										<thead>
											<th width="20%">Unit&agrave; di misura</th>
											<th width="40%">Descrizione</th>
											<th width="20%">Tipo UM</th>
											<th>&nbsp;</th>
										</thead>
										<tbody>
											<?php
												foreach($ris as $key=>$val) {
													print("<tr class='clickable-row' href='' data-url='' val_um='".$val['val_um']."'>");
													print("<td>".$val['val_um']."</td>");
													print("<td>".ucfirst($val['descrizione'])."</td>");
                                                    if($val['tipo'] == "capacita") { $tipo = "Capacit&agrave;"; }
                                                    else { $tipo = $val['tipo']; }
                                                    print('<td>'.ucwords($tipo).'</td>');
											/*		if($_SESSION['ruolo'] == "super") {
														print("<td style='text-align:right'>
																<div class=\"btn-group\">
																	<button type=\"button\" class=\"btn btn-sm btn-default dropdown-toggle\" data-toggle=\"dropdown\" aria-expanded=\"false\">
																		<i class=\"fa fa-gear\"></i> Opzioni <span class=\"caret\"></span>
																	</button>
																	<ul class=\"dropdown-menu pull-right\" role=\"menu\">
																		<li><a href='' class='modifica' form-action='/misure/modifica' id='".$val['val_um']."' name=\"".$val['val_um']."\">
																			<span  class=\"fa fa-edit\" >&nbsp;</span>Modifica</a>
																		</li>
																		<li><a href='' class=\"delete\" form-action='/misure/rimuovi' data-toggle=\"modal\" 
																			data-target=\"#myModal\" name=\"".$val['val_um']."\" id=\"".$val['val_um']."\">
																			<span class=\"fa fa-times\" >&nbsp;</span>Elimina</a>
																		</li>
																	</ul>
																</div>
															</td>");
													} 
													else { print("<td>&nbsp;</td>"); } */
                                                    print("<td>&nbsp;</td>");
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

			<form id="cerca" method="POST" action="/enti/cerca">
				<div class="form-group">
					<label for="cerca_capo">Cerca unit&agrave; di misura</label>
					<input type="text" class="form-control" id="cerca_um" name="cerca_um">
				</div>

				<button class="btn btn-info btn-sm btn-block"><i class="fa fa-search" aria-hidden="true"></i> Cerca</button>
			</form>

            <?php
                if($_SESSION['ruolo'] == "super") { 
            ?>
			<div>&nbsp;</div>
			<a href="/misure/nuovo" class="btn btn-success btn-sm btn-block"role="button"><i class="fa fa-plus" aria-hidden="true"></i> Nuova unit&agrave; di misura</a>
            <?php } ?>

        </div>
    </div>
</div>

<form id="fake-form" method="POST" action="">
    <input type="hidden" id="fake-form-misura" name="fake-form-misura" value="">
</form>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Elimina unit&agrave; di misura </span></h4>
      </div>
      <div class="modal-body">
        <p>Rimuovere l'unit&agrave; di misura selezionata?</p>
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
    $(".delete").bind('click', function() {
        var preset_name = $(this).attr('name');
        var action = $(this).attr('form-action');
        $("#fam").html(preset_name);
        $('#fake-form').attr('action', action);
        $("#fake-form-misura").val(preset_name);
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
    $(".modifica").bind('click', function() {
        var preset_name = $(this).attr('name');
        var action = $(this).attr('form-action');
        $("#fam").html(preset_name);
        $('#fake-form').attr('action', action);
        $("#fake-form-misura").val(preset_name);
        $("#fake-form").submit();
    });

    return false;
});
</script>
