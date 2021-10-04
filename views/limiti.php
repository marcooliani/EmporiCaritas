<?php include("_navbar.php"); ?>

<div class="col-md-12">
    <div class="row">
        <div class="col-sm-9 col-md-9 col-lg-10 col-main">
			<div style="float:left; height:100%; width:100%">

				<div class="panel panel-default"  style="height:100%; background:#FFFFFF">
					<div class="panel-heading"><i class="fa fa-list" aria-hidden="true"></i> <strong>LIMITI</strong></div>
						<div class="panel-body" style="height:93%; overflow-y: auto;">					
						<?php
                          if($ris->rowCount() > 0) {
							foreach($ris as $key=>$val) {					
							    print("<table class='table table-responsive' style='border: 1px solid #eee'>
											<thead>
											</thead>
											<tbody>");

								print("<tr class='' href='' data-url='' id_tipologia='".$val['categoria_merce']."'>");
								print("<th id='".$val['categoria_merce']."' class='span' colspan='10' scope='colgroup' style='background:#c0392b !important; 
										color:white !important'> ".ucwords($val['descrizione_categoria'])." </th>
										<td colspan='2' style='background:#c0392b !important; text-align:right'>
										    <div class=\"btn-group\">
                                                <button type=\"button\" class=\"btn btn-sm btn-default dropdown-toggle\" data-toggle=\"dropdown\" aria-expanded=\"false\">
                                                    <i class=\"fa fa-gear\"></i> Opzioni <span class=\"caret\"></span>
                                                </button>
                                                <ul class=\"dropdown-menu pull-right\" role=\"menu\">
                                                    <li><a href='' class='modifica' form-action='/limiti/modifica/".$val['categoria_merce']."' 
														id='".$val['categoria_merce']."' name=\"".$val['categoria_merce']."\">
                                                        <span  class='fa fa-edit fa-fw' >&nbsp;</span>Modifica</a>
                                                    </li>
                                                    <li><a href='' class=\"delete\" form-action='/limiti/rimuovi' data-toggle=\"modal\" 
                                                        data-target=\"#myModal\" name=\"".$val['categoria_merce']."\" id=\"".$val['categoria_merce']."\">
                                                        <span class='fa fa-times fa-fw' >&nbsp;</span> Elimina</a>
                                                    </li>
                                                </ul>
                                            </div>
										</td>");
									print("</tr>");
									print("<tr class='default' style='color:#000;'>
											<th style='color:#000; background:#eee !important'>Comp. nucleo famigliare</th>
											<th id='1c' scope='col' style='width:7%; color:#000; background:#eee !important; text-align:right'> 1 </th>
											<th id='2c' scope='col' style='width:7%; color:#000; background:#eee !important; text-align:right'> 2 </th>
											<th id='3c' scope='col' style='width:7%; color:#000; background:#eee !important; text-align:right'> 3 </th>
											<th id='4c' scope='col' style='width:7%; color:#000; background:#eee !important; text-align:right'> 4 </th>
											<th id='5c' scope='col' style='width:7%; color:#000; background:#eee !important; text-align:right'> 5 </th>
											<th id='6c' scope='col' style='width:7%; color:#000; background:#eee !important; text-align:right'> 6 </th>
											<th id='7c' scope='col' style='width:7%; color:#000; background:#eee !important; text-align:right'> 7 </th>
											<th id='8c' scope='col' style='width:7%; color:#000; background:#eee !important; text-align:right'> 8 </th>
											<th id='9c' scope='col' style='width:7%; color:#000; background:#eee !important; text-align:right'> 9 </th>
											<th id='10c' scope='col' style='width:7%; color:#000; background:#eee !important; text-align:right'> 10 </th>
											<th style='background:#eee !important'>&nbsp;</th>
											</tr>");
									print("<tr>");
									print("<th style='color:#000; background:#eee !important' headers='".$val['categoria_merce']."' id='lim_spesa' class='default'>Limite spesa</th>");
									print("<td style='text-align:right' headers='".$val['categoria_merce']." lim_spesa 1c'> ".$val['lim_1c_spesa']." </td>");
									print("<td style='text-align:right' headers='".$val['categoria_merce']." lim_spesa 2c'> ".$val['lim_2c_spesa']." </td>");
									print("<td style='text-align:right' headers='".$val['categoria_merce']." lim_spesa 3c'> ".$val['lim_3c_spesa']." </td>");
									print("<td style='text-align:right' headers='".$val['categoria_merce']." lim_spesa 4c'> ".$val['lim_4c_spesa']." </td>");
									print("<td style='text-align:right' headers='".$val['categoria_merce']." lim_spesa 5c'> ".$val['lim_5c_spesa']." </td>");
									print("<td style='text-align:right' headers='".$val['categoria_merce']." lim_spesa 6c'> ".$val['lim_6c_spesa']." </td>");
									print("<td style='text-align:right' headers='".$val['categoria_merce']." lim_spesa 7c'> ".$val['lim_7c_spesa']." </td>");
									print("<td style='text-align:right' headers='".$val['categoria_merce']." lim_spesa 8c'> ".$val['lim_8c_spesa']." </td>");
									print("<td style='text-align:right' headers='".$val['categoria_merce']." lim_spesa 9c'> ".$val['lim_9c_spesa']." </td>");
									print("<td style='text-align:right' headers='".$val['categoria_merce']." lim_spesa 10c'> ".$val['lim_10c_spesa']." </td>");
									print("<td style='width:10%'> &nbsp; </td>");
									print("</tr>");
									print("<tr>");
									print("<th style='color:#000; background:#eee !important' headers='".$val['categoria_merce']."' id='lim_mese' class='default'>Limite mensile</th>");
									print("<td style='text-align:right' headers='".$val['categoria_merce']." lim_mese 1c'> ".$val['lim_1c_mese']." </td>");
									print("<td style='text-align:right' headers='".$val['categoria_merce']." lim_mese 2c'> ".$val['lim_2c_mese']." </td>");
									print("<td style='text-align:right' headers='".$val['categoria_merce']." lim_mese 3c'> ".$val['lim_3c_mese']." </td>");
									print("<td style='text-align:right' headers='".$val['categoria_merce']." lim_mese 4c'> ".$val['lim_4c_mese']." </td>");
									print("<td style='text-align:right' headers='".$val['categoria_merce']." lim_mese 5c'> ".$val['lim_5c_mese']." </td>");
									print("<td style='text-align:right' headers='".$val['categoria_merce']." lim_mese 6c'> ".$val['lim_6c_mese']." </td>");
									print("<td style='text-align:right' headers='".$val['categoria_merce']." lim_mese 7c'> ".$val['lim_7c_mese']." </td>");
									print("<td style='text-align:right' headers='".$val['categoria_merce']." lim_mese 8c'> ".$val['lim_8c_mese']." </td>");
									print("<td style='text-align:right' headers='".$val['categoria_merce']." lim_mese 9c'> ".$val['lim_9c_mese']." </td>");
									print("<td style='text-align:right' headers='".$val['categoria_merce']." lim_mese 10c'> ".$val['lim_10c_mese']." </td>");
									print("<td style='text-align:right' style='width:10%'> &nbsp; </td>");
									print("</tr>");

									print("</tbody>");
									print("</table>");
								}
                              }

                              else {
                                print("<h4>Nessun limite impostato</h4>");
                              }
							?>
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

			<form id="cerca" method="POST" action="/limiti/cerca">
				<div class="form-group">
					<label for="cerca_capo">Cerca limite</label>
					<input type="text" class="form-control" id="cerca_limite" name="cerca_limite" placeholder="Inserire nome categoria">
				</div>

				<button class="btn btn-info btn-sm btn-block"><i class="fa fa-search" aria-hidden="true"></i> Cerca</button>
			</form>

			<div>&nbsp;</div>
			<a href="/limiti/nuovo" class="btn btn-success btn-sm btn-block"role="button"><i class="fa fa-plus" aria-hidden="true"></i> Nuovo limite</a>

        </div>
    </div>
</div>

<form id="fake-form" method="POST" action="">
    <input type="hidden" id="fake-form-limite" name="fake-form-limite" value="">
</form>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Elimina limite di categoria <span id="fam"></span></h4>
      </div>
      <div class="modal-body">
        <p>Rimuovere i limiti per la categoria selezionata?</p>
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
    $(".delete").bind('click', function(e) {
		e.preventDefault();
        var preset_name = $(this).attr('name');
        var action = $(this).attr('form-action');
        $("#fam").html(preset_name);
        $('#fake-form').attr('action', action);
        $("#fake-form-limite").val(preset_name);
    });

    $(".modifica").bind('click', function(e) {
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

