<?php include("_navbarcassa.php"); ?>

<div class="col-md-12">
    <div class="row">
        <div class="col-sm-9 col-md-9 col-lg-10 col-main">
			<div style="float:left; height:100%; width:100%;">

				<div class="panel panel-default"  style="height:58%; background:#FFFFFF">
					<div class="panel-heading"><i class="fa fa-barcode" aria-hidden="true"></i> <strong>STORICO SCONTRINI <?php if(isset($_SESSION['tessera'])) echo "- ".$cliente; ?></strong></div>
						<div class="panel-body" style="height:86%; overflow-y:auto; ">
							<table id="tab_scontrini" class="table table-responsive table-hover table-striped">
                                <thead>
                                  <tr>
                                    <th style="display:none;">&nbsp;</th>
                                    <th>ID Scontrino</th>
                                    <th>Data</th>
                                    <th>Cliente</th>
                                    <th>Cod. Fiscale</th>
                                    <th>Num. Articoli</th>
                                    <th>Tot. Spesa</th>
                                    <th>Note</th>
                                    <th>&nbsp;</th>
                                  </tr>
                                </thead>
                                <tbody>
									<?php
									//	if($ris->rowCount() != 0) {
											foreach($ris as $key=>$val) {
												print("<tr class='clickable-row' href='#spesa' data-url='/scontrini/ajax_spesa' scontrino='".$val['id_scontrino']."'>");
												print("<td style='display:none'>".$val['data']."</td>");
												print("<td>".$val['id_scontrino']."</td>");
												print("<td>".date("d/m/Y H:i", strtotime($val['data']))."</td>");
												print("<td>".ucwords($val['cognome'])." ".ucwords($val['nome'])."</td>");
												print("<td>".$val['codice_fiscale']."</td>");
												print("<td>".$val['num_articoli']."</td>");
												print("<td>".$val['totale_punti']."</td>");
                                                if(empty($val['note'])) {
												    print("<td>&nbsp;</td>");
                                                }
                                                else {
                                                    print("<td><a class='note' style='color:inherit' data-toggle='tooltip' data-placement='bottom' id='note' name='note' scontrino='".$val['id_scontrino']."'>
                                                            <i class='fa fa-sticky-note-o' style='cursor:pointer' aria-hidden='true'></i> </a></td>");
                                                }
												print("<td style='text-align:right'><div class=\"btn-group\">
														<button type=\"button\" class=\"btn btn-sm btn-default dropdown-toggle\" data-toggle=\"dropdown\" aria-expanded=\"false\">
                                                        <i class=\"fa fa-gear\"></i> Opzioni <span class=\"caret\"></span>
                                                    </button>
                                                    	<ul class=\"dropdown-menu pull-right\" role=\"menu\">
															<li><a href='' class='stampastorico' form-action='/scontrini/stampastorico/".$val['id_scontrino']."'  
                                                            name=\"".$val['id_scontrino']."\" id=\"".$val['id_scontrino']."\">
                                                            <span class='fa fa-print fa-fw' >&nbsp;</span> Stampa</a>
                                                        	</li>
															<li><a href='' class=\"delete\" form-action='/scontrini/annulla' data-toggle=\"modal\" 
                                                            data-target=\"#myModal\" name=\"".$val['id_scontrino']."\" id=\"".$val['id_scontrino']."\">
                                                            <span class='fa fa-times fa-fw' >&nbsp;</span> Annulla scontrino</a>
                                                        	</li>
														</ul>
													</div>
													</td>");
												print("</tr>");
											}
									//	}
									?>
								</tbody>
							</table>

						</div>
				</div>
				<div class="panel panel-default"  style="height:39%; background:#FFFFFF">
					<div class="panel-heading"><i class="fa fa-barcode" aria-hidden="true"></i> <strong>DETTAGLIO SINGOLA SPESA</strong></div>
						<div class="panel-body" id="spesa" style="height:86%; overflow-y:auto; ">

						</div>
				</div>

			</div>

        </div>
        <div class="col-sm-3 col-md-3 col-lg-2 col-right">&nbsp;

			<form method="POST" action="/scontrini/cerca">
			<div class="form-group">
				<label>Cerca per intervallo di date</label>
				<div class="input-group date" data-provide="datepicker">
					<input type="text" class="form-control" id="date-start" name="date-start" placeholder="Data inizio intervallo">
					<div class="input-group-addon">
						<span class="fa fa-calendar"></span>
					</div>
				</div>
				<div class="input-group date" data-provide="datepicker">
					<input type="text" class="form-control" name="date-end" id="date-end" placeholder="Data fine intervallo">
					<div class="input-group-addon">
						<span class="fa fa-calendar"></span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label>Cerca per famiglia</label>
				<input class="form-control" type="text" name="famiglia" id="famiglia" placeholder="Inserire nome famiglia">
				<input class="form-control" type="hidden" name="tessera_famiglia" id="tessera_famiglia">
			</div>

			<button type="submit" class="btn btn-info btn-sm btn-block" id="cerca_scontrini"><i class="fa fa-search" aria-hidden="true"></i> Cerca</button>
			</form>

            <?php if(isset($_SESSION['tessera'])) {?>
                <div>&nbsp;</div>
                <div>&nbsp;</div>
                <a class="btn btn-danger btn-sm btn-block" href="/cassa/nuovo_conto"><i class="fa fa-shopping-cart fa-lg" aria-hidden="true"></i> Torna alla cassa</a>
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
        <h4 class="modal-title"><span id="titolo_modal">Annulla scontrino</span></h4>
      </div>
      <div class="modal-body">
        <p><span id="testo_modal">Annullare lo scontrino selezionato? <br>L'operazione restituir&agrave; i punti spesi dal cliente e ripristiner&agrave; le scorte a magazzino</span></p>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-danger" name="submit_delete" id="submit_delete" data-dismiss="modal"><span id="bottone">Procedi</span></button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Esci</button>
      </div>
    </div>

  </div>
</div>

<form id="fake-form" method="POST" action="">
    <input type="hidden" id="fake-form-tessera" name="fake-form-tessera" value="">
</form>

<?php if(!isset($_SESSION['tessera']) || empty($_SESSION['tessera'])) { ?> 
<script type="text/javascript">
$('.stampascontrino').removeAttr('href');
$('.stampascontrino').css('color', '#999999');
$('.ristampascontrino').removeAttr('href');
$('.ristampascontrino').css('color', '#999999');
$('.resetcarrello').removeAttr('href');
$('.resetcarrello').css('color', '#999999');
$('.annullaedesci').removeAttr('href');
$('.annullaedesci').css('color', '#999999');
</script>
<?php } ?>

<script type="text/javascript">
$(document).ready(function(){
    $('.note').tooltip({
        items:'#note',
        content: function(callback) {
            var scontrino = $(this).attr('scontrino');
            $.get('/scontrini/ajax_note', {id : scontrino}, function(res) {
                callback(res);
            });
                        
            return 'loading...';
        }
    });

     return false;
});
</script>

<script type="text/javascript">
$(document).ready(function(){
	$('#date-start').datepicker();
	$('#date-end').datepicker();
});

</script>

<script type="text/javascript">
$(document).ready(function(){
	$('.stampastorico').printPage({
		attr: "form-action",
		message: "Stampa scontrino in corso"
	});
});
</script>

<script type="text/javascript">
$('body').on('hidden.bs.modal', '.modal', function () {
    $(this).removeData('bs.modal');
});
</script>

<script type="text/javascript">
 $(document).ready(function(){
    $("#famiglia").autocomplete({

        source: function (request, response) {
            var re = $.ui.autocomplete.escapeRegex(request.term);
            var matcher = new RegExp("^" + re, "i");
            $.getJSON("/scontrini/ajax_cerca", function (data) {
                response($.grep(($.map(data, function (v, i) {
                    return {
                        label: v.nome,
                        value: v.nome,
                        cf: v.codice_fiscale
                    };
                })), function (item) {
                    return matcher.test(item.value);
                }))
            });
        },
        select: function (event, ui) {
        //  alert(ui.item ? ("You picked '" + ui.item.label + "' with an ID of " + ui.item.istat) : "Nothing selected, input was " + this.value);
            $('#tessera_famiglia').val(ui.item.cf);
        //  console.log($('#comune').val + ' has faded!');
        },
        change: function (event, ui) {
        //  alert(ui.item ? ("You picked '" + ui.item.label + "' with an ID of " + ui.item.istat) : "Nothing selected, input was " + this.value);
            $('#tessera_famiglia').val(ui.item.cf);
        //  console.log($('#comune').val+'has faded!')
        }

    });
 });
</script>

<script type="text/javascript">
$(document).ready(function(){
    $('.clickable-row').click(function(e){
        e.preventDefault();

        $(this).addClass('highlight').siblings().removeClass('highlight');

        var lUrl = $(this).attr("data-url");
        var layer = $(this).attr("href");
        var scontrino = $(this).attr("scontrino");

        $.ajax({
            url: lUrl,
            dataType: "json",
            data: "id=" + scontrino,
            cache: false,
            success: function(message) {
                $(layer).html(message.responseText);

                $('#tab_spesa').dataTable( {
                    "order": [[ 1, "asc" ]],
                    "scrollY": "200px",
                    "scrollCollapse": false,
                    "paging": false,
                    "filter": false,
                    "info": false,
                    "language": {
                        "infoEmpty": "Nessun prodotto trovato",
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
    $(".nuovaoperazione").bind('click', function() {
        var action = $(this).attr('form-action');
        var titleModal = "Nuova operazione di cassa";
        var textMessage = "<p>Uscire dall'operazione corrente e tornare alla schermata iniziale?</p>"

        $('#fake-form').attr('action', action);
        $('.modal-title').html(titleModal);
        $('.modal-body').html(textMessage);
    });

    // Esecuzione della conferma nel modal
    $("#submit_delete").bind('click', function() {
        $("#fake-form").submit();
    });

    return false;
});
</script>

<script type="text/javascript">
$(document).ready(function() {
    $('#tab_scontrini').dataTable( {
        "order": [[ 0, "desc" ]],
        "scrollY": "350px",
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
