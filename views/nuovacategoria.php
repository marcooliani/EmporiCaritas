<?php include("_navbar.php"); ?>

<div class="col-md-12">
    <div class="row">
        <div class="col-sm-9 col-md-9 col-lg-10 col-main">
			<div style="float:left; height:100%; width:100%;">

				<div class="panel panel-default"  style="height:100%; background:#FFFFFF">
					<div class="panel-heading"><i class="fa fa-barcode" aria-hidden="true"></i> <strong>NUOVA CATEGORIA MERCEOLOGICA</strong></div>
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
							<form id="nuova_categoria" method="POST" action="/categorie/inserisci">
							<div class="well well-sm">
							  <div class="row">
								<div class="col-sm-4 col-md-3 col-lg-3">
									<div class="form-group">
										<label for="exampleInputEmail1">Nome categoria *</label>
										<input type="text" class="form-control" id="descrizione_categoria" name="descrizione_categoria" tabindex="1" placeholder="Inserire nome categoria" autofocus>
									</div>
								</div>
								<div class="col-sm-2 col-md-2 col-lg-2">
									<div class="form-group">
										<label for="exampleInputEmail1">Lim. punti per SPESA *</label>
										<input type="text" class="form-control" id="limite_spesa_max" name="limite_spesa_max" tabindex="2" placeholder="Es: 100">
									</div>
								</div>
								<div class="col-sm-2 col-md-2 col-lg-2">
									<div class="form-group">
										<label for="exampleInputEmail1">Lim. punti per MESE *</label>
										<input type="text" class="form-control" id="limite_mese_max" name="limite_mese_max" tabindex="3" placeholder="Es: 100">
									</div>
								</div>
								<div class="col-sm-2 col-md-2 col-lg-2">
									<div class="form-group">
										<label for="exampleInputEmail1">Tipo UM *</label>
										<select class="form-control" id="tipo_um" name="tipo_um" tabindex="4"> 
                                            <option value="" selected disabled> -- Seleziona -- </option>
                                            <?php
                                                foreach($ris as $key=>$val) {
                                                    if($val['tipo'] == "capacita") { $tipo = "Capacit&agrave;"; }
                                                    else { $tipo = $val['tipo']; }
                                                    print('<option value="'.$val['tipo'].'">'.ucwords($tipo).'</option>');
                                                } 
                                            ?>
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

            <div>&nbsp;</div>
            <div>&nbsp;</div>
            <a class="btn btn-info btn-sm btn-block" href="/categorie/index"><i class="fa fa-list" aria-hidden="true"></i> Torna all'elenco</a>

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
        url: "/tipologie/ajax_tipologie",
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
                    descrizione_categoria: { required: true, regex: /^[0-9 A-Za-z\'àèìòù\.&-]+$/ },
					limite_spesa_max: { required: true, regex: /^[0-9]+$/ },
					limite_mese_max: { required: true, regex: /^[0-9]+$/ },
                    tipo_um: { required: true },
                                        
                },
        highlight: function (element) { $(element).addClass('input-error'); },
        unhighlight: function(element) { $(element).removeClass('input-error'); },
        success: function (element) { $(element).removeClass('input-error'); },
        errorPlacement: function (error, element) { },
        submitHandler: function(form) { form.submit(); }
        });
});

</script>
