<?php 
	include("_navbarcassa.php"); 

	if(isset($_SESSION['punti_residui']) && !empty($_SESSION['punti_residui'])) {
		if(!empty($warning_credito_esaurito) && $warning_credito_esaurito == "1") {
			$modal_msg = "CREDITO INSUFFICIENTE!";
			$modal_head_msg = "Avviso cliente";

			echo "<script type='text/javascript'>
					$(document).ready(function(){
                        var audio = new Audio('/public/files/fbsound.mp3');
                        audio.play();
						$('#modalAlert').modal('show');
					});
				</script>"; 
		}

        if(!empty($notfound) && $notfound == "1") {
            $modal_msg = "PRODOTTO NON TROVATO!";
			$modal_msg_2 = "Il prodotto non &egrave; stato trovato nel database. Il prodotto potrebbe <strong>non essere stato caricato
                a magazzino</strong>: riprovare l'inserimento utilizzando la <strong>ricerca per descrizione prodotto</strong> o contattare il gestore del
                magazzino o un amministratore!";

			$modal_head_msg = "Errore";

			echo "<script type='text/javascript'>
					$(document).ready(function(){
                        var audio = new Audio('/public/files/fbsound.mp3');
                        audio.play();
						$('#modalAlert').modal('show');
					});
				</script>"; 
        }

        if(!empty($agelimit) && $agelimit == "1") {
            $modal_msg = "PRODOTTO NON ACQUISTABILE!";
            $modal_msg_2 = "Il prodotto &egrave; soggetto a una <strong>limitazione in base all'et&agrave;</strong> di uno o pi&ugrave famigliari, 
                pertanto non pu&ograve; essere inserito nel carrello. Se si ritiene questo avviso errato, contattare il gestore del magazzino o
                un amministratore!";

            $modal_head_msg = "Errore";

            echo "<script type='text/javascript'>
                    $(document).ready(function(){
                        var audio = new Audio('/public/files/fbsound.mp3');
                        audio.play();
                        $('#modalAlert').modal('show');
                    });
                </script>";
        }

        if(!empty($errorestampa) && $errorestampa == "1") {
            $modal_msg = "ERRORE STAMPA SCONTRINO!";
            $modal_msg_2 = "Si &egrave; verificato un errore nella stampa dello scontrino. Si prega di riprovare.<br>
                Se il problema persiste, contattare un amministratore!";

            $modal_head_msg = "Stampa scontrino fallita!";

            echo "<script type='text/javascript'>
                    $(document).ready(function(){
                        var audio = new Audio('/public/files/fbsound.mp3');
                        audio.play();
                        $('#modalAlert').modal('show');
                    });
                </script>";
        }

		if(!empty($warning_limite_mese) && $warning_limite_mese == "1") {
			$modal_msg = "LIMITE MENSILE DI SPESA SUPERATO!";
			$modal_msg_2 = "Il limite mensile per la categoria <strong>".ucwords($categoria)."</strong> &egrave; stato superato!
                Impossibile inserire il prodotto!";
			$modal_head_msg = "Avviso di superamento limite!";

			echo "<script type='text/javascript'>
					$(document).ready(function(){
                        var audio = new Audio('/public/files/fbsound.mp3');
                        audio.play();
						$('#modalAlert').modal('show');
					});
				</script>"; 
		}

		else if(!empty($warning_limite_spesa) && $warning_limite_spesa == "1") {
			$modal_msg = "LIMITE DI SINGOLA SPESA SUPERATO!";
			$modal_msg_2 = "Il limite di singola spesa per la categoria <strong>".ucwords($categoria)."</strong> &egrave; stato superato! 
                    Impossibile inserire il prodotto!";
			$modal_head_msg = "Avviso di superamento limite";

        	echo "<script type='text/javascript'>
            	    $(document).ready(function(){
                        var audio = new Audio('/public/files/fbsound.mp3');
                        audio.play();
                	    $('#modalAlert').modal('show');
                	});
            	</script>";
		}
	}
