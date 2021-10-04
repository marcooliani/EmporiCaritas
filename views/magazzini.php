<?php include("_navbaradmin.php"); ?>

<div class="col-md-12">
    <div class="row">
        <div class="col-sm-9 col-md-9 col-lg-10">
			<div style="float:left; height:99%; width:100%">

				<div class="panel panel-info"  style="height:49%; background:#FFFFFF">
					<div class="panel-heading"><i class="fa fa-list" aria-hidden="true"></i> <strong>ELENCO PRODOTTI <?php echo strtoupper($nomeemporio); ?></strong></div>
						<div class="panel-body" style="height:86%; overflow-y: auto;">

							<table class="table table-responsive table-hover table-striped">
								<thead>
									<th width="20%">Categoria</th>
									<th width="30%">Tipologia</th>
									<th width="7%">Punti</th>
									<th width="7%">Stock</th>
									<th width="12%">Livello riordino</th>
									<th width="12%">Livello critico</th>
									<th>&nbsp;</th>
								</thead>
								<tbody>
									<?php
										foreach($ris as $key=>$val) {
											if($val['stock'] <= $val['warning_qta_minima'] && $val['stock'] > $val['danger_qta_minima']) {
												$alert_stock = "<label class='label label-warning'><i class='fa fa-exclamation-triangle'></i></label>";
												$blink = "";
											}
											else if ($val['stock'] <= $val['danger_qta_minima']) {
												$alert_stock = "<label class='label label-danger'><i class='fa fa-exclamation-triangle'></i></label>";
												$blink = "blink_me";
											}
											else {
												$alert_stock = "";
												$blink = "";
											}

											print("<tr class='clickable-row' href='#dati_barcode' 
												data-url='/magazzini/ajax_barcodes' id_tipologia='".$val['id_tipologia']."' emporio='".$id_emporio."'>");
											print("<td>".ucfirst($val['descrizione_categoria'])."</td>");
											print("<td>".ucfirst($val['descrizione_tipologia'])."</td>");
											print("<td>".$val['punti']."</td>");
											print("<td><span class='".$blink."'>".$val['stock']." ".$alert_stock."</td>");
											print("<td>".$val['warning_qta_minima']."</td>");
											print("<td><span class='".$blink."'>".$val['danger_qta_minima']."</span></td>");
											print("<td style='text-align:right'>
															<div class=\"btn-group\">
																<button type=\"button\" class=\"btn btn-sm btn-default dropdown-toggle\" data-toggle=\"dropdown\" aria-expanded=\"false\">
																	<i class=\"fa fa-gear\"></i> Opzioni <span class=\"caret\"></span>
																</button>
																<ul class=\"dropdown-menu pull-right\" role=\"menu\">
																	<li><a href='' class='modifica' form-action='/tipologie/modifica/".$val['id_tipologia']."' 
																		id='".$val['id_tipologia']."' name='".$val['id_tipologia']."' emporio='".$id_emporio."'>
																		<span  class=\"fa fa-edit\" >&nbsp;</span>Modifica</a>
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
				<div class="panel panel-info" style="height:49%; background:#FFFFFF">
                    <div class="panel-heading"><i class="fa fa-barcode" aria-hidden="true"></i> <strong>DETTAGLIO PRODOTTI</strong></div>
                        <div class="panel-body" id="dati_barcode" style="height:86%; overflow-y: auto;">
                        </div>
                </div>
			</div>


        </div>
        <div class="col-sm-3 col-md-3 col-lg-2 col-right">&nbsp;

			<form id="cerca_emporio" method="POST" action="/magazzini/cerca">
				<div class="form-group">
                    <label for="cerca_capo">Seleziona emporio</label>
                    <select class="form-control" name="emporio" id="emporio">
                        <option value=""> --- </option>
                        <?php
                            foreach($ris2 as $key=>$val2) {
								if($val2['id_emporio'] == $id_emporio)
									$selected = "selected";
								else
									$selected = "";
                                print("<option value='".$val2['id_emporio']."' ".$selected.">".ucwords($val2['nome_emporio'])."</option>");
                            }
                        ?>
                    </select>
                </div>

				<button class="btn btn-success btn-sm btn-block"><i class="fa fa-arrow-right" aria-hidden="true"></i> Avanti</button>
			</form>

			<div>&nbsp;</div>
	
			<?php if($emporio_selected) { ?>
			<form id="cerca_prodotto" method="POST" action="/magazzini/cerca_prodotto">
				<input type="hidden" name="emporio" id="emporio" value="<?php echo $id_emporio; ?>">
				<div class="form-group">
					<label for="cerca_capo">Cerca prodotto (barcode)</label>
					<input type="text" class="form-control" id="cerca_barcode" name="cerca_barcode" placeholder="Inserire barcode" autofocus>
				</div>
				<div class="form-group">
					<label for="cerca_capo">Cerca prodotto (descrizione)</label>
					<input type="text" class="form-control" id="cerca_nome" name="cerca_nome" placeholder="Inserire descrizione prodotto">
				</div>
				<div class="form-group">
					<label class="checkbox-inline"><input type="checkbox" name="riordino" id="riordino" value="riordino">Riordino</label>
					<label class="checkbox-inline"><input type="checkbox" name="esaurimento" id="esaurimento" value="esaurimento">Esaurimento</label>
				</div>

				<button class="btn btn-info btn-sm btn-block"><i class="fa fa-search" aria-hidden="true"></i> Cerca</button>
			</form>

			<?php
                if($cerca == "1") {
            ?>
                <div>&nbsp;</div>
                <a class="btn btn-danger btn-sm btn-block" href="/magazzini/index"><i class="fa fa-times" aria-hidden="true"></i> Azzera risultati ricerca</a>
            <?php
                }
			}
            ?>

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
        <h4 class="modal-title">Adegua quantit&agrave; prodotto <span id="fam"></span></h4>
      </div>
      <div class="modal-body">
        <p>
		<!--	<label>Inserire nuova quantit&agrave; (< <span id="tot"></span>)</label> -->
			<label>Inserire nuova quantit&agrave; </label>
			<input class="form-control" size="5" type="text" id="nuova_qta" name="nuova_qta">
		</p>
		<p>
			<label>Motivazione </label>
            <input class="form-control" type="text" id="motivazione" name="motivazione">
		</p>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-info" name="submit_qta" id="submit_qta" data-dismiss="modal" disabled>Modifica</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
      </div>
    </div>

  </div>
</div>

<form id="fake-form" method="POST" action="">
    <input type="hidden" id="fake-form-prodotto" name="fake-form-prodotto" value="">
</form>

<script type="text/javascript">
$('body').on('hidden.bs.modal', '.modal', function () {
    $(this).removeData('bs.modal');
});
</script>

<script type="text/javascript">
$(document).ready(function(){
	$('#cerca_barcode').bind('keyup input change',function(){
    	if($(this).val().length > 0) {
			$('#cerca_nome').prop('disabled', true);
		}
		else {
			$('#cerca_nome').prop('disabled', false);
		}
	});

	$('#cerca_nome').bind('keyup input change',function(){
    	if($(this).val().length > 0) {
			$('#cerca_barcode').prop('disabled', true);
		}
		else {
			$('#cerca_barcode').prop('disabled', false);
		}
	});
});
</script>

<script type="text/javascript">
$(document).ready(function(){
    $('.clickable-row').click(function(e){
        e.preventDefault();

		$(this).addClass('highlight').siblings().removeClass('highlight');
		$('span').removeClass('blink_me');

        var lUrl = $(this).attr("data-url");
        var layer = $(this).attr("href");
		var tipologia = $(this).attr("id_tipologia");
		var emporio = $(this).attr("emporio");

        $.ajax({
            url: lUrl,
			dataType: "json",
			data: {id: tipologia, emporio: emporio},
            cache: false,
            success: function(message) {
                $(layer).html(message.responseText1);

				var barcode = '';
				var action = '';
				var qta = '';
				var bla= '';
				var motivazione= '';

				$(".adegua_qta").bind('click', function(e) {
					e.preventDefault(); 
					barcode = $(this).attr('name');
					action = $(this).attr('form-action');
					qta = $(this).attr('qta');
					$('#motivazione').val('');
					$('#nuova_qta').val('');
				});

				$('#nuova_qta').bind('keyup change input', function(e){
					e.preventDefault(); 
					bla = $('#nuova_qta').val(); // ma perchè, poi?
					var check = new RegExp('^[0-9]+$');

					if(check.test(bla)) {
						$('#submit_qta').prop('disabled', false);

					}
					else  {
						$('#submit_qta').prop({disabled: true});
					}
				});
						
				$('#submit_qta').bind('click', function(e) {
					e.preventDefault(); 
					motivazione = $('#motivazione').val();

					$.ajax({
						url: action,
						dataType: "POST",
						data: {id: barcode, old_qta: qta, qta: bla, motivazione: motivazione},
						cache: false,
						success: function(message) {
							$('#ajax_qta').html(message);
							barcode = '';
							action = '';
							qta = '';
							bla= '';
							motivazione = '';
						}
					});
				}); 

				$('.modify_barcode').bind('click', function(e) {
					e.preventDefault();
                    var action = $(this).attr('form-action');
					$('#fake-form').attr('action', action);
                    $("#fake-form").submit();
                });

            }
        });
     });

     return false;
});

</script>

<script type="text/javascript">
$(document).ready(function(){
    $(".modifica").bind('click', function(e) {
		e.preventDefault(); 
        var action = $(this).attr('form-action');
		$('#fake-form').attr('action', action);
        $("#fake-form").submit();
    });

    return false;
});
</script>

