<?php include("_navbar.php"); ?>

<div class="col-md-12">
    <div class="row">
        <div class="col-sm-9 col-md-9 col-lg-10 col-main">
			<div style="float:left; height:100%; width:100%">

			<!--	<div class="panel panel-info"  style="height:49%; background:#FFFFFF"> -->
				<div class="panel panel-default"  style="height:100%; background:#FFFFFF">
					<div class="panel-heading"><i class="fa fa-list" aria-hidden="true"></i> <strong>ENTI</strong></div>
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

							<table id="tab_enti" class="table table-responsive table-hover table-striped">
								<thead>
                                  <tr>
									<th>Nome ente</th>
									<th>Inserito il</th>
									<th>&nbsp;</th>
                                  </tr>
								</thead>
								<tbody>
								<?php
									foreach($ris as $key=>$val) {
										print("<tr class='clickable-row' href='' data-url='' id_tipologia='".$val['id_ente']."'>");
										print("<td>".ucwords($val['ragione_sociale'])."</td>");
										print("<td>".date("d/m/Y", strtotime($val['inserito_il']))."</td>");
										print("<td style='text-align:right'>
												<div class=\"btn-group\">
													<button type=\"button\" class=\"btn btn-sm btn-default dropdown-toggle\" data-toggle=\"dropdown\" aria-expanded=\"false\">
														<i class=\"fa fa-gear\"></i> Opzioni <span class=\"caret\"></span>
													</button>
													<ul class=\"dropdown-menu pull-right\" role=\"menu\">
														<li><a href='' class='modifica' form-action='/enti/modifica/".$val['id_ente']."' 
															id='".$val['id_ente']."' name=\"".$val['id_ente']."\">
															<span  class='fa fa-edit fa-fw' >&nbsp;</span> Modifica</a>
														</li>
														<li><a href='' class=\"delete\" form-action='/enti/rimuovi' data-toggle=\"modal\" 
															data-target=\"#myModal\" name=\"".$val['id_ente']."\" id=\"".$val['id_ente']."\">
															<span class='fa fa-times fa-fw' >&nbsp;</span> Elimina</a>
														</li>
													</ul>
												  </div>
												</td>");
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
					<label for="cerca_capo">Cerca ente</label>
					<input type="text" class="form-control" id="cerca_ente" name="cerca_ente" placeholder="Inserire nome ente">
				</div>

				<button class="btn btn-info btn-sm btn-block"><i class="fa fa-search" aria-hidden="true"></i> Cerca</button>
			</form>

            <?php
                if($cerca == "1") {
            ?>
                <div>&nbsp;</div>
                <a class="btn btn-danger btn-sm btn-block" href="/enti/index"><i class="fa fa-times" aria-hidden="true"></i> Azzera risultati ricerca</a>
            <?php
                }
            ?>

			<div>&nbsp;</div>
			<a href="/enti/nuovo" class="btn btn-success btn-sm btn-block"role="button"><i class="fa fa-plus" aria-hidden="true"></i> Nuovo ente</a>

        </div>
    </div>
</div>

<form id="fake-form" method="POST" action="">
    <input type="hidden" id="fake-form-ente" name="fake-form-ente" value="">
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
        <p>Rimuovere l'ente selezionato?</p>
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

		$(this).addClass('highlight').siblings().removeClass('highlight');
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
        $("#fake-form-ente").val(preset_name);
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
        $("#fake-form-ente").val(preset_name);
        $("#fake-form").submit();
    });

    return false;
});
</script>

<script type="text/javascript">
$(document).ready(function() {
    $('#tab_enti').dataTable( {
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