?>

<div class="col-md-12">
    <div class="row">
        <div class="col-sm-3 col-md-3 col-lg-2 col-left">&nbsp;

			<div id="dati_cliente">
				<?php 
					if(isset($_SESSION['tessera_componente']) && !empty($_SESSION['tessera_componente'])) {
						$label = "label-warning";
						$tessera = $_SESSION['tessera_componente'];
					} 
					else {
						$label = "label-danger";
						$tessera = $_SESSION['tessera'];
					}
				?>
				<div class="form-group">
					<label>Tessera: </label><br><span class="label <?php echo $label; ?>" style="font-size:14px"><?php echo $tessera; ?></span>
				</div>
			
				<?php if(isset($_SESSION['tessera_componente']) && !empty($_SESSION['tessera_componente'])) { ?>
					<div class="form-group">
						<label>Capofamiglia: </label><br><span class="label label-danger" style="font-size:14px"><?php echo $_SESSION['tessera']; ?></span>
					</div>
				<?php } ?>
					<strong>Cognome e nome: </strong> <?php echo ucwords($_SESSION['cognome'])." ".ucwords($_SESSION['nome']); ?><br>
					<strong>Nucleo famigliare: </strong> <?php if($_SESSION['componenti'] != 0) echo $_SESSION['componenti']; else echo "Non specificato"; ?>
			</div>
			<div >&nbsp;</div>
			<div >&nbsp;</div>
			<div id="punti">
				<table class="table table-responsive">
					<tr>
						<td><h3>Totale spesa:</h3></td>
						<td><h3><span class="label label-info"><?php echo $_SESSION['totale_spesa']; ?></span></h3></td>
					</tr>
					<tr>
						<td><h3>Credito residuo:</h3></td>

						<?php
							$warn = (($_SESSION['punti_totali'] / 100) * 30);
							$alrt = (($_SESSION['punti_totali'] / 100) * 10);

							if($_SESSION['punti_residui'] <= $warn && $_SESSION['punti_residui'] > $alrt) {
								$label = "label-warning";
								$blink = "";
							}
							else if($_SESSION['punti_residui'] <= $alrt) {
								$label = "label-danger";
								$blink = "blink_me";
							}
							else {
								$label = "label-success";
								$blink = "";
							}
						?>
						<td><h3><span class="label <?php echo $label." ".$blink; ?>"><?php echo $_SESSION['punti_residui']; ?></span></h3></td>  
					</tr>
                    <tr>
                        <td colspan="2">
                            <h3>Note cliente:</h3>
                            <textarea id="notecliente" name="notecliente" class="form-control" rows="7" placeholder="Note aggiuntive opzionali"><?php if(!empty($_SESSION['notecliente'])) { echo $_SESSION['notecliente']; } ?></textarea>
                            <button class="btn btn-sm btn-danger" id="insertnote" name="insertnote">
                                <?php if(empty($_SESSION['notecliente'])) { ?>
                                    <i class="fa fa-plus" aria-hidden="true"></i> Aggiungi
                                <?php } else { ?>
                                    <i class="fa fa-save" aria-hidden="true"></i> Modifica
                                <?php } ?>
                            </button>
                        </td>
                    </tr> 
				</table>
			</div>

        </div>
        <div class="col-sm-6 col-md-6 col-lg-8 col-main" style="padding-left:16px">
			<div style="float:left; height:99%; width:100%">

				<div class="panel panel-default"  style="height:69%; background:#FFFFFF">
					<div class="panel-heading"><strong><i class="fa fa-shopping-cart" aria-hidden="true"></i> ELENCO ARTICOLI</strong></div>
						<div id="lista_acquisti" class="panel-body" style="height:90%; overflow-y: auto;">
									<table class="table table-responsive table-hover table-striped">
										<thead>
											<th width="55%">Articolo</th>
											<th width="10%">Prezzo</th>
											<th width="10%">Qt&agrave; (Pz)</th>
											<th width="10%">Totale</th>
											<th>&nbsp;</th>
										</thead>
										<tbody id="lista_prodotti">
											<?php
												$cart = $_SESSION['cart'];

												if (!$cart->isEmpty()) {
													foreach ($cart as $arr) {

														// Get the item object:
														$item = $arr['item'];

														if($item->getDiscounted() == 1) {
															$offerta = "<span class='label label-success offerta' href='#dati_barcode' data-url='/offerte/ajax_offerta'
																		barcode='".$item->getId()."'>
																		<a style='color:inherit; cursor:pointer; font-size:14px' data-toggle='tooltip' 
																		data-placement='bottom' title='Prodotto in offerta'><i class='fa fa-gift'></i> </span>";
														}
														else {
															$offerta = "";
														}

														// Print the item:
														print("<tr class='clickable-row' href='#dati_barcode' data-url='' barcode='".$item->getId()."'>");
														print("<td width='55%'>".$item->getName()."</td>");
														print("<td width='10%'>".$item->getPrice()." ".$offerta."</td>");
									//					print("<td width='10%'>".$arr['qty']."></td>");
														print("<td width='10%'><input id='id_".$item->getId()."' name='quantita' type='text' size='2' value='".$arr['qty']."'></td>");
														print("<td width='10%'>".$item->getPrice() * $arr['qty']."</td>");
														print("<td style='text-align:right; font-size:18px'>
																	<span class='custom_qty' form-action='/cassa/cambiaqta' 
                                                                	barcode='".$item->getId()."'><a style='color:inherit' data-toggle='tooltip' data-placement='bottom' title='Aggiorna qta'>
																	<i style='cursor:pointer' class='fa fa-refresh'></i></a></span>&nbsp;&nbsp;
																	<span class='rem_item' style='cursor:pointer' form-action='/cassa/rimuovi_articolo' 
																	barcode='".$item->getId()."'><a style='color:inherit' data-toggle='tooltip' data-placement='bottom' title='Qta -1'>
																	<i class='fa fa-minus-square'></i></a></span>&nbsp;&nbsp; 
																	<span class='add_item' style='cursor:pointer' form-action='/cassa/incrementa_articolo'
																	barcode='".$item->getId()."'><a style='color:inherit' data-toggle='tooltip' data-placement='bottom' title='Qta +1'>
																	<i class='fa fa-plus-square'></i></a></span>
																</td>");
														print("</tr>");

													} // End of foreach loop!

												} // End of IF.
											?>
										</tbody>
									</table>
						</div>
				</div>
				<div class="panel panel-default" style="height:29%; background:#FFFFFF">
                    <div class="panel-heading"><i class="fa fa-barcode" aria-hidden="true"></i> <strong>NOTE PRODOTTO</strong></div>
                        <div class="panel-body" id="dati_barcode" style="height:86%; overflow-y: auto;">
                        </div>
                </div>
			</div>


        </div>
        <div class="col-sm-3 col-md-3 col-lg-2 col-right">&nbsp;
			<form id="insert_item" name="insert_item" method="POST" action="/cassa/inserisci">
                <input type="hidden" id="notecliente_hidden" name="notecliente_hidden" value="">
				<div class="form-group">
					<label for="cerca_capo">Aggiungi articolo (codice a barre)</label>
					<input type="text" class="form-control" id="insert_barcode" name="insert_barcode" placeholder="Inserire barcode" autofocus>
				</div>

				<div class="form-group">
					<label for="cerca_capo">Aggiungi articolo (descrizione)</label>
					<input type="text" class="form-control" id="insert_descrizione" name="insert_descrizione" placeholder="Inserire descrizione">
				</div>

				<button type="submit" class="btn btn-info btn-sm btn-block" id="leggi_barcode"><i class="fa fa-cart-plus fa-lg" aria-hidden="true"></i> Aggiungi</button>
			</form>

			<div>&nbsp;</div>
            <?php if(!empty($notfound) && $notfound == "1") { ?>
            <div>
                <a class="btn btn-danger btn-sm btn-block segnala" href="" data-url="/cassa/segnala_barcode/<?php echo $barcode; ?>" data-toggle="modal" 
                    data-target="#myModal" name="prodotto_mancante" id="prodotto_mancante"><i class="fa fa-exclamation fa-lg" aria-hidden="true"></i>&nbsp; Segnala prodotto mancante</a>
            </div>

			<div>&nbsp;</div>
            <?php } ?>
        
            <?php if (!$cart->isEmpty()) { ?>
			<div>
				<a class="btn btn-danger btn-sm btn-block reset" href="" form-action="/cassa/reset_carrello" data-toggle="modal" 
                    data-target="#myModal" name="reset_carrello" id="reset_carrello"><i class="fa fa-recycle" aria-hidden="true"></i> Svuota carrello</a>
			</div>
            <?php } ?>

			<div>&nbsp;</div>
			<div>&nbsp;</div>
			<div>
				<button class="btn btn-success btn-block stampascontrino" id="stampascontrino" 
					href="/scontrini/stampa" <?php if ($cart->isEmpty()) { echo "disabled";} else { echo ""; } ?>><i class="fa fa-print" aria-hidden="true"></i> Stampa scontrino
				</button>
			</div>
		
	
<!--		<div>
				<a class="btn btn-danger btn-sm btn-block delete" href='' form-action="/cassa/index" data-toggle="modal" 
					data-target="#myModal" name="annulla_cassa" id="annulla_cassa"><i class="fa fa-sign-out" aria-hidden="true"></i> Concludi/annulla operazione</a>
			</div> -->
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
        <h4 class="modal-title">Annulla operazione di cassa</h4>
      </div>
      <div class="modal-body">
        <p>Annullare l'operazione e tornare alla schermata iniziale?</p>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-danger" name="submit_delete" id="submit_delete" data-dismiss="modal">Conferma</button>
        <button class="btn btn-default" data-dismiss="modal">Esci</button>
      </div>
    </div>

  </div>
</div>
<!-- Modal end -->

<!-- Modal ALERT!!-->
<div id="modalAlert" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?php echo $modal_head_msg; ?></h4>
      </div>
      <div class="modal-body">
        <h2 style="text-align:center"><span id="msg-titolo" class="label label-danger"><i class="fa fa-ban" aria-hidden="true"></i> <?php echo $modal_msg; ?></span></h2><br>
        <p><?php echo $modal_msg_2; ?></p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-danger" name="ok" id="ok" data-dismiss="modal">Esci</button>
      </div>
    </div>

  </div>
</div>
<!-- Modal ALERT end -->

<form id="fake-form" method="POST" action="">
</form>

<form id="fake-form-cart" method="POST" action="">
	<input type="hidden" id="fake-form-barcode" name="barcode">
	<input type="hidden" id="fake-form-qty" name="qta">
</form>

<script type="text/javascript">
$('body').on('hidden.bs.modal', '.modal', function () {
    $(this).removeData('bs.modal');
});
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $('#ok').bind('click', function() {
            $('#insert_barcode').focus();
        });
    });
