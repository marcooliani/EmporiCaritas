<?php include("_navbar.php"); ?>

<div class="col-md-12">
    <div class="row">
        <div class="col-sm-9 col-md-9 col-lg-10 col-main">
            <div style="float:left; height:100%; width:100%">

                <div class="panel panel-default"  style="height:49%; background:#FFFFFF">
                    <div class="panel-heading"><i class="fa fa-list" aria-hidden="true"></i> <strong>AGEA - REGISTRO DI CARICO 
							<?php echo "(".date("d/m/Y", strtotime($datastart))." - ".date("d/m/Y", strtotime($datafine)).")"; ?></strong></div>
                        <div class="panel-body" style="height:86%; overflow-y: auto;">

                            <table id="tab_carico" class="table table-responsive table-hover table-striped">
                                <thead>
                                  <tr>
                                    <th style="display:none">&nbsp;</th>
                                    <th width="15%">Data</th>
                                    <th width="15%">DDC</th>
                                    <th width="25%">Prodotto</th>
                                    <th width="10%">UM</th>
                                    <th width="10%">Quantit&agrave;</th>
                                    <th width="10%">Giacenza</th>
                                    <th>&nbsp;</th>
                                  </tr>
                                </thead>
                                <tbody>
								<?php
									foreach($ris_carico as $key=>$val) {
										print("<tr class='clickable-row' href='#dati_barcode' 
                                                data-url='/barcodes/ajax_barcodes' id_tipologia='".$val['id_tipologia']."'>");
										print("<td style='display:none'>".$val['data']."</td>");
										print("<td>".date("d/m/Y", strtotime($val['data']))."</td>");
										print("<td>".$val['ddt']."</td>");
									/*	print("<td>".ucwords($val['descrizione_categoria'])."</td>"); */
										print("<td>".$val['descrizione']."</td>");
										print("<td>".$val['um_1']."</td>");
										print("<td>".$val['quantita']."</td>");
										print("<td>".$val['giacenza']."</td>");
										print("<td style='text-align:right'>&nbsp;</td>");
                                        print("</tr>");
									}
								?>
								</tbody>
							</table>
						</div>
				</div>
				<div class="panel panel-default" style="height:49%; background:#FFFFFF">
                    <div class="panel-heading"><i class="fa fa-list" aria-hidden="true"></i> <strong>AGEA - REGISTRO DI SCARICO 
							<?php echo "(".date("d/m/Y", strtotime($datastart))." - ".date("d/m/Y", strtotime($datafine)).")"; ?></strong></div>
                        <div class="panel-body" id="dati_barcode" style="height:86%; overflow-y: auto;">
                            <table id="tab_scarico" class="table table-responsive table-hover table-striped">
                                <thead>
                                  <tr>
                                    <th style="display:none">&nbsp;</th>
                                    <th width="15%">Data</th>
                                    <th width="15%">DDC</th>
                                    <th width="25%">Prodotto</th>
                                    <th width="10%">UM</th>
                                    <th width="10%">Quantit&agrave;</th>
                                    <th width="10%">Giacenza</th>
                                    <th>&nbsp;</th>
                                  </tr>
                                </thead>
                                <tbody>
								<?php
									foreach($ris_scarico as $key=>$val2) {
										print("<tr class='clickable-row' href='#dati_barcode' 
                                                data-url='/barcodes/ajax_barcodes' id_tipologia='".$val['id_tipologia']."'>");
										print("<td style='display:none'>".$val2['data']."</td>");
										print("<td>".date("d/m/Y", strtotime($val2['data']))."</td>");
										print("<td>".$val2['ddt']."</td>");
								/*		print("<td>".ucwords($val2['descrizione_categoria'])."</td>"); */
										print("<td>".$val2['descrizione']."</td>");
										print("<td>".$val2['um_1']."</td>");
										print("<td>".$val2['quantita']."</td>");
										print("<td>".$val2['giacenza']."</td>");
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

                <div class="form-group">
            <!--    <label for="cerca_capo">Cerca prodotto (categoria)</label> -->
                    <label for="cerca_capo">Cerca prodotto</label>
                    <select class="form-control" id="cerca_categoria" name="cerca_categoria" placeholder="Seleziona" >
						<option value="" selected disabled>-- Seleziona --</option>
					<?php
						foreach($ris as $key=>$cat) {
						/*	print("<option value='".$cat['id_categoria']."'>".ucwords($cat['descrizione_categoria'])."</option>"); */
							print("<option value='".$cat['barcode']."'>".$cat['descrizione']."</option>"); 
						}
					?>
					</select>
                </div>
                <div class="form-group">
					<label>Cerca per data</label>
                	<div class="input-group date" data-provide="datepicker">
                    	<input type="text" class="form-control" id="date-start" name="date-start" placeholder="<?php echo date("d/m/Y", strtotime($datastart)); ?>">
                    	<div class="input-group-addon">
                        	<span class="fa fa-calendar"></span>
                    	</div>
                	</div>
                	<div class="input-group date" data-provide="datepicker">
                    	<input type="text" class="form-control" name="date-end" id="date-end" placeholder="<?php echo date("d/m/Y", strtotime($datafine)); ?>">
                    	<div class="input-group-addon">
                        	<span class="fa fa-calendar"></span>
                    	</div>
                	</div>
				</div>
                <div class="form-group">
					<div class="form-group">
                    	<label class="checkbox-inline"><input type="checkbox" name="carico" id="carico" value="carico" checked>Carico</label>
                    	<label class="checkbox-inline"><input type="checkbox" name="scarico" id="scarico" value="scarico" checked>Scarico</label>
                	</div>
                </div>

                <button id="ricerca" class="btn btn-success btn-sm btn-block"><i class="fa fa-search" aria-hidden="true"></i> Cerca</button>

            <?php
                if($cerca == "1") {
            ?>
                <div>&nbsp;</div>
                <a class="btn btn-danger btn-sm btn-block" href="/agea/caricoscarico"><i class="fa fa-times" aria-hidden="true"></i> Azzera risultati ricerca</a>
            <?php
                }
            ?>

            <div>&nbsp;</div>
            <a id="crea_doc" class="btn btn-info btn-block" style="white-space: normal;" tabindex="20">
                <i class="fa fa-print" aria-hidden="true"></i> Stampa registro</a>

        </div>
    </div>
</div>

<form id="fake-form" method="POST" action="">
    <input type="hidden" id="fake-form-categoria" name="fake-form-categoria" value="">
    <input type="hidden" id="fake-form-datestart" name="fake-form-datestart" value="<?php echo $datastart; ?>">
    <input type="hidden" id="fake-form-dateend" name="fake-form-dateend" value="<?php echo $datafine; ?>">
    <input type="hidden" id="fake-form-carico" name="fake-form-carico" value="">
    <input type="hidden" id="fake-form-scarico" name="fake-form-scarico" value="">
</form>

<script type="text/javascript">
$(document).ready(function(){
    $('#date-start').datepicker();
    $('#date-end').datepicker();
});

</script>

<script type="text/javascript">
$(document).ready(function(){
    $('#ricerca').bind('click', function() {
		var cat = $('#cerca_categoria').val();
		var datestart = $('#date-start').val();
		var dateend = $('#date-end').val();

		if($("#carico").is(':checked'))
			var carico = $('#carico').val();
		else var carico = '';

		if($("#scarico").is(':checked'))
			var scarico = $('#scarico').val();
		else var scarico = '';

		if(datestart != '') {
        	var d = new Date(datestart.split("/").reverse().join("-")),
        	month = '' + (d.getMonth() + 1),
        	day = '' + d.getDate(),
        	year = d.getFullYear();

        	if (month.length < 2) month = '0' + month;
        	if (day.length < 2) day = '0' + day;

        	datainizio = year + '-' + month + '-' + day;
		}
		else datainizio = '';

		if(dateend != '') {
			var dd = new Date(dateend.split("/").reverse().join("-")),
        	month = '' + (dd.getMonth() + 1),
        	day = '' + dd.getDate(),
        	year = dd.getFullYear();

        	if (month.length < 2) month = '0' + month;
        	if (day.length < 2) day = '0' + day;

			datafine = year + '-' + month + '-' + day;
		}
		else datafine = '';

		$('#fake-form-categoria').val(cat);
		$('#fake-form-datestart').val(datainizio);
		$('#fake-form-dateend').val(datafine);
		$('#fake-form-carico').val(carico);
		$('#fake-form-scarico').val(scarico);

        $('#fake-form').attr('action', '/agea/cerca');
        $('#fake-form').submit();
    });

     return false;
});
</script>

<script type="text/javascript">
$(document).ready(function(){
	$('#crea_doc').printPage({
			url: "/agea/caricoscarico_doc/" + $('#fake-form-datestart').val() + "/" + $('#fake-form-dateend').val(),
			message:"Stampa registro in corso"
	});

/*    $('#crea_doc').bind('click', function() {
        $('#fake-form').attr('action', '/agea/caricoscarico_doc');
        $('#fake-form').submit();
    }); */


     return false;
});
</script>

<script type="text/javascript">
$(document).ready(function() {
    $('#tab_carico').dataTable( {
        "order": [[ 0, "desc" ]],
        "scrollY": "280px",
        "scrollCollapse": false,
        "paging": false,
        "filter": false,
        "info": false,
        "language": {
            "infoEmpty": "Nessun prodotto trovato",
        }
    });

    $('#tab_scarico').dataTable( {
        "order": [[ 0, "desc" ]],
        "scrollY": "280px",
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
