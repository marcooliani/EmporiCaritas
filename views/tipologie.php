<?php include("_navbar.php");

	if($_SESSION['ruolo'] == 'superlocale' || $_SESSION['ruolo'] == 'super') {
		$disabled = "";
	}
	else {
		$disabled = "disabled";
	}

?>

<div class="col-md-12">
    <div class="row">
        <div class="col-sm-9 col-md-9 col-lg-10 col-main">
			<div style="float:left; height:100%; width:100%">

			<!--	<div class="panel panel-info"  style="height:49%; background:#FFFFFF"> -->
				<div class="panel panel-default"  style="height:100%; background:#FFFFFF">
					<div class="panel-heading"><i class="fa fa-list" aria-hidden="true"></i> <strong>TIPOLOGIE DI PRODOTTO</strong></div>
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
 
							<table id="tab_tipologie" class="table table-responsive table-hover table-striped">
								<thead>
                                    <tr>
										<th>Tipologia prodotto</th>
										<th>Categoria</th>
										<th>Punti</th>
										<th>Livello riordino</th>
										<th>Livello critico</th>
										<th>Limite et&agrave;</th>
										<th>&nbsp;</th>
                                    </tr>
								</thead>
								<tbody>
									<?php
										foreach($ris as $key=>$val) {
											print("<tr class='clickable-row' href='' data-url='' id_tipologia='".$val['id_tipologia']."'>");
											print("<td>".ucfirst($val['descrizione_tipologia'])."</td>");
											print("<td>".ucfirst($val['descrizione_categoria'])."</td>");
											print("<td>".$val['punti']."</td>");
											print("<td>".$val['warning_qta_minima']."</td>");
											print("<td>".$val['danger_qta_minima']."</td>");
											print("<td>".$val['eta_min']." - ".$val['eta_max']."</td>");
											if($_SESSION['ruolo'] == "super") {
												print("<td style='text-align:right'>
															<div class=\"btn-group\">
																<button type=\"button\" class=\"btn btn-sm btn-default dropdown-toggle\" data-toggle=\"dropdown\" aria-expanded=\"false\" ".$disabled.">
																	<i class=\"fa fa-gear\"></i> Opzioni <span class=\"caret\"></span>
																</button>
																<ul class=\"dropdown-menu pull-right\" role=\"menu\">
																	<li><a href='' class='modifica' form-action='/tipologie/modifica/".$val['id_tipologia']."' data-toggle=\"modal\"
																		data-target=\"#myModal\" id='".$val['id_tipologia']."' name=\"".$val['id_tipologia']."\">
																		<span  class='fa fa-edit fa-fw' >&nbsp;</span>Modifica</a>
																	</li>
																	<li><a href='' class=\"delete\" form-action='/tipologie/rimuovi' data-toggle=\"modal\" 
																	data-target=\"#myModal\" name=\"".$val['id_tipologia']."\" id=\"".$val['id_tipologia']."\">
																	<span class='fa fa-times fa-fw' >&nbsp;</span> Elimina</a>
																	</li>
																</ul>
															</div>
														</td>");
											}
											else { print("<td>&nbsp;</td>"); }
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

			<form id="cerca" method="POST" action="/tipologie/cerca">
				<div class="form-group">
					<label for="cerca_capo">Cerca tipologia</label>
					<input type="text" class="form-control" id="cerca_tipologia" name="cerca_tipologia" placeholder="Inserire nome tipologia">
				</div>

                <div class="form-group">
                    <label for="cerca_capo">Cerca per categoria</label>
                    <select class="form-control" id="cerca_categoria" name="cerca_categoria">
                        <option value="" selected disabled> -- Seleziona -- </option>
                        <?php
                            foreach($ris_cat as $key=>$val_cat) {
                                print("<option value='".$val_cat['id_categoria']."'>".ucwords($val_cat['descrizione_categoria'])."</option>");
                            }
                        ?>
                    </select>
                </div>

				<button class="btn btn-info btn-sm btn-block"><i class="fa fa-search" aria-hidden="true"></i> Cerca</button>
			</form>

            <?php if($_SESSION['ruolo'] == "superlocale" || $_SESSION['ruolo'] == "super" || $_SESSION['ruolo'] == "backend" || $_SESSION['ruolo'] == "backend+cassa") { ?>
            <div>&nbsp;</div>
            <div>&nbsp;</div>
            <a href="/tipologie/esporta" class="btn btn-default btn-sm btn-block"><i class="fa fa-file-excel-o fa-lg" aria-hidden="true"></i> Esporta giacenze</a>
            <?php } ?>

			<div>&nbsp;</div>
			<?php if($_SESSION['ruolo'] == "super") { ?>
			<a href="/tipologie/nuovo" class="btn btn-success btn-sm btn-block" role="button" <?php echo $disabled; ?>><i class="fa fa-plus" aria-hidden="true"></i> Nuova tipologia</a>
			<?php } ?>
			

        </div>
    </div>
</div>

<form id="fake-form" method="POST" action="">
    <input type="hidden" id="fake-form-tipologia" name="fake-form-tipologia" value="">
</form>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Elimina tipologia </span></h4>
      </div>
      <div class="modal-body">
        <h1 style="text-align:center"><span id="msg-titolo" class="label label-danger"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ATTENZIONE!</span></h1><br>
        <p class="msg-warning">La rimozione di una tipologia pu&ograve; creare incongruenze nei database locali
            dei prodotti o eliminare i prodotti stessi!<br> 
            Procedere solo se si &egrave; sicuri di cosa si sta facendo!</p>
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
    $('.delete').bind('click', function(e) {
		e.preventDefault();
        var preset_name = $(this).attr('name');
        var action = $(this).attr('form-action');
        $("#fam").html(preset_name);
        $('#fake-form').attr('action', action);
        $("#fake-form-tipologia").val(preset_name);
        $('.modal-title').html('Elimina tipologia');
        $('#msg-titolo').removeClass('label-warning').addClass('label-danger');
        $('.msg-warning').html("La rimozione di una tipologia pu&ograve; creare incongruenze nei database locali " + 
            "dei prodotti o eliminare i prodotti stessi!<br>" +
            "Procedere solo se si &egrave; sicuri di cosa si sta facendo!");
        $("#submit_delete").removeClass('btn-warning').addClass('btn-danger');
        $("#submit_delete").html('Elimina');
    });

    $('.modifica').bind('click', function(e) {
		e.preventDefault();
        var action = $(this).attr('form-action');
        $('#fake-form').attr('action', action);
        $('.modal-title').html('Modifica tipologia');
        $('#msg-titolo').removeClass('label-danger').addClass('label-warning');
        $('.msg-warning').html("La modifica di una tipologia pu&ograve; creare incongruenze nei database locali " + 
                                "dei prodotti!<br>" + 
                                "Procedere solo se si &egrave; sicuri di cosa si sta facendo!");
        $("#submit_delete").removeClass('btn-danger').addClass('btn-warning');
        $("#submit_delete").html('Modifica');
//        $("#fake-form").submit();
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
    return false;
});
</script>

<script type="text/javascript">
$(document).ready(function() {
    $('#tab_tipologie').dataTable( {
        "order": [[ 0, "asc" ], [ 1, "asc" ]],
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