</script>

<script type="text/javascript">
	$(document).ready(function(){
		$('[data-toggle="tooltip"]').tooltip(); 
	});
</script>

<script type="text/javascript">
$(document).ready(function(){
    $('.segnala').hover(function(e){
        e.preventDefault();

        var titleModal = "Segnala prodotto mancante";
        var textMessage = "<p>Segnalare il prodotto <?php echo "<strong>".$barcode."</strong>" ?> come mancante?</p>"

        $('.modal-title').html(titleModal);
        $('.modal-body').html(textMessage);
    });

    $("#submit_delete").bind('click', function() {
        var lUrl = $('.segnala').attr("data-url");

        $.ajax({
            url: lUrl,
            cache: false,
            success: function(message) {
                $('#insert_barcode').focus();
            }
        });
    });

    return false;
});
</script>

<script type="text/javascript">
$(document).ready(function(){
    $('.offerta').hover(function(e){
        e.preventDefault();

        var lUrl = $(this).attr("data-url");
        var layer = $(this).attr("href");
        var barcode = $(this).attr("barcode");

        $.ajax({
            url: lUrl,
            dataType: "json",
            data: "id=" + barcode,
            cache: false,
            success: function(message) {
                $(layer).html(message.responseText1);
			}
     	});
     }, function(e) { 
			var layer = $(this).attr("href");
			$(layer).html('');
	});

     return false;
});
</script>

