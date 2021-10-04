<?php include("_navbar.php"); ?>

<div class="col-md-12">
    <div class="row">
        <div class="col-sm-9 col-md-9 col-lg-10 col-main">
			<div style="float:left; height:100%; width:100%;">

				<div class="panel panel-default"  style="height:100%; background:#FFFFFF">
					<div class="panel-heading"><i class="fa fa-barcode" aria-hidden="true"></i> <strong>MODIFICA ENTE</strong></div>
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
							<form id="modifica_ente" method="POST" action="/enti/update_ente/<?php echo $ente; ?>">
							<?php $val = $ris->fetch(); ?>
							<div class="well well-sm">
							  <div class="row">
								<div class="col-sm-5 col-md-5 col-lg-5">
									<div class="form-group">
										<label for="exampleInputEmail1">Ragione sociale *</label>
										<input type="text" class="form-control" id="ragione_sociale" name="ragione_sociale" tabindex="1" 
											value="<?php echo ucwords($val['ragione_sociale']); ?>" autofocus>
									</div>
								</div>

								<div class="col-sm-2 col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Inserito il *</label>
                                        <div class="input-group date" data-provide="datepicker">
                                            <input type="text" class="form-control" id="inserito_il" name="inserito_il" tabindex="2" 
												value="<?php echo date("d/m/Y", strtotime($val['inserito_il'])); ?>">
                                            <div class="input-group-addon">
                                                <span class="fa fa-calendar"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

							  </div>

							  <div class="row">
								<div class="col-sm-5 col-md-5 col-lg-5">
									<label>Note</label>
									<textarea class="form-control" rows="5" name="note" id="note" tabindex="3"><?php echo $val['note']; ?></textarea>
								</div>
							  </div>
						  	</div>

						</div>
				</div>
			</div>

        </div>
        <div class="col-sm-3 col-md-3 col-lg-2 col-right">&nbsp;

			<div>&nbsp;</div>
			<button type="submit" class="btn btn-success btn-sm btn-block" tabindex="4"><i class="fa fa-save" aria-hidden="true"></i> Salva Modifiche</button>
			<button type="reset" class="btn btn-danger btn-sm btn-block" tabindex="5"><i class="fa fa-times" aria-hidden="true"></i> Reset</button>
			</form>

            <div>&nbsp;</div>
            <div>&nbsp;</div>
            <a class="btn btn-info btn-sm btn-block" href="/enti/index"><i class="fa fa-list" aria-hidden="true"></i> Torna all'elenco</a>

        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
    $('#inserito_il').datepicker();
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
                    ragione_sociale: { required: true, regex: /^[0-9 A-Za-z\'àèìòù\.&]+$/ },
                    inserito_il: { required: true, regex: /^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/ },
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
