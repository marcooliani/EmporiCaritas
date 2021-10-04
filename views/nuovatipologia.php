<?php include("_navbar.php"); ?>

<div class="col-md-12">
    <div class="row">
        <div class="col-sm-9 col-md-9 col-lg-10 col-main">
			<div style="float:left; height:100%; width:100%;">

				<div class="panel panel-default"  style="height:100%; background:#FFFFFF">
					<div class="panel-heading"><i class="fa fa-barcode" aria-hidden="true"></i> <strong>NUOVA TIPOLOGIA DI PRODOTTO</strong></div>
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
							<form id="nuova_tipologia" method="POST" action="/tipologie/inserisci">
							<div class="well well-sm">
							  <div class="row">
								<div class="col-sm-4 col-md-4 col-lg-4">
									<div class="form-group">
										<label for="exampleInputEmail1">Nome Tipologia *</label>
										<input type="text" class="form-control" id="descrizione_tipologia" name="descrizione_tipologia" tabindex="1" placeholder="Inserire nome tipologia" autofocus>
									</div>
								</div>
								<div class="col-sm-4 col-md-3 col-lg-3">
									<div class="form-group">
										<label for="exampleInputEmail1">Categoria *</label>
										<select class="form-control" id="id_categoria" name="id_categoria" tabindex="2">
											<option value="" selected disabled> -- Seleziona -- </option>
											<?php
												foreach($ris as $key=>$val) {
													print("<option value='".$val['id_categoria']."'>".ucwords($val['descrizione_categoria'])."</option>");
												}
											?>
										</select>
									</div>
								</div>
								<div class="col-sm-1 col-md-1 col-lg-1">
									<div class="form-group">
										<label for="exampleInputEmail1">Punti *</label>
										<input type="text" class="form-control" id="punti" name="punti" tabindex="3" placeholder="Es: 7">
									</div>
								</div>
								<div class="col-sm-2 col-md-2 col-lg-2">
									<div class="form-group">
										<label for="exampleInputEmail1">Livello riordino stock *</label>
										<input type="text" class="form-control" id="warning_qta_minima" name="warning_qta_minima" tabindex="4" placeholder="Es: 20">
									</div>
								</div>
								<div class="col-sm-2 col-md-2 col-lg-2">
									<div class="form-group">
										<label for="exampleInputEmail1">Livello critico stock *</label>
										<input type="text" class="form-control" id="danger_qta_minima" name="danger_qta_minima" tabindex="5" placeholder="Es: 10">
									</div>
								</div>
								<div class="col-sm-1 col-md-1 col-lg-1">
									<div class="form-group">
										<label for="exampleInputEmail1">Et&agrave; min</label>
										<input type="text" class="form-control" id="eta_min" name="eta_min" tabindex="6" placeholder="Es: 3">
									</div>
								</div>
								<div class="col-sm-1 col-md-1 col-lg-1">
									<div class="form-group">
										<label for="exampleInputEmail1">Et&agrave; max</label>
										<input type="text" class="form-control" id="eta_max" name="eta_max" tabindex="7" placeholder="Es: 10">
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
            <a class="btn btn-info btn-sm btn-block" href="/tipologie/index"><i class="fa fa-list" aria-hidden="true"></i> Torna all'elenco</a>

        </div>
    </div>
</div>


<script type="text/javascript">
$(document).ready(function(){

     return false;
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
                    descrizione_tipologia: { required: true, regex: /^[0-9 A-Za-z\'àèìòù\.&-]+$/ },
					id_categoria: { required: true },
                    warning_qta_minima: { required: true, regex: /^[0-9]+$/ },
                    danger_qta_minima: { required: true, regex: /^[0-9]+$/ },
                    punti: { required: true, regex: /^[0-9]+$/ },
                    eta_min: { required: false, regex: /^[0-9]+$/ },
                    eta_max: { required: false, regex: /^[0-9]+$/ },
                                        
                },
        highlight: function (element) { $(element).addClass('input-error'); },
        unhighlight: function(element) { $(element).removeClass('input-error'); },
        success: function (element) { $(element).removeClass('input-error'); },
        errorPlacement: function (error, element) { },
        submitHandler: function(form) { form.submit(); }
        });
});

</script>