<script type="text/javascript">  
  $(document).ready(function() {
	$(".stampascontrino").printPage({
		message: "Stampa scontrino in corso"
	});
	$(".ristampascontrino").printPage({
		message: "Ristampa scontrino in corso"
	});	
  });
</script>

<script type="text/javascript">
 $(document).ready(function(){
    $("#insert_descrizione").autocomplete({

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
            $('#insert_barcode').val(ui.item.barcode);
        //  console.log($('#comune').val + ' has faded!');
        },
        change: function (event, ui) {
        //  alert(ui.item ? ("You picked '" + ui.item.label + "' with an ID of " + ui.item.istat) : "Nothing selected, input was " + this.value);
            $('#insert_barcode').val(ui.item.barcode);
        //  console.log($('#comune').val+'has faded!')
        }

    });
 });
</script>

<script type="text/javascript">
$(document).ready(function(){
    $('#insertnote').bind('click', function() {
        var note = $('#notecliente').val();

        $.ajax({
            url: "/cassa/ajax_notecliente",
            dataType: "json",
            data: "notecliente=" + note,
            cache: false,
            success: function(message) {
                $("#insertnote").html(message);
                $("#insertnote").removeClass( "btn-danger" ).addClass( "btn-success" );
                $('#insert_barcode').focus();
            }
        });
    });
});
</script>

