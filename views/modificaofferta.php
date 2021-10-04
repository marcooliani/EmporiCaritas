<?php include("_navbar.php"); ?>

<div class="col-md-12">
    <div class="row">
        <div class="col-sm-9 col-md-9 col-lg-10 col-main">
			<div style="float:left; height:100%; width:100%;">

				<div class="panel panel-default"  style="height:100%; background:#FFFFFF">
					<div class="panel-heading"><i class="fa fa-gift" aria-hidden="true"></i> <strong>MODIFICA OFFERTA</strong></div>
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
							<form id="modifica_categoria" method="POST" action="/offerte/update_offerta/<?php echo $id_offerta; ?>">
							<?php $val = $ris->fetch(); ?>
							<div class="well well-sm">
							  <div class="row">
								<div class="col-sm-3 col-md-3 col-lg-3">
									<div class="form-group">
										<label for="exampleInputEmail1">Prodotto</label>
										<input type="text" class="form-control" id="prodotto" name="prodotto" value="<?php echo $val['descrizione']; ?>" disabled>
									</div>
								</div> 
								<div class="col-sm-3 col-md-3 col-lg-3">
									<div class="form-group">
										<label for="exampleInputEmail1">Tipologia</label>
										<input type="text" class="form-control" id="tipologia" name="tipologia" value="<?php echo ucwords($val['descrizione_tipologia']); ?>" disabled>
									</div>
								</div> 
								<div class="col-sm-2 col-md-2 col-lg-2">
									<div class="form-group">
										<label for="exampleInputEmail1">Prezzo offerta *</label>
										<input type="text" class="form-control" id="prezzo_offerta" name="prezzo_offerta" 
										value="<?php echo $val['prezzo_offerta']; ?>" tabindex="1" >
									</div>
								</div>
								<div class="col-sm-2 col-md-2 col-lg-2">
									<div class="form-group">
										<label for="exampleInputEmail1">Valida da *</label>
										<div class='input-group date' data-provide='datepicker'>
											<input type="text" class="form-control" id="offerta_da" name="offerta_da" 
												value="<?php echo date("d/m/Y", strtotime($val['offerta_da'])); ?>" tabindex="2">
											<div class='input-group-addon'>
                            					<span class='fa fa-calendar'></span>
                        					</div>
                        				</div>
									</div>
								</div>
								<div class="col-sm-2 col-md-2 col-lg-2">
									<div class="form-group">
										<label for="exampleInputEmail1">Valida a *</label>
										<div class='input-group date' data-provide='datepicker'>
											<input type="text" class="form-control" id="offerta_a" name="offerta_a" 
												value="<?php echo date("d/m/Y", strtotime($val['offerta_a'])); ?>" tabindex="3">
											<div class='input-group-addon'>
                            					<span class='fa fa-calendar'></span>
                        					</div>
                        				</div>
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
			<button type="submit" class="btn btn-success btn-sm btn-block"><i class="fa fa-save" aria-hidden="true"></i> Salva Modifiche</button>
			<button type="reset" class="btn btn-danger btn-sm btn-block"><i class="fa fa-times" aria-hidden="true"></i> Reset</button>
			</form>

            <div>&nbsp;</div>
            <div>&nbsp;</div>
            <a class="btn btn-info btn-sm btn-block" href="/offerte/index"><i class="fa fa-list" aria-hidden="true"></i> Torna all'elenco</a>

        </div>
    </div>
</div>


<script type="text/javascript">
$(document).ready(function(){
	$('#offerta_da').datepicker();
	$('#offerta_a').datepicker();
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
                    prezzo_offerta: { required: true, regex: /^[0-9]+$/ },
                    offerta_da: { required: true },
                    offerta_a: { required: true },
                                        
                },
        highlight: function (element) { $(element).addClass('input-error'); },
        unhighlight: function(element) { $(element).removeClass('input-error'); },
        success: function (element) { $(element).removeClass('input-error'); },
        errorPlacement: function (error, element) { },
        submitHandler: function(form) { form.submit(); }
        });
});

</script>
