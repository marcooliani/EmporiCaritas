<?php include("_navbar.php"); ?>

<div class="col-md-12">
    <div class="row">
        <div class="col-sm-9 col-md-9 col-lg-10 col-main">
			<div style="float:left; height:100%; width:100%;">

				<div class="panel panel-default"  style="height:100%; background:#FFFFFF">
					<div class="panel-heading"><i class="fa fa-id-card-o" aria-hidden="true"></i> <strong>NUOVO CLIENTE</strong></div>
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

							<form id="nuova_famiglia" method="POST" action="/famiglie/nuovo_capo">
							<div class="well well-sm">
						      	<div class="row">
									<div class="col-sm-3 col-md-3 col-lg-3">
										<div class="form-group">
											<label for="exampleInputEmail1">Codice Fiscale *</label>
											<input type="text" class="form-control" id="tessera" name="tessera" tabindex="1" placeholder="Inserire codice fiscale" autofocus>
										</div>
									</div>
									<div class="col-sm-2 col-md-2 col-lg-2">
										<div class="form-group">
											<label for="exampleInputEmail1">Famigliari *</label>
											<input type="text" class="form-control" id="num_componenti" name="num_componenti" placeholder="Es: 4"  tabindex="2">
										</div>
									</div>
									<div class="col-sm-2 col-md-2 col-lg-2">
										<div class="form-group">
											<label for="exampleInputEmail1">Punti assegnati *</label>
											<input type="text" class="form-control" id="punti" name="punti" placeholder="Es: 250" tabindex="3">
										</div>
									</div>
									<div class="col-sm-2 col-md-2 col-lg-2">
										<div class="form-group">
											<label for="exampleInputEmail1">Punti residui *</label>
											<input type="text" class="form-control" id="punti_residui" name="punti_residui" placeholder="Es: 250" tabindex="4">
										</div>
									</div>
									<div class="col-sm-2 col-md-2 col-lg-2">
										<div class="form-group">
											<label for="exampleInputEmail1">Esenzione</label>
											<select class="form-control" id="esenzione" name="esenzione" tabindex="5">
												<option value="f">No</option>
												<option value="t">S&igrave;</option>
											</select>
										</div>
									</div>
							  	</div>
							  </div>

							<div class="well well-sm">
								<div class="row">
									<div class="col-sm-3 col-md-3 col-lg-3">
										<div class="form-group">
											<label for="exampleInputEmail1">Cognome / Rag. Sociale *</label>
											<input type="text" class="form-control" id="cognome" name="cognome" placeholder="Inserire cognome" tabindex="6">
										</div>
									</div>
									<div class="col-sm-3 col-md-3 col-lg-3">
										<div class="form-group">
											<label for="exampleInputPassword1">Nome</label>
											<input type="text" class="form-control" id="nome" name="nome" placeholder="Inserire nome" tabindex="7">
										</div>
									</div>
									<div class="col-sm-2 col-md-2 col-lg-2">
										<div class="form-group">
											<label for="exampleInputPassword1">Ente</label>
											<select class="form-control" id="is_ente" name="is_ente" tabindex="8">
												<option value="f"> No </option>
												<option value="t"> S&igrave; </option>
											</select>
										</div>
									</div>
									<div class="col-sm-3 col-md-3 col-lg-3">
										<div class="form-group">
											<label for="exampleInputPassword1">Data di nascita</label>
											<div class="input-group date" data-provide="datepicker">
												<input type="text" class="form-control" id="natoil" name="natoil" placeholder="Es: 01/01/1970" tabindex="9">
												<div class="input-group-addon">
													<span class="fa fa-calendar"></span>
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-1 col-md-1 col-lg-1">
										<div class="form-group">
											<label for="exampleInputPassword1">Sesso</label>
											<select class="form-control" id="sesso" name="sesso" tabindex="10">
												<option value=""> --- </option>
												<option value="M"> M </option>
												<option value="F"> F </option>
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-4 col-md-4 col-lg-4">
										<div class="form-group">
											<label for="exampleInputEmail1">Luogo di nascita</label>
											<input type="text" class="form-control" id="luogo_nascita" name="luogo_nascita" placeholder="Inserire luogo di nascita" tabindex="11">
										</div>
									</div>
									<div class="col-sm-4 col-md-4 col-lg-4">
										<div class="form-group">
											<label for="exampleInputEmail1">Nazione di nascita</label>
											<input type="text" class="form-control" id="nazione_vis" name="nazione_vis" placeholder="Inserire nazione di nascita" tabindex="12">
											<input type="hidden" class="form-control" id="nazione" name="nazione">
										</div>
									</div>
									<div class="col-sm-4 col-md-4 col-lg-4">
										<div class="form-group">
											<label for="exampleInputEmail1">Nazionalit&agrave;</label>
											<input type="text" class="form-control" id="nazionalita_vis" name="nazionalita_vis" placeholder="Inserire nazionalit&agrave;" tabindex="13">
											<input type="hidden" class="form-control" id="nazionalita" name="nazionalita">
										</div>
									</div>
									
								</div>
							  </div>
						
							  <div class="well well-sm">			
								<div class="row">
                                    <div class="col-sm-4 col-md-4 col-lg-4">
										<div class="form-group">
											<label for="exampleInputEmail1">Indirizzo *</label>
											<input type="text" class="form-control" id="indirizzo" name="indirizzo" placeholder="Es: via Einaudi, 10" tabindex="14">
										</div>
									</div>
									<div class="col-sm-4 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Localit&agrave; </label>
                                            <input type="text" class="form-control" id="localita" name="localita" placeholder="Inserire località" tabindex="15">
                                        </div>
									</div>
									<div class="col-md-3 col-lg-3">
										<div class="form-group">
											<label for="exampleInputEmail1">Comune *</label>
											<input type="text" class="form-control" id="comune_nome" name="comune_nome" placeholder="Inserire Comune" tabindex="16">
											<input type="hidden" class="form-control" id="comune" name="comune" value="">
										</div>
									</div>
									<div class="col-sm-1 col-md-1 col-lg-1">
										<div class="form-group">
                                            <label for="exampleInputEmail1">CAP *</label>
                                            <input type="text" class="form-control" id="cap" name="cap" value="">
										</div>
									</div>
								</div>
			
								<div class="row">
									<div class="col-sm-4 col-md-4 col-lg-4">
										<div class="form-group">
											<label for="exampleInputEmail1">Telefono</label>
											<input type="text" class="form-control" id="telefono" name="telefono" placeholder="Inserire telefono fisso" tabindex="17">
										</div>
									</div>
									<div class="col-sm-4 col-md-4 col-lg-4">
										<div class="form-group">
											<label for="exampleInputEmail1">Cellulare</label>
											<input type="text" class="form-control" id="cellulare" name="cellulare" placeholder="Inserire cellulare" tabindex="18">
										</div>
									</div>
									<div class="col-sm-4 col-md-4 col-lg-4">
										<div class="form-group">
											<label for="exampleInputEmail1">Email</label>
											<input type="text" class="form-control" id="email" name="email" placeholder="Inserire email" tabindex="19">
										</div>
									</div>
							      </div>
								</div>

								<div class="well well-sm">
                                  <div class="row">
									<div class="col-sm-2 col-md-2 col-lg-2">
                                        <div class="form-group">
											<label for="exampleInputEmail1">Iscritto da *</label>
											<div class="input-group date" data-provide="datepicker">
												<input type="text" class="form-control" id="iscrittoda" name="iscrittoda" tabindex="20" value="<?php echo date("d/m/Y"); ?>">
												<div class="input-group-addon">
													<span class="fa fa-calendar"></span>
												</div>
											</div>
                                        </div>
									</div>
									<div class="col-sm-2 col-md-2 col-lg-2">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Scadenza *</label>
											<div class="input-group date" data-provide="datepicker">
												<input type="text" class="form-control" id="scadenza" name="scadenza" tabindex="21" value="<?php echo date('d/m/Y', strtotime('+1 years'));  ?>">
												<div class="input-group-addon">
													<span class="fa fa-calendar"></span>
												</div>
											</div>
                                        </div>
                                    </div>
									<div class="col-sm-4 col-md-4 col-lg-4">
										<div class="form-group">
											<label for="exampleInputEmail1">Ente proponente *</label>
											<select class="form-control" id="ente_proponente" name="ente_proponente" tabindex="22">
												<option value="" selected disabled> -- Seleziona -- </option>
												<?php
													foreach($enti as $key=>$val) {
														print("<option value='".$val['id_ente']."'>".ucwords($val['ragione_sociale'])."</option>");
													}
												?>
											</select>
										</div>
									</div>
                                    <div class="col-sm-2 col-md-2 col-lg-2">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Giorno</label>
											<select class="form-control" id="giorno1" name="giorno1" tabindex="23">
												<option value=""> --- </option>
												<?php
													setlocale(LC_ALL, 'it_IT.UTF-8');
													$timestamp = strtotime('next Sunday');
													
													for ($i = 0; $i < 7; $i++) {
														$days = ucwords(strftime('%A', $timestamp));
														$timestamp = strtotime('+1 day', $timestamp);

														print("<option value='".$days."'>".$days."</option>");
													}
												?>
											</select>
                                        </div>
									</div>
									<div class="col-sm-2 col-md-4 col-lg-2">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Orario</label>
                                            <input type="text" class="form-control" id="orario" name="orario" tabindex="24" placeholder="Es: 14:00 - 16:00">
                                        </div>
									</div>
								  </div>
								  <div class="row">
									<div class="col-sm-4 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Note</label>
                                             <textarea rows="4" cols="50" class="form-control" id="note" name="note" tabindex="25"></textarea>
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
			<button type="submit" id="btn_form_send" class="btn btn-success btn-sm btn-block" tabindex="26"><i class="fa fa-plus" aria-hidden="true"></i> Inserisci</button>
			<button type="reset" class="btn btn-danger btn-sm btn-block" tabindex="27"><i class="fa fa-times" aria-hidden="true"></i> Reset</button>
			</form>

            <div>&nbsp;</div>
            <div>&nbsp;</div>
            <a class="btn btn-info btn-sm btn-block" href="/famiglie/index"><i class="fa fa-list" aria-hidden="true"></i> Torna all'elenco</a>

        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	$('#tessera').on('keypress', function(e) {
    	return e.which !== 13;
	});
});
</script>

