<?php include("_navbar.php"); ?>

<div class="col-md-12">
    <div class="row">
        <div class="col-sm-9 col-md-9 col-lg-10 col-main">
			<div style="float:left; height:100%; width:100%;">

				<div class="panel panel-default"  style="height:100%; background:#FFFFFF">
					<div class="panel-heading"><i class="fa fa-barcode" aria-hidden="true"></i> <strong>MODIFICA BARCODE (<?php echo $barcode; ?>)</strong></div>
						<div class="panel-body" style="height:93%; overflow-y:auto; ">
                            <?php if(isset($modifyok) && $modifyok == 1) { ?>
                                <div class="alert alert-success">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong>Modifica riuscita</strong>
                                </div>
                            <?php }
                                  else if(isset($modifyok) && $modifyok == 0) {
                            ?>
                                <div class="alert alert-danger">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong>Modifica fallita</strong>
                                </div>
                            <?php
                                  }
                            ?>

							<div style="font-size:12px; padding-bottom:10px;"><i>(*) = Campi obbligatori</i></div>

							<form id="modifica_prodotto" method="POST" action="/barcodes/update_barcode/<?php echo $barcode; ?>">

							<?php $val = $ris->fetch(); ?>
							<div class="well well-sm">
							  <div class="row">
								<div class="col-sm-3 col-md-3 col-lg-3">
									<div class="form-group">
										<label for="exampleInputEmail1">Barcode *</label>
										<input type="text" class="form-control" id="barcode" name="barcode" tabindex="1" value="<?php echo $val['barcode']; ?>" autofocus>
									</div>
								</div>
								<div class="col-sm-2 col-md-2 col-lg-2">
									<div class="form-group">
										<label for="exampleInputEmail1">Categoria *</label>
										<select class="form-control" name="categoria" id="categoria" tabindex="2">
											<option value=""> --- </option>
											<?php
												foreach($sql_categorie as $key=>$val2) {
													if($val['categoria'] == $val2['id_categoria'])
                                                            $selected = "selected";
                                                        else
                                                            $selected = "";
													print("<option value='".$val2['id_categoria']."' ".$selected.">".ucfirst($val2['descrizione_categoria'])."</option>");
												}
											?>
										</select>
									</div>
								</div>
								<div class="col-sm-3 col-md-3 col-lg-3">
									<div class="form-group">
										<label for="exampleInputEmail1">Tipologia *</label>
										<select class="form-control" name="id_tipologia" id="id_tipologia" tabindex="3">
											<option value="<?php echo $val['tipologia'] ?>"><?php echo ucwords($val['descrizione_tipologia']); ?> </option>
										</select>
									</div>
								</div>
								<div class="col-sm-2 col-md-2 col-lg-2">
									<div class="form-group">
										<label for="exampleInputEmail1">UM Stock *</label>
										<select class="form-control" id="um_stock" name="um_stock" tabindex="4">
											<option value="pz"> pz </option>
                                                <?php
                                                  /*  foreach($sql_um_1 as $key=>$val2) {
														if($val['um_stock'] == $val2['val_um'])
															$selected = "selected";
														else
															$selected = "";
                                                        print("<option value='".$val2['val_um']."' ".$selected.">".$val2['val_um']."</option>");
                                                    } */
                                                ?>
										</select>
									</div>
								</div>
								<div class="col-sm-2 col-md-2 col-lg-2">
									<div class="form-group">
										<label for="exampleInputEmail1">Stock </label>
										<input type="text" class="form-control" id="stock" name="stock" value="<?php echo $val['stock']; ?>"  tabindex="5" readonly>
									</div>
								</div>
							  </div>
						<!--	</div>

							<div class="well well-sm"> -->
								<div class="row">
									<div class="col-sm-8 col-md-8 col-lg-8">
										<div class="form-group">
											<label for="exampleInputEmail1">Descrizione *</label>
											<input type="text" class="form-control" id="descrizione" name="descrizione" 
												value="<?php echo ucfirst($val['descrizione']); ?>"  tabindex="6">
										</div>
									</div>
									<div class="col-sm-2 col-md-2 col-lg-2">
										<div class="form-group">
											<label for="exampleInputEmail1">Formato *</label>
											<select class="form-control" name="um1" id="um1" tabindex="7">
												<?php
													foreach($sql_um as $key=>$val2) {
														if($val['um_1'] == $val2['val_um'])
															$selected = "selected";
														else
															$selected = "";
														print("<option value='".$val2['val_um']."' ".$selected.">".$val2['val_um']."</option>");
													}
												?>
											</select>
										</div>
									</div>
									<div class="col-sm-2 col-md-2 col-lg-2">
										<div class="form-group">
											<label for="exampleInputEmail1">&nbsp;</label>
											<input type="text" class="form-control" id="contenuto_um1" name="contenuto_um1" value="<?php echo $val['contenuto_um1']; ?>" tabindex="8">
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-sm-2 col-md-2 col-lg-2">
										<div class="form-group">
											<label for="exampleInputEmail1">AGEA</label>
											<select class="form-control" name="agea" id="agea" tabindex="9">
												<option value="f" <?php if(!$val['agea'] ) echo "selected"; ?>>No</option>
												<option value="t" <?php if($val['agea'] ) echo "selected"; ?>>S&igrave;</option>
											</select>
										</div>
                                    </div>
									<div class="col-sm-2 col-md-2 col-lg-2">
										<div class="form-group">
											<label for="exampleInputEmail1">Classificato uff.</label>
											<select class="form-control" name="classificato" id="classificato" tabindex="10">
												<option value="f" <?php if(!$val['classificato'] ) echo "selected"; ?>>No</option>
												<option value="t" <?php if($val['classificato'] ) echo "selected"; ?>>S&igrave;</option>
											</select>
										</div>
                                    </div>
								</div>

							</div>

						</div>
				</div>
			</div>


        </div>
        <div class="col-sm-3 col-md-3 col-lg-2 col-right">&nbsp;

			<div>&nbsp;</div>
			<button type="submit" class="btn btn-success btn-sm btn-block" tabindex="11"><i class="fa fa-save" aria-hidden="true"></i> Salva Modifiche</button>
			<button type="reset" class="btn btn-danger btn-sm btn-block" tabindex="12"><i class="fa fa-times" aria-hidden="true"></i> Reset</button>
			</form>

            <div>&nbsp;</div>
            <div>&nbsp;</div>
            <a class="btn btn-info btn-sm btn-block" href="/barcodes/index"><i class="fa fa-list" aria-hidden="true"></i> Torna all'elenco</a>

        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
    $('#barcode').on('keypress', function(e) {
        return e.which !== 13;
    });
});
</script>

