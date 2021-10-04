<?php include("_navbar.php"); ?>

<div class="col-md-12">
    <div class="row">
        <div class="col-sm-9 col-md-9 col-lg-10 col-main">
			<div style="float:left; height:100%; width:100%;">

				<div class="panel panel-default"  style="height:100%; background:#FFFFFF">
					<div class="panel-heading"><i class="fa fa-truck" aria-hidden="true"></i> <strong>NUOVO FORNITORE</strong></div>
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
						
							<form id="nuovo_fornitore" method="POST" action="/fornitori/inserisci">
							<div class="well well-sm">
						      <div class="row">

								<div class="col-sm-4 col-md-4 col-lg-4">
									<div class="form-group">
										<label for="exampleInputEmail1">Ragione sociale *</label>
										<input type="text" class="form-control" id="tessera" name="ragione_sociale" tabindex="1" placeholder="Inserire ragione sociale" >
									</div>
								</div>

								<div class="col-sm-4 col-md-4 col-lg-4">
									<div class="form-group">
										<label for="exampleInputEmail1">Cognome</label>
										<input type="text" class="form-control" id="cognome" name="cognome" placeholder="Inserire cognome" tabindex="4">
									</div>
								</div>

								<div class="col-sm-4 col-md-4 col-lg-4">
									<div class="form-group">
										<label for="exampleInputPassword1">Nome</label>
										<input type="text" class="form-control" id="nome" name="nome" placeholder="Inserire nome" tabindex="5">
									</div>
								</div>

							  </div>

							  <div class="row">
								<div class="col-sm-4 col-md-4 col-lg-4">
									<div class="form-group">
										<label for="exampleInputEmail1">Iscritto da *</label>
										<div class="input-group date" data-provide="datepicker">
											<input type="text" class="form-control" id="iscrittoda" name="iscrittoda" tabindex="6" value="<?php echo date("d/m/Y"); ?>">
											<div class="input-group-addon">
												<span class="fa fa-calendar"></span>
											</div>
										</div>
									</div>
							    </div>
								<div class="col-sm-2 col-md-2 col-lg-2">
									<div class="form-group">
										<label for="exampleInputPassword1">Donatore</label>
										<select class="form-control" id="donatore" name="donatore" tabindex="7">
											<option value="t">S&igrave;</option>
											<option value="f">No</option>
										</select>
									</div>
								</div>

							</div>
						</div>

						<div class="well well-sm">									
							<div class="row">
                                <div class="col-sm-4 col-md-4 col-lg-4">
										<div class="form-group">
											<label for="exampleInputEmail1">Indirizzo</label>
											<input type="text" class="form-control" id="indirizzo" name="indirizzo" placeholder="Es: via Einaudi, 10" tabindex="8">
										</div>
								</div>
								<div class="col-sm-4 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Localit&agrave;</label>
                                        <input type="text" class="form-control" id="localita" name="localita" placeholder="Inserire località" tabindex="9">
                                    </div>
								</div>
								<div class="col-md-3 col-lg-3">
									<div class="form-group">
										<label for="exampleInputEmail1">Comune</label>
										<input type="text" class="form-control" id="comune_nome" name="comune_nome" placeholder="Inserire Comune" tabindex="10">
										<input type="hidden" class="form-control" id="comune" name="comune" value="">
									</div>
								</div>
								<div class="col-sm-1 col-md-1 col-lg-1">
									<div class="form-group">
                                        <label for="exampleInputEmail1">CAP</label>
                                        <input type="text" class="form-control" id="cap" name="cap" value="">
									</div>
								</div>
							</div>
			
							<div class="row">
								<div class="col-sm-4 col-md-4 col-lg-4">
									<div class="form-group">
										<label for="exampleInputEmail1">Telefono</label>
										<input type="text" class="form-control" id="telefono" name="telefono" placeholder="Inserire telefono fisso" tabindex="11">
									</div>
								</div>
								<div class="col-sm-4 col-md-4 col-lg-4">
									<div class="form-group">
										<label for="exampleInputEmail1">Cellulare</label>
										<input type="text" class="form-control" id="cellulare" name="cellulare" placeholder="Inserire cellulare" tabindex="12">
									</div>
								</div>
								<div class="col-sm-4 col-md-4 col-lg-4">
									<div class="form-group">
										<label for="exampleInputEmail1">Email</label>
										<input type="text" class="form-control" id="email" name="email" placeholder="Inserire email" tabindex="13">
									</div>
								</div>
							</div>
						</div>

						<div class="well well-sm">
						  <div class="row">
							<div class="col-sm-4 col-md-4 col-lg-4">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Note</label>
                                    <textarea rows="4" cols="50" class="form-control" id="note" name="note" tabindex="19"></textarea>
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
			<button type="submit" class="btn btn-success btn-sm btn-block" tabindex="20"><i class="fa fa-plus" aria-hidden="true"></i> Inserisci</button>
			<button type="reset" class="btn btn-danger btn-sm btn-block" tabindex="21"><i class="fa fa-times" aria-hidden="true"></i> Reset</button>
			</form>

            <div>&nbsp;</div>
            <div>&nbsp;</div>
            <a class="btn btn-info btn-sm btn-block" href="/fornitori/index"><i class="fa fa-list" aria-hidden="true"></i> Torna all'elenco</a>

        </div>
    </div>
