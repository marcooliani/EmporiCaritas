<?php include("_navbar.php"); ?>

<div class="col-sm-12 col-md-12 col-lg-12">
    <div class="row">
        <div class="col-sm-9 col-md-9 col-lg-10 col-main">
			<div style="float:left; height:100%; width:74%;">

				<div class="panel panel-default"  style="height:100%; background:#FFFFFF">
					<div class="panel-heading"><i class="fa fa-list" aria-hidden="true"></i> <strong>ELENCO FORNITORI</strong></div>
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

							<table id="tab_fornitori" class="table table-responsive table-hover table-striped">
								<thead>
									<th width="25%">Ragione Sociale</th>
									<th width="25%">Cognome</th>
									<th width="25%">Nome</th>
									<th width="10%">Donatore</th>
									<th>&nbsp;</th>
								</thead>
								<tbody>
											
									<?php 
										foreach($ris as $key=>$val) {
											if($val['donatore'] == 't')
												$donatore = "<span class='label label-success'><i class='fa fa-check'></i> </span>";
											else
												$donatore = "<span class='label label-danger'><i class='fa fa-times'></i> </span>";

											print("<tr class='clickable-row' href1='#dati_donatore' data-url='/fornitori/ajax_fornitore' id_fornitore='".$val['id_fornitore']."'>");
											print("<td>".ucwords($val['ragione_sociale'])."</td>");
											print("<td>".ucwords($val['cognome'])."</td>");
											print("<td>".ucwords($val['nome'])."</td>");
											print("<td>".$donatore."</td>");

											print("<td style='text-align:right'>
												<div class=\"btn-group\">
													<button type=\"button\" class=\"btn btn-sm btn-default dropdown-toggle\" data-toggle=\"dropdown\" aria-expanded=\"false\">
														<i class=\"fa fa-gear\"></i> Opzioni <span class=\"caret\"></span>
													</button>
													<ul class=\"dropdown-menu pull-right\" role=\"menu\">
														<li><a href='' class='modify' form-action='/fornitori/modifica/".$val['id_fornitore']."' 
																id='".$val['id_fornitore']."' name=\"".$val['id_fornitore']."\">
															<span  class='fa fa-edit fa-fw' >&nbsp;</span> Modifica</a>
														</li>
														<li><a href='' class=\"delete\" form-action='/fornitori/rimuovi' data-toggle=\"modal\" 
																data-target=\"#myModal\" name=\"".$val['id_fornitore']."\" id=\"".$val['id_fornitore']."\">
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
<!--				<div class="panel panel-info"  style="height:48%; background:#FFFFFF">
					<div class="panel-heading"><i class="fa fa-users" aria-hidden="true"></i> <strong>COMPONENTI FAMIGLIA</strong></div>
						<div class="panel-body" id="dati_membri" style="height:86%; overflow-y: auto;">
						</div>
				</div> -->
			</div>
			<div style="float:right; height:100%; width:25%; background:#FFFFFF">
				<div class="panel panel-default"  style="height:100%; background:#FFFFFF">
                    <div class="panel-heading"><i class="fa fa-truck" aria-hidden="true"></i> <strong>DATI FORNITORE</strong></div>
                        <div class="panel-body" id="dati_donatore">
                        </div>
                </div>
			</div>
        </div>
        <div class="col-sm-3 col-md-3 col-lg-2 col-right">&nbsp;

			<form id="cerca" method="POST" action="/fornitori/cerca">
				<div class="form-group">
					<label for="cerca_donatore">Cerca fornitore</label>
					<input type="text" class="form-control" id="cerca_donatore" name="cerca_donatore" placeholder="Inserire nome fornitore" autofocus>
				</div>
                <div class="form-group">
                    <table width="100%" border="0" cellpadding="5" cellspacing="5">
                        <tr>
                            <td><label class="checkbox-inline"><input type="checkbox" name="solo_fornitori" id="solo_fornitori" value="">Solo fornitori</label> </td>
                            <td><label class="checkbox-inline"><input type="checkbox" name="solo_donatori" id="solo_donatori" value="">Solo donatori</label> </td>
                        </tr>
                    </table>
                </div>

				<button type="submit" class="btn btn-info btn-sm btn-block"><i class="fa fa-search" aria-hidden="true"></i> Cerca</button>
			</form>

			<div>&nbsp;</div>
			<a href="/fornitori/nuovo" class="btn btn-success btn-sm btn-block"><i class="fa fa-plus" aria-hidden="true"></i> Nuovo fornitore</a>

            <?php
                if($cerca == "1") {
            ?>
                <div>&nbsp;</div>
                <a class="btn btn-danger btn-sm btn-block" href="/fornitori/index"><i class="fa fa-times" aria-hidden="true"></i> Azzera risultati ricerca</a>
            <?php
                }
            ?>

            <?php if($_SESSION['ruolo'] == "superlocale" || $_SESSION['ruolo'] == "super") { ?>
			<div>&nbsp;</div>
			<div>&nbsp;</div>
			<a href="/fornitori/esporta" class="btn btn-default btn-sm btn-block"><i class="fa fa-file-excel-o fa-lg" aria-hidden="true"></i> Esporta lista donatori</a>
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
        <h4 class="modal-title">Elimina fornitore </span></h4>
      </div>
      <div class="modal-body">
        <p>Rimuovere il fornitore selezionato?</p>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-danger" name="submit_delete" id="submit_delete" data-dismiss="modal">Elimina</button>
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
		var donatore = $(this).attr("id_fornitore");

        $.ajax({
            url: lUrl,
			dataType: "json",
			data: "id=" + donatore,
            cache: false,
            success: function(message) {
                $(layer).html(message.responseText);
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
    });

	$(".modify").bind('click', function(e) {
		e.preventDefault();
		var action = $(this).attr('form-action');
		$('#fake-form').attr('action', action);
        $("#fake-form").submit();
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
$(document).ready(function() {
    $('#tab_fornitori').dataTable( {
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