<script type="text/javascript">
$(document).ready(function(){
	$('#data_donazione').datepicker();
});
</script>

<script type="text/javascript">
$(document).ready(function() {
	$('#fornitore').change(function() {
		var f = $('select#fornitore option:selected').attr('flag_d');

		if(f != 1)
			$('#acquistato').val('t');
		else
			$('#acquistato').val('f');
	});

});
</script>

<script type="text/javascript">
$(document).ready(function() {
    $('#categoria').change(function(){
    var id_cat = $('select#categoria option:selected').attr('value');

    $.ajax({
        type: "POST",
        url: "/tipologie/ajax_tipologie",
        data: {'id': id_cat},
        dataType: "html",
        success: function(data) {
            $("select#id_tipologia").html(data);
        }
 
    });

    $.ajax({
        type: "POST",
        url: "/categorie/ajax_misure",
        data: {'id': id_cat},
        dataType: "html",
        success: function(data) {
            $("select#um1").html(data);
        }
 
    });

    return false;
    });
});
</script>

<script type="text/javascript">
    $("#barcode").autocomplete({
		//autoFocus: true,
        source: function (request, response) {
            var re = $.ui.autocomplete.escapeRegex(request.term);
            var matcher = new RegExp("^" + re, "i");
            $.getJSON("/barcodes/ajax_check_barcode", function (data) {
                response($.grep(($.map(data, function (v, i) {
                    return {
                        label: v.barcode,
                        value: v.barcode,
                        descrizione: v.nome,
						id_tipologia: v.id_tipologia,
						descrizione_tipologia: v.descrizione_tipologia,
						id_categoria: v.id_categoria,
						descrizione_categoria: v.descrizione_categoria,
						um_1: v.um_1,
						contenuto_um1: v.contenuto_um1,
						um_stock: v.um_stock,
						stock: v.stock,
						agea: v.agea,
						classificato: v.classificato,
                    };
                })), function (item) {
                    return matcher.test(item.value);
                }))
            });
        },
        select: function (event, ui) {
	//		$('#warning_doppione').html('<div style="text-align:center; width:80%; margin:auto;" class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Attenzione!</strong> Prodotto gi&agrave; presente nel database: la quantit&agrave; inserita nel campo &quot;Stock&quot; verr&agrave; sommata a quella gi&agrave; esistente</div><div class="row">&nbsp;</div>');
            $('#descrizione').val(ui.item.descrizione);
			$('#descrizione').prop('disabled', true);
			$('#categoria').val(ui.item.id_categoria);
			$('#categoria').prop('disabled', true);
			$('select#id_tipologia').html('<option value=\''+ui.item.id_tipologia+'\'>'+ui.item.descrizione_tipologia+'</option>');
			$('#id_tipologia').val(ui.item.id_tipologia);
			$('#id_tipologia').prop('disabled', true);
			$('#um_stock').val(ui.item.um_stock);
			$('#um_stock').prop('disabled', true);
			$('#um1').val(ui.item.um_1);
			$('#um1').prop('disabled', true);
			$('#contenuto_um1').val(ui.item.contenuto_um1);
			$('#contenuto_um1').prop('disabled', true);
			if(ui.item.agea == false) {var agea = 'f'; } else {var agea = 't'; }
			$('#agea').val(agea);
			if(ui.item.classificato == false) {var classificato = 'f'; } else {var classificato = 't'; }
			$('#classificato').val(classificato);
			if(ui.item.acquistato == false) {var acquistato = 'f'; } else {var acquistato = 't'; }
			$('#doppione').val('update_stock');
        //  console.log($('#comune').val + ' has faded!');
        },
        change: function (event, ui) {
	//		$('#warning_doppione').html('<div style="text-align:center; width:80%; margin:auto;" class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Attenzione!</strong> Prodotto gi&agrave; presente nel database: la quantit&agrave; inserita nel campo &quot;Stock&quot; verr&agrave; sommata a quella gi&agrave; esistente</div><div class="row">&nbsp;</div>');
            $('#descrizione').val(ui.item.descrizione);
			$('#descrizione').prop('disabled', true);
			$('#categoria').val(ui.item.id_categoria);
			$('#categoria').prop('disabled', true);
			$('select#id_tipologia').html('<option value=\''+ui.item.id_tipologia+'\'>'+ui.item.descrizione_tipologia+'</option>');
			$('#id_tipologia').val(ui.item.id_tipologia);
			$('#id_tipologia').prop('disabled', true);
			$('#um_stock').val(ui.item.um_stock);
			$('#um_stock').prop('disabled', true);
			$('#um1').val(ui.item.um_1);
			$('#um1').prop('disabled', true);
			$('#contenuto_um1').val(ui.item.contenuto_um1);
			$('#contenuto_um1').prop('disabled', true);
			if(ui.item.agea == false) {var agea = 'f'; } else {var agea = 't'; }
			$('#agea').val(agea);
			if(ui.item.classificato == false) {var classificato = 'f'; } else {var classificato = 't'; }
			$('#classificato').val(classificato);
			if(ui.item.acquistato == false) {var acquistato = 'f'; } else {var acquistato = 't'; }
			$('#doppione').val('update_stock');
        }

    });
 });
