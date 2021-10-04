<?php include("_navbar.php"); ?>

<div class="col-md-12">
    <div class="row">
        <div class="col-sm-9 col-md-9 col-lg-10 col-main">
            <div style="float:left; height:100%; width:100%;">

                <?php if($single == 1) { $val_single = $ris_single->fetch(); } ?>
                <div class="panel panel-default"  style="height:100%; background:#FFFFFF">
                    <div class="panel-heading"><i class="fa fa-gift" aria-hidden="true"></i> <strong>NUOVA OFFERTA</strong> 
                            <?php if($single == 1) { echo " - ".$barcode; } ?></div>
                        <div class="panel-body" style="height:93%; overflow-y:auto; ">

                            <?php if(isset($insertok) && $insertok == 1) { ?>
                                <div class="alert alert-success">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong>Inserimento riuscito</strong>
                                </div>
                            <?php }
                                  else if(isset($insertok) && $insertok == 0) {
                            ?>
                                <div class="alert alert-danger">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong>Inserimento fallito</strong>
                                </div>
                            <?php
                                  }
                            ?>
							<div style="font-size:12px; padding-bottom:10px;"><i>(*) = Campi obbligatori</i></div>


                            <div class="well well-sm">
                              <div class="row" style="overflow:visible">
                                <div class="col-sm-3 col-md-3 col-lg-3">
                                    <div class="form-group">
										<label>Categoria *</label><br>
                                        <?php if($single == 1) { ?> 
                                            <input type="hidden" name="categoria" id="categoria" value="<?php echo $val_single['id_categoria']; ?>">
                                            <?php echo ucwords($val_single['descrizione_categoria']); ?>
            
                                        <?php } else { ?>
        								<select id="categoria" name="categoria" class="form-control">
                							<option value="" selected disabled> -- Seleziona -- </option>
											<?php
												foreach($ris as $key=>$val) {
													print("<option value='".$val['id_categoria']."'>".ucwords($val['descrizione_categoria'])."</option>");
												}
											?>
            							</select>
                                        <?php } ?>
                                    </div>
                                </div> <!-- col -->

                                <div class="col-sm-3 col-md-3 col-lg-3">
                                    <div class="form-group">
										<label>Tipologia *</label><br>
                                        <?php if($single == 1) { ?>
                                            <input type="hidden" name="tipologia" id="tipologia" value="<?php echo $val_single['tipologia']; ?>">
                                            <?php echo ucwords($val_single['descrizione_tipologia']); ?>

                                        <?php } else { ?>
        								<select id="tipologia" name="tipologia" class="form-control" >
                							<option value="" selected disabled> --- </option>
            							</select>
                                        <?php } ?>
                                    </div>
                                </div> <!-- col -->

                    <!--            <div class="col-sm-3 col-md-3 col-lg-3">
                                    <div class="form-group">
										<label>Prodotto</label><br>
        								<select id="barcode" name="barcode" class="form-control" disabled>
											<option value=""> - </option>
            							</select>
                                    </div>
                                </div> --> <!-- col -->

							  </div> <!-- row -->
							</div> <!-- well -->

							<form method="POST" action="/offerte/inserisci">
							  <div id="barcode">
                                <?php 
                                    if($single == 1) { 
                                        print("<table id='tab_barcodes_offerte' class='table table-responsive table-striped'>
                                                <thead>
                                                    <tr>
                                                        <th width='5%'>&nbsp;</th>
                                                        <th width='25%'>Prodotto</th>
                                                        <th width='15%'>Barcode</th>
                                                        <th width='5%'>Prezzo</th>
                                                        <th width='7%'>Prezzo offerta</th>
                                                        <th width='10%'>Valida da</th>
                                                        <th width='10%'>Valida a</th>
                                                    </tr>
                                                </thead>
                                             <tbody>");

                                        $riga = 0;
                                        print("<tr>
                                                <td><input type='checkbox' name='seleziona[]' value='".$riga."'></td>
                                                <td>".$val_single['descrizione']."</td>
                                                <td>".$barcode."<input type='hidden' name='barcode[]' id='".$barcode."' value='".$barcode."'>
                                                        <input type='hidden' name='tipologia[]' value='".$val_single['tipologia']."'>
                                                </td>
                                                <td>".$val_single['punti']."<input type='hidden' name='prezzo_pieno[]' value='".$val_single['punti']."'></td>
                                                <td><input type='text' class='form-control' name='prezzo_offerta[]' id='".$val_single['barcode']."' value=''></td>
                                                <td><div class='input-group date' data-provide='datepicker'>
                                                            <input type='text' class='form-control' id='valida_da' name='valida_da[]' tabindex='8' placeholder='Es. 01/01/1970'>
                                                            <div class='input-group-addon'>
                                                                <span class='fa fa-calendar'></span>
                                                            </div>
                                                        </div></td>
                                                <td><div class='input-group date' data-provide='datepicker'>
                                                            <input type='text' class='form-control' id='valida_a' name='valida_a[]' tabindex='8' placeholder='Es. 01/01/1970'>
                                                            <div class='input-group-addon'>
                                                                <span class='fa fa-calendar'></span>
                                                            </div>
                                                        </div></td>
                                                </tr>");

                                        print("</tbody></table>");
                                    
                                    }
                                ?>
							  </div>
                        </div>
                </div>
            </div>

        </div>
        <div class="col-sm-3 col-md-3 col-lg-2 col-right">&nbsp;

            <div>&nbsp;</div>
            <button type="submit" id="#submit" class="btn btn-success btn-sm btn-block"><i class="fa fa-plus" aria-hidden="true"></i> Inserisci</button>
            <button type="reset" class="btn btn-danger btn-sm btn-block"><i class="fa fa-times" aria-hidden="true"></i> Reset</button>
            </form>

            <div>&nbsp;</div>
            <div>&nbsp;</div>
            <a class="btn btn-info btn-sm btn-block" href="/offerte/index"><i class="fa fa-list" aria-hidden="true"></i> Torna all'elenco</a>

        </div>
    </div>
</div>

<!-- Modal ALERT!!-->
<div id="modalAlert" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Campo mancante</h4>
      </div>
      <div class="modal-body">
        <p>Seleziona almeno un prodotto!</p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-danger" name="ok" id="ok" data-dismiss="modal">Esci</button>
      </div>
    </div>

  </div>
</div>
<!-- Modal ALERT end -->

<script type="text/javascript">
$(document).ready(function() {
    $('#categoria').change(function(){
    var id_cat = $('select#categoria option:selected').attr('value');

    $.ajax({
        type: "POST",
        url: "/offerte/ajax_tipologie",
        data: {'id': id_cat},
        dataType: "html",
        success: function(data) {
            $("select#tipologia").html(data);
            $("select#barcode").html("<option value=''> --- </option>");
		/*	$('#barcode').multiselect('destroy'); */
        }
 
    });

    return false;
    });
});
</script>

<script type="text/javascript">
$(document).ready(function() {
    $('#tipologia').change(function(){
    var id_tip = $('select#tipologia option:selected').attr('value');
    var punti = $('select#tipologia option:selected').attr('punti');
	
    $.ajax({
        type: "POST",
        url: "/offerte/ajax_barcode",
        data: {'id': id_tip, 'punti':punti },
        dataType: "html",
        success: function(data) {
            $("#barcode").html(data);

            $('#tab_barcodes_offerte').dataTable({
                        "scrollY": "570px",
                        "scrollCollapse": false,
                        "paging": false,
                        "filter": false,
                        "info": false,
                        "language": {
                            "infoEmpty": "Nessun prodotto trovato",
                        }
                })
        }
 
    });

    return false;
    });
});
</script>

<script type="text/javascript">
$(document).ready(function(){
    $('#valida_da').datepicker();
    $('#valida_a').datepicker();
});

</script>

<script>
$(document).ready(function(){
	$('form').submit(function(event){
    	if(($("input[name*='seleziona']:checked").length) <= 0) {
        	$('#modalAlert').modal('show');
			return false;
    	}	

		else {
			var index = 0;
			var error = 0;
    		$('input[name*="seleziona[]"]').each(function() {
        		if (this.checked) {
            		var prezzo_pieno = $('input[name*="prezzo_pieno[]"]').eq(index).val();
            		var prezzo_offerta = $('input[name*="prezzo_offerta[]"]').eq(index).val();
					var valida_da = $('input[name*="valida_da[]"]').eq(index).val();
					var valida_a = $('input[name*="valida_a[]"]').eq(index).val();

            		if(prezzo_offerta == "") {
                		$('input[name*="prezzo_offerta[]"]').eq(index).addClass('input-error');
						error = 1;
					}
					else {
						if(prezzo_offerta >= prezzo_pieno) {
							$('input[name*="prezzo_offerta[]"]').eq(index).addClass('input-error');
                        	error = 1;
						}
						else {
							var intRegex = /[0-9]+$/;
							if(!prezzo_offerta.match(intRegex)) {
								$('input[name*="prezzo_offerta[]"]').eq(index).addClass('input-error');
                                error = 1;
							}
							else {
								$('input[name*="prezzo_offerta[]"]').eq(index).removeClass('input-error');
                                error = 0;
							}
						}
					}

            		if(valida_da == "") {
                		$('input[name*="valida_da[]"]').eq(index).addClass('input-error');
						error = 1;
					}
					else {
						$('input[name*="valida_da[]"]').eq(index).removeClass('input-error');
                        error = 0;
					}

            		if(valida_a == "") {
                		$('input[name*="valida_a[]"]').eq(index).addClass('input-error');
						error = 1;
					}
					else {
						$('input[name*="valida_a[]"]').eq(index).removeClass('input-error');
                        error = 0;
					}
        		}

				else {
					$('input[name*="prezzo_offerta[]"]').eq(index).removeClass('input-error');
					$('input[name*="valida_da[]"]').eq(index).removeClass('input-error');
					$('input[name*="valida_a[]"]').eq(index).removeClass('input-error');
				}

        		index++;
    		});

			if(error == 1) {return false;}
		}
    	return;
	});
});
</script>