<script type="text/javascript">
$(document).ready(function(){
    $(".rem_item").bind('click', function() {
		var action = $(this).attr('form-action');
		var barcode = $(this).attr('barcode');

		$('#fake-form-cart').attr('action', action);
		$('#fake-form-barcode').val(barcode);
		$('#fake-form-cart').submit();
	});
});
</script>

<script type="text/javascript">
$(document).ready(function(){
    $(".add_item").one('click', function() {
		var action = $(this).attr('form-action');
		var barcode = $(this).attr('barcode');

		$('#fake-form-cart').attr('action', action);
		$('#fake-form-barcode').val(barcode);
		$('#fake-form-cart').submit();
	});
});
</script>

<script type="text/javascript">
$(document).ready(function(){
    $(".custom_qty").bind('click', function() {
		var intRegex = /[0-9]+$/;
		var action = $(this).attr('form-action');
		var barcode = $(this).attr('barcode');
		var qty = $('#id_' + barcode).val();

		$('#fake-form-cart').attr('action', action);
		$('#fake-form-barcode').val(barcode);
		$('#fake-form-qty').val(qty);

		if(qty.match(intRegex)) {
			$('#id_' + barcode).removeClass('input-error');
			$('#fake-form-cart').submit();
		}

		else { $('#id_' + barcode).addClass('input-error'); }
	});
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

    $(".reset").bind('click', function() {
        var action = $(this).attr('form-action');
		var titleModal = "Svuota carrello";
		var textMessage = "<p>Eliminare tutti gli articoli presenti nel carrello?</p>"

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
$(document).ready(function(){
  $('#lista_acquisti').animate({
  scrollTop: $('#lista_acquisti').get(0).scrollHeight}, 20);
});
</script>