</script>

<script >
     $.validator.addMethod('regex', function(value, element, param) {
        return this.optional(element) ||
            value.match(typeof param == 'string' ? new RegExp(param) : param);
    }, '');

$(document).ready(function(){
    $('form').validate({
        debug: true,
        rules: {
                    barcode: { required: true, regex: /^[0-9]+$/ },
                    categoria: { required: true },
                    id_tipologia: { required: true },
                    descrizione: { required: true, regex: /^[0-9 A-Za-z\'\.-àèìòù]+$/ },
                    um1: { required: true },
                    contenuto_um1: { required: true, regex: /^[0-9]\d*(\.[5])?$/ },
                    agea: { required: true },
                    classificato: { required: true },
                    acquistato: { required: true },
                    um_stock: { required: true },
//                    stock: { required: false, regex: /^[1-9]\d*$/ },
                    data_donazione: { required: true, regex: /^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/ },
                    fornitore: { required: true },
                    note: { required: false },
                    
        },
        highlight: function (element) { $(element).addClass('input-error'); },
        unhighlight: function(element) { $(element).removeClass('input-error'); },
        success: function (element) { $(element).removeClass('input-error'); },
        errorPlacement: function (error, element) { },
        submitHandler: function(form) { form.submit(); }
    });
});

</script>
