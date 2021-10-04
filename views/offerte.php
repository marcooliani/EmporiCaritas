<?php include("_navbar.php"); ?>

<div class="col-md-12">
    <div class="row">
        <div class="col-sm-9 col-md-9 col-lg-10 col-main">
            <div style="float:left; height:100%; width:100%">

                <div class="panel panel-default"  style="height:39%; background:#FFFFFF">
                    <div class="panel-heading"><i class="fa fa-list" aria-hidden="true"></i> <strong>ELENCO OFFERTE ATTIVE</strong></div>
                        <div class="panel-body" style="height:82%; overflow-y: auto;">
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

                            <table id="tab_offerte" class="table table-responsive table-hover table-striped">
                                <thead>
                                  <tr>
                                    <th style="display:none">&nbsp;</th>
                                    <th style="display:none">&nbsp;</th>
                                    <th width="15%">Barcode</th>
                                    <th width="25%">Descrizione</th>
                                    <th width="25%">Tipologia</th>
                                    <th width="7%">Punti</th>
                                    <th width="10%">Inizio</th>
                                    <th width="10%">Fine</th>
                                    <th>&nbsp;</th>
                                  </tr>
                                </thead>
                                <tbody>
								<?php
									foreach($ris as $key=>$val) {
										print("<tr class='clickable-row' href='#dati_barcode' 
                                                data-url='/barcodes/ajax_barcodes' id_tipologia='".$val['id_tipologia']."'>");
										print("<td style='display:none'>".$val['offerta_a']."</td>");
										print("<td style='display:none'>".$val['offerta_da']."</td>");
										print("<td>".$val['barcode']."</td>");
										print("<td>".$val['descrizione']."</td>");
										print("<td>".ucwords($val['descrizione_tipologia'])."</td>");
										print("<td>".$val['prezzo_offerta']."</td>");
										print("<td>".date("d/m/Y", strtotime($val['offerta_da']))."</td>");
										print("<td>".date("d/m/Y", strtotime($val['offerta_a']))."</td>");
										print("<td style='text-align:right'>
                                                            <div class=\"btn-group\">
                                                                <button type=\"button\" class=\"btn btn-sm btn-default dropdown-toggle\" data-toggle=\"dropdown\" aria-expanded=\"false\">
                                                                    <i class=\"fa fa-gear\"></i> Opzioni <span class=\"caret\"></span>
                                                                </button>
                                                                <ul class=\"dropdown-menu pull-right\" role=\"menu\">
                                                                    <li><a href='' class='modifica' form-action='/offerte/modifica/".$val['id_offerta']."' 
                                                                        id='".$val['id_offerta']."' name=\"".$val['id_offerta']."\">
                                                                        <span  class='fa fa-edit fa-fw' >&nbsp;</span> Modifica</a>
                                                                    </li>
                                                                    <li><a href='' class='delete' form-action='/offerte/rimuovi/' data-toggle=\"modal\" 
                                                                        data-target=\"#myModal\" id='".$val['id_offerta']."' name=\"".$val['id_offerta']."\">
                                                                        <span  class='fa fa-times fa-fw' >&nbsp;</span> Elimina</a>
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
				<div class="panel panel-default" style="height:58%; background:#FFFFFF">
                    <div class="panel-heading"><i class="fa fa-list" aria-hidden="true"></i> <strong>ELENCO OFFERTE PASSATE (ULTIME 20)</strong></div>
                        <div class="panel-body" id="dati_barcode" style="height:88%; overflow-y: auto;">
                            <table id="tab_offerte_old" class="table table-responsive table-hover table-striped">
                                <thead>
                                  <tr>
                                    <th style="display:none">&nbsp;</th>
                                    <th style="display:none">&nbsp;</th>
                                    <th width="15%">Barcode</th>
                                    <th width="25%">Descrizione</th>
                                    <th width="25%">Tipologia</th>
                                    <th width="7%">Punti</th>
                                    <th width="10%">Inizio</th>
                                    <th width="10%">Fine</th>
                                    <th>&nbsp;</th>
                                  </tr>
                                </thead>
                                <tbody>
								<?php
									foreach($ris_old as $key=>$val2) {
										print("<tr class='clickable-row' href='#dati_barcode' 
                                                data-url='/barcodes/ajax_barcodes' id_tipologia='".$val2['id_tipologia']."'>");
										print("<td style='display:none'>".$val2['offerta_a']."</td>");
										print("<td style='display:none'>".$val2['offerta_da']."</td>");
										print("<td>".$val2['barcode']."</td>");
										print("<td>".$val2['descrizione']."</td>");
										print("<td>".ucwords($val2['descrizione_tipologia'])."</td>");
										print("<td>".$val2['prezzo_offerta']."</td>");
										print("<td>".date("d/m/Y", strtotime($val2['offerta_da']))."</td>");
										print("<td>".date("d/m/Y", strtotime($val2['offerta_a']))."</td>");
										print("<td style='text-align:right'>&nbsp;</td>");
                                        print("</tr>");
									}
								?>
								</tbody>
							</table>
                        </div>
                </div>
			</div>
		</div>
        <div class="col-sm-3 col-md-3 col-lg-2 col-right">&nbsp;

            <form id="cerca" method="POST" action="/offerte/cerca">
                <div class="form-group">
                    <label for="cerca_capo">Cerca prodotto (barcode)</label>
                    <input type="text" class="form-control" id="cerca_barcode" name="cerca_barcode" placeholder="Inserire barcode" autofocus>
                </div>
                <div class="form-group">
                    <label for="cerca_capo">Cerca prodotto (descrizione)</label>
                    <input type="text" class="form-control" id="cerca_nome" name="cerca_nome" placeholder="Inserire descrizione prodotto">
                </div>
                <div class="form-group">
					<label>Cerca per data</label>
                	<div class="input-group date" data-provide="datepicker">
                    	<input type="text" class="form-control" id="date-start" name="date-start" placeholder="Data inizio offerta">
                    	<div class="input-group-addon">
                        	<span class="fa fa-calendar"></span>
                    	</div>
                	</div>
                	<div class="input-group date" data-provide="datepicker">
                    	<input type="text" class="form-control" name="date-end" id="date-end" placeholder="Data fine offerta">
                    	<div class="input-group-addon">
                        	<span class="fa fa-calendar"></span>
                    	</div>
                	</div>
                </div>

                <button class="btn btn-info btn-sm btn-block"><i class="fa fa-search" aria-hidden="true"></i> Cerca</button>
            </form>

            <?php
                if($cerca == "1") {
            ?>
                <div>&nbsp;</div>
                <a class="btn btn-danger btn-sm btn-block" href="/offerte/index"><i class="fa fa-times" aria-hidden="true"></i> Azzera risultati ricerca</a>
            <?php
                }
            ?>


            <div>&nbsp;</div>
            <a href="/offerte/nuovo" class="btn btn-success btn-sm btn-block" role="button"><i class="fa fa-plus" aria-hidden="true"></i> Nuova offerta</a>

        </div>
    </div>
</div>

<form id="fake-form" method="POST" action="">
    <input type="hidden" id="fake-form-offerta" name="fake-form-offerta" value="">
</form>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Elimina offerta <span id="fam"></span></h4>
      </div>
      <div class="modal-body">
        <p>Rimuovere l'offerta selezionata?</p>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-danger" name="submit_delete" id="submit_delete" data-dismiss="modal">Elimina</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
      </div>
    </div>

  </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
    $('#date-start').datepicker();
    $('#date-end').datepicker();
});

</script>

<script type="text/javascript">
$(document).ready(function(){
    $('#cerca_barcode').bind('keyup input change',function(){
        if($(this).val().length > 0) {
            $('#cerca_nome').prop('readonly', true);
        }
        else {
            $('#cerca_nome').prop('readonly', false);
        }
    });

    $('#cerca_nome').bind('keyup input change',function(){
        if($(this).val().length > 0) {
            $('#cerca_barcode').prop('readonly', true);
        }
        else {
            $('#cerca_barcode').prop('readonly', false);
        }
    });
});
</script>

<script type="text/javascript">
$(document).ready(function(){
    $(".delete").bind('click', function(e) {
        e.preventDefault();
        var preset_name = $(this).attr('name');
        var action = $(this).attr('form-action');
        $('#fake-form').attr('action', action);
        $("#fake-form-offerta").val(preset_name);
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

<script type="text/javascript">
 $(document).ready(function(){
    $("#cerca_nome").autocomplete({

        source: function (request, response) {
            var re = $.ui.autocomplete.escapeRegex(request.term);
            var matcher = new RegExp("^" + re, "i");
            $.getJSON("/cassa/ajax_inserisci", function (data) {
                response($.grep(($.map(data, function (v, i) {
                    return {
                        label: v.nome,
                        value: v.nome,
                        barcode: v.barcode
                    };
                })), function (item) {
                    return matcher.test(item.value);
                }))
            });
        },
        select: function (event, ui) {
        //  alert(ui.item ? ("You picked '" + ui.item.label + "' with an ID of " + ui.item.istat) : "Nothing selected, input was " + this.value);
            $('#cerca_barcode').val(ui.item.barcode);
        //  console.log($('#comune').val + ' has faded!');
        },
        change: function (event, ui) {
        //  alert(ui.item ? ("You picked '" + ui.item.label + "' with an ID of " + ui.item.istat) : "Nothing selected, input was " + this.value);
            $('#cerca_barcode').val(ui.item.barcode);
        //  console.log($('#comune').val+'has faded!')
        }

    });
 });
</script>

<script type="text/javascript">
$(document).ready(function() {
    $('#tab_offerte').dataTable( {
        "order": [[ 0, "desc" ], [ 1, "desc" ]],
        "scrollY": "190px",
        "scrollCollapse": false,
        "paging": false,
        "filter": false,
        "info": false,
        "language": {
            "infoEmpty": "Nessun prodotto trovato",
        }
    });

    $('#tab_offerte_old').dataTable( {
        "order": [[ 0, "desc" ], [ 1, "desc" ]],
        "scrollY": "360px",
        "scrollCollapse": false,
        "paging": false,
        "filter": false,
        "info": false,
        "language": {
            "infoEmpty": "Nessun prodotto trovato",
        }
    });
});
</script>
