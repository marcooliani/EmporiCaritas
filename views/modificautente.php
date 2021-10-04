<?php include("_navbaradmin.php"); ?>

<div class="col-md-12">
    <div class="row">
        <div class="col-sm-9 col-md-9 col-lg-10 col-main">
			<div style="float:left; height:100%; width:100%;">

				<div class="panel panel-default"  style="height:100%; background:#FFFFFF">
					<div class="panel-heading"><i class="fa fa-barcode" aria-hidden="true"></i> <strong>MODIFICA UTENTE (<?php echo $user; ?>)</strong></div>
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


							<form id="modifica_utente" method="POST" action="/utenti/update_utente/<?php echo $user; ?>">
							<?php $val = $ris->fetch(); ?>
							<div class="well well-sm">
							  <div class="row">
								<div class="col-sm-4 col-md-4 col-lg-4">
									<div class="form-group">
										<label for="exampleInputEmail1">Login</label>
										<input type="text" class="form-control" id="login" name="login" tabindex="1" 
											value="<?php echo $val['login']; ?>" disabled>
									</div>
								</div>
								<div class="col-sm-4 col-md-4 col-lg-4">
									<div class="form-group">
										<label for="exampleInputEmail1">Password (8-16 char) *</label>
										<input type="text" class="form-control" id="password" name="password" tabindex="2" value="<?php echo $val['password']; ?>" >
									</div>
								</div>
								<div class="col-sm-3 col-md-3 col-lg-3">
									<div class="form-group">
										<label for="exampleInputEmail1">Ruolo *</label>
										<select class="form-control" id="ruolo" name="ruolo" tabindex="3">
											<option value="" selected disabled> --- </option>
											<option value="accettazione" <?php if($val['ruolo'] == "accettazione") echo "selected"; ?>>Accettazione</option>
											<option value="cassiere" <?php if($val['ruolo'] == "cassiere") echo "selected"; ?>>Cassiere</option>
											<option value="backend" <?php if($val['ruolo'] == "backend") echo "selected"; ?>>BackOffice Operator</option>
											<option value="cassa+backend" <?php if($val['ruolo'] == "cassa+backend") echo "selected"; ?>>Cassa + BackOffice</option>
											<option value="superlocale" <?php if($val['ruolo'] == "superlocale") echo "selected"; ?>>Amministratore emporio</option>
										</select>
									</div>
								</div>
							  </div>

							  <div class="row">
								<div class="col-sm-4 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Cognome</label>
                                        <input type="text" class="form-control" id="cognome" name="cognome" tabindex="4" value="<?php echo $val['cognome']; ?>">
                                    </div>
                                </div>
								<div class="col-sm-4 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Nome</label>
                                        <input type="text" class="form-control" id="nome" name="nome" tabindex="5" value="<?php echo $val['nome']; ?>">
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
            <a class="btn btn-info btn-sm btn-block" href="/utenti/index"><i class="fa fa-list" aria-hidden="true"></i> Torna all'elenco</a>

        </div>
    </div>
</div>

<script >
     $.validator.addMethod('regex', function(value, element, param) {
        return this.optional(element) ||
            value.match(typeof param == 'string' ? new RegExp(param) : param);
    }, '');

$(document).ready(function(){
    $('form').validate({
        debug: true,
        rules: {
                    password: { required: true, minlength:8, maxlength:16 },
                    ruolo: { required: true },
                    cognome: { required: false, regex: /^[0-9 A-Za-z\'àèìòù]+$/ },
                    nome: { required: false, regex: /^[0-9 A-Za-z\'àèìòù]+$/ },
                                        
                },
        highlight: function (element) { $(element).addClass('input-error'); },
        unhighlight: function(element) { $(element).removeClass('input-error'); },
        success: function (element) { $(element).removeClass('input-error'); },
        errorPlacement: function (error, element) { },
        submitHandler: function(form) { form.submit(); }
        });
});

</script>