</div>


<script type="text/javascript">
$(document).ready(function(){

     return false;
});

</script>

<script type="text/javascript">
$(document).ready(function(){
    $('#iscrittoda').datepicker();
});
</script>

<script type="text/javascript">
 $(document).ready(function(){
	$("#comune_nome").autocomplete({

		source: function (request, response) {
			var re = $.ui.autocomplete.escapeRegex(request.term);
			var matcher = new RegExp("^" + re, "i");
			$.getJSON("/famiglie/ajax_comuni", function (data) {
				response($.grep(($.map(data, function (v, i) {
					return {
						label: v.nome,
						value: v.nome,
						istat: v.cod_istat,
						cap: v.cap
					};
				})), function (item) {
					return matcher.test(item.value);
				}))
			});
		},
		select: function (event, ui) {
		//  alert(ui.item ? ("You picked '" + ui.item.label + "' with an ID of " + ui.item.istat) : "Nothing selected, input was " + this.value);
			$('#comune').val(ui.item.istat);
			$('#cap').val(ui.item.cap);
		//  console.log($('#comune').val + ' has faded!');
		},
		change: function (event, ui) {
		//  alert(ui.item ? ("You picked '" + ui.item.label + "' with an ID of " + ui.item.istat) : "Nothing selected, input was " + this.value);
			$('#comune').val(ui.item.istat);
			$('#cap').val(ui.item.cap);
		//  console.log($('#comune').val+'has faded!')
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
					ragione_sociale: { required: true, regex: /^[0-9 A-Za-z\'àèìòù\.&]+$/ },
                    cognome: { required: false ,regex: /^[0-9 A-Za-z\'àèìòù\.&]+$/ },
                    nome: { required: false, regex: /^[0-9 A-Za-z\'àèìòù]+$/ },
                    indirizzo: { required: false, regex: /^[0-9 A-Za-z\'\.\,\/àèìòù]+$/ },
                    localita: { required: false, regex: /^[0-9 A-Za-z\'\.àèìòù]+$/ },
                    cap: { required: false, regex: /^[0-9]{5}$/ },
                    comune: { required: false },
                    telefono: { required:false, regex: /^[0-9]+$/ },
                    cellulare: { required: false, regex: /^[0-9]{10}$/ },
                    email: { required: false, regex: /^[\w\-\.]*[\w\.]\@[\w\.]*[\w\-\.]+[\w\-]+[\w]\.+[\w]+[\w $]/ },
					donatore: { required: true },
                    iscrittoda: { required: true, regex: /^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/ },
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