<script type="text/javascript">
$(document).ready(function(){
	$('#num_componenti').focusout(function() {
		var famigliari = $('#num_componenti').val();

		$.ajax({
            url: "/famiglie/ajax_punti_carbonaro",
            dataType: "json",
            data: "id=" + famigliari,
            cache: false,
            success: function(message) {
				$('#punti').val(message.responseText);
				$('#punti_residui').val(message.responseText);
			}
		});
	});

	$('#punti').focusout(function() {
		var pt =  $('#punti').val()
		$('#punti_residui').val(pt);
	});
});
</script>

<script type="text/javascript">
$(document).ready(function(){
	$('#esenzione').change(function() {
		var c = $('select#esenzione option:selected').attr('value');

		if(c == 't') {
			$('#punti').val('-1');
			$('#punti_residui').val('-1');
			$('#punti').prop('readonly', true);
			$('#punti_residui').prop('readonly', true);
		}
		else {
			$('#punti').val('');
			$('#punti_residui').val('');
			$('#punti').prop('readonly', false);
			$('#punti_residui').prop('readonly', false);
		}
	});
});
</script>

<script type="text/javascript">
$(document).ready(function(){
    $('#natoil').datepicker();
    $('#iscrittoda').datepicker();
    $('#scadenza').datepicker();
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

<script type="text/javascript">
 $(document).ready(function(){
	$("#nazione_vis").autocomplete({

		source: function (request, response) {
			var re = $.ui.autocomplete.escapeRegex(request.term);
			var matcher = new RegExp("^" + re, "i");
			$.getJSON("/famiglie/ajax_nazioni", function (data) {
				response($.grep(($.map(data, function (v, i) {
					return {
						label: v.nome,
						value: v.nome,
						istat: v.cod_internazionale,
					};
				})), function (item) {
					return matcher.test(item.value);
				}))
			});
		},
		select: function (event, ui) {
		//  alert(ui.item ? ("You picked '" + ui.item.label + "' with an ID of " + ui.item.istat) : "Nothing selected, input was " + this.value);
			$('#nazione').val(ui.item.istat);
		//  console.log($('#comune').val + ' has faded!');
		},
		change: function (event, ui) {
		//  alert(ui.item ? ("You picked '" + ui.item.label + "' with an ID of " + ui.item.istat) : "Nothing selected, input was " + this.value);
			$('#nazione').val(ui.item.istat);
		//  console.log($('#comune').val+'has faded!')
		}

	});
 });
</script>

<script type="text/javascript">
 $(document).ready(function(){
	$("#nazionalita_vis").autocomplete({

		source: function (request, response) {
			var re = $.ui.autocomplete.escapeRegex(request.term);
			var matcher = new RegExp("^" + re, "i");
			$.getJSON("/famiglie/ajax_nazioni", function (data) {
				response($.grep(($.map(data, function (v, i) {
					return {
						label: v.nome,
						value: v.nome,
						istat: v.cod_internazionale,
					};
				})), function (item) {
					return matcher.test(item.value);
				}))
			});
		},
		select: function (event, ui) {
		//  alert(ui.item ? ("You picked '" + ui.item.label + "' with an ID of " + ui.item.istat) : "Nothing selected, input was " + this.value);
			$('#nazionalita').val(ui.item.istat);
		//  console.log($('#comune').val + ' has faded!');
		},
		change: function (event, ui) {
		//  alert(ui.item ? ("You picked '" + ui.item.label + "' with an ID of " + ui.item.istat) : "Nothing selected, input was " + this.value);
			$('#nazionalita').val(ui.item.istat);
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
					tessera: { required: true, regex: /^[a-zA-Z]{6}[0-9]{2}[a-zA-Z][0-9]{2}[a-zA-Z][0-9]{3}[a-zA-Z]$/ },
					cognome: { required: true,regex: /^[0-9 A-Za-z\'àèìòù\.&]+$/ },
					nome: { required: false, regex: /^[0-9 A-Za-z\'àèìòù]+$/ },
					natoil: { required: false, regex: /^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/ },
					luogo_nascita: { required: false, regex: /^[0-9 A-Za-z\'àèìòù]+$/ },
					nazione_vis: { required: false, regex: /^[0-9 A-Za-z\'àèìòù]+$/ },
					nazionalita_vis: { required: false, regex: /^[0-9 A-Za-z\'àèìòù]+$/ },
					sesso: { required: false },
					ente_proponente: { required: true },
					indirizzo: { required: true, regex: /^[0-9 A-Za-z\'\.\,\/àèìòù]+$/ },
					localita: { required: false, regex: /^[0-9 A-Za-z\'\.àèìòù]+$/ },
					cap: { required: true, regex: /^[0-9]{5}$/ },
					comune_nome: { required: true },
					telefono: { required:false, regex: /^[0-9]+$/ },
					cellulare: { required: false, regex: /^[0-9]{10}$/ },
					email: { required: false, regex: /^[\w\-\.]*[\w\.]\@[\w\.]*[\w\-\.]+[\w\-]+[\w]\.+[\w]+[\w $]/ },
					num_componenti: { required: true, regex: /^[1-9]\d*$/ },
					punti: { required: true, regex: /^[-1]|[0-9]+$/ },
					punti_residui: { required: true, regex: /^[-1]|[0-9]+$/ },
					giorno1: { required: false },
					orario: { required: false, regex: /^[0-9\:\.\- ]+$/ },
					iscrittoda: { required: true, regex: /^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/ },
					scadenza: { required: true, regex: /^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/ },
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
