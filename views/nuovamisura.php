<?php include("_navbaradmin.php"); ?>

<div class="col-md-12">
    <div class="row">
        <div class="col-sm-9 col-md-9 col-lg-10 col-main">
			<div style="float:left; height:100%; width:100%;">

				<div class="panel panel-default"  style="height:100%; background:#FFFFFF">
					<div class="panel-heading"><i class="fa fa-barcode" aria-hidden="true"></i> <strong>NUOVA UNIT&Agrave; DI MISURA</strong></div>
						<div class="panel-body" style="height:93%; overflow-y:auto; ">

						<?php
							if($insertok == "1") {
						?>
							<div class="alert alert-success">
  								<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  								<strong>Inserimento avvenuto con successo!</strong>
							</div>
						<?php
							}
							else if($insertok == "0") {
						?>
							<div class="alert alert-danger">
  								<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  								<strong>Inserimento fallito!</strong>
							</div>
						<?php
							}
						?>

							<div style="font-size:12px; padding-bottom:10px;"><i>(*) = Campi obbligatori</i></div>
							<form id="nuova_misura" method="POST" action="/misure/inserisci">
							<div class="well well-sm">
							  <div class="row">
								<div class="col-sm-2 col-md-2 col-lg-2">
									<div class="form-group">
										<label for="exampleInputEmail1">Unit&agrave; *</label>
										<input type="text" class="form-control" id="val_um" name="val_um" tabindex="1" placeholder="Es: kg, g, pz" autofocus>
									</div>
								</div>
								<div class="col-sm-5 col-md-5 col-lg-5">
									<div class="form-group">
										<label for="exampleInputEmail1">Descrizione</label>
										<input type="text" class="form-control" id="descrizione" name="descrizione" tabindex="2" placeholder="Es: chilogrammi, grammi, pezzi">
									</div>
								</div>
                                <div class="col-sm-2 col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Tipo UM *</label>
                                        <select class="form-control" id="tipo_um" name="tipo_um" tabindex="4">
                                            <option value="" selected disabled> -- Seleziona -- </option>
                                            <option value="capacita">Capacit&agrave;</option>
                                            <option value="dosi">Dosi</option>
                                            <option value="lunghezza">Lunghezza</option>
                                            <option value="peso">Peso</option>
                                            <option value="pezzi">Pezzi</option>
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
			<button type="submit" class="btn btn-success btn-sm btn-block"><i class="fa fa-plus" aria-hidden="true"></i> Inserisci</button>
			<button type="reset" class="btn btn-danger btn-sm btn-block"><i class="fa fa-times" aria-hidden="true"></i> Reset</button>
			</form>

        </div>
    </div>
</div>


<script type="text/javascript">
$(document).ready(function(){

     return false;
});

</script>

<script type="text/javascript">
$(document).ready(function() {
    $('#categoria').change(function(){
    var id_cat = $('select#categoria option:selected').attr('value');

    $.ajax({
        type: "POST",
        url: "/prodotti/ajax_tipologie",
        data: {'id': id_cat},
        dataType: "html",
        success: function(data) {
            $("select#id_tipologia").html(data);
        }
 
    });

    return false;
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
                    val_um: { required: true, regex: /^[a-z]+$/ },
                    descrizione: { required: false, regex: /^[a-zA-Z ]+$/ },
                    tipo_um: { required: true }
                                        
                },
        highlight: function (element) { $(element).addClass('input-error'); },
        unhighlight: function(element) { $(element).removeClass('input-error'); },
        success: function (element) { $(element).removeClass('input-error'); },
        errorPlacement: function (error, element) { },
        submitHandler: function(form) { form.submit(); }
        });
});

</script>
